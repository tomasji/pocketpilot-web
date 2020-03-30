<?php

declare(strict_types=1);

namespace PP\Airfield;

use Nette\Database\Context;
use Nette\Http\FileUpload;
use Nette\SmartObject;
use PDOException;
use RuntimeException;
use UnexpectedValueException;

/**
 * @author Andrej Souček
 */
class AirfieldsImporter {

	use SmartObject;

	/**
	 * @var Context
	 */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @param FileUpload $upload
	 * @throws RuntimeException
	 * @throws UnexpectedValueException
	 */
	public function process(FileUpload $upload) {
		switch(pathinfo($upload->getName(), PATHINFO_EXTENSION)) {
			case 'csv':
				$this->insert(self::map($upload));
				break;
			default:
				throw new UnexpectedValueException("Invalid file type. Only CSV files allowed.");
		}
	}

	/**
	 * @param array<AirfieldEntry> $airfields
	 * @throws RuntimeException
	 */
	private function insert(array $airfields): void {
		try {
			$this->database->beginTransaction();
			/** @var AirfieldEntry $airfield */
			foreach ($airfields as $airfield) {
				$this->database->table(AirfieldDatabaseDef::TABLE_NAME)->insert([
					AirfieldDatabaseDef::COLUMN_NAME => $airfield->getName(),
					AirfieldDatabaseDef::COLUMN_DESCRIPTION => $airfield->getDescription(),
					AirfieldDatabaseDef::COLUMN_LOCATION => $this->database::literal(
						"ST_GeogFromText('POINT(' || {$airfield->getLongitude()} || ' ' || {$airfield->getLatitude()} || ')')"
					)
				]);
			}
		} catch (PDOException $e) {
			$this->database->rollBack();
			throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
		}
		$this->database->commit();
	}

	private static function map(FileUpload $fileUpload): array {
		$ret = [];
		$rows = str_getcsv($fileUpload->getContents(), "\n"); //parse the rows
		unset($rows[0]);
		foreach($rows as $row) {
			$values = str_getcsv($row, ";");
			$ret[] = new AirfieldEntry($values[0], $values[1], $values[2], $values[3]);
		}
		return $ret;
	}
}
