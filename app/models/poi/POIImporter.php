<?php

declare(strict_types=1);

namespace PP\POI;

use Nette\Database\Context;
use Nette\Http\FileUpload;
use Nette\SmartObject;
use PDOException;
use RuntimeException;
use UnexpectedValueException;

/**
 * @author Andrej Souček
 */
class POIImporter
{
    use SmartObject;

    /**
     * @var Context
     */
    private $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    /**
     * @param FileUpload $upload
     * @throws RuntimeException
     * @throws UnexpectedValueException
     */
    public function process(FileUpload $upload)
    {
        switch (pathinfo($upload->getName(), PATHINFO_EXTENSION)) {
            case 'csv':
                $this->insert(self::map($upload));
                break;
            default:
                throw new UnexpectedValueException("Invalid file type. Only CSV files allowed.");
        }
    }

    /**
     * @param array<POIEntry> $POI
     * @throws RuntimeException
     */
    private function insert(array $POI): void
    {
        try {
            $this->database->beginTransaction();
            if (count($POI) <= 0) {
                throw new RuntimeException('Empty file.');
            }
            $this->database->table(POIDatabaseDef::TABLE_NAME)->delete();
            /** @var POIEntry $point */
            foreach ($POI as $point) {
                $this->database->table(POIDatabaseDef::TABLE_NAME)->insert([
                    POIDatabaseDef::COLUMN_NAME => $point->getName(),
                    POIDatabaseDef::COLUMN_DESCRIPTION => $point->getDescription(),
                    POIDatabaseDef::COLUMN_LOCATION => $this->database::literal(
                        "ST_GeogFromText(" .
                        "'POINT(' || {$point->getLongitude()} || ' ' || {$point->getLatitude()} || ')')"
                    )
                ]);
            }
        } catch (PDOException $e) {
            $this->database->rollBack();
            throw new RuntimeException($e->getMessage());
        }
        $this->database->commit();
    }

    private static function map(FileUpload $fileUpload): array
    {
        $ret = [];
        $rows = str_getcsv($fileUpload->getContents(), "\n"); //parse the rows
        unset($rows[0]);
        foreach ($rows as $row) {
            $values = str_getcsv($row, ";");
            $ret[] = new POIEntry($values[0], $values[1], $values[2], $values[3]);
        }
        return $ret;
    }
}
