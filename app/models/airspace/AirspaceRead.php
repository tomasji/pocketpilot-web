<?php

declare(strict_types=1);

namespace PP\Airspace;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\SmartObject;
use PP\Track\TrackDatabaseDef;

/**
 * @author Andrej Souček
 */
class AirspaceRead
{
    use SmartObject;

    /** @var Context */
    private $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    /**
     * @param array<array<float, float>> $latlngs
     * @return array<AirspaceEntry>
     */
    public function fetchIntersections(array $latlngs): array
    {
        $ret = [];
        $rows = $this->database
            ->query(
                "SELECT " .
                    AirspaceDatabaseDef::COLUMN_NAME  . ', ' .
                    AirspaceDatabaseDef::COLUMN_TYPE  . ' ' .
                "FROM " . AirspaceDatabaseDef::TABLE_NAME . ' ' .
                "WHERE ST_Intersects(airspace.location, ST_GeomFromText('{$this->getLinestringBy($latlngs)}', 4326)) " .
                "AND " . AirspaceDatabaseDef::COLUMN_TYPE . " NOT IN ('SECTOR', 'FIR') " .
                "ORDER BY " . AirspaceDatabaseDef::COLUMN_TYPE . " ASC, " . AirspaceDatabaseDef::COLUMN_NAME . " ASC"
            )->fetchAll();
        foreach ($rows as $row) {
            $ret[] = $this->toEntity($row);
        }
        return $ret;
    }

    private function toEntity(IRow $data): AirspaceEntry
    {
        return new AirspaceEntry(
            (string)$data->offsetGet(AirspaceDatabaseDef::COLUMN_NAME),
            (string)$data->offsetGet(AirspaceDatabaseDef::COLUMN_TYPE)
        );
    }

    private function getLinestringBy(array $latlngs): string
    {
        $out = 'LINESTRING(';
        $out .= implode(', ', array_map(static function ($latlng) {
            return "$latlng[1] $latlng[0]";
        }, $latlngs));
        $out .= ')';
        return $out;
    }
}
