<?php

declare(strict_types=1);

namespace PP\Track;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;

/**
 * @author Andrej Souček
 */
class TrackRead {

	use SmartObject;

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @param int $userId
	 * @return array [trackId => TrackEntry]
	 */
	public function fetchBy(int $userId): array {
		$ret = [];
		$rows = $this->database->table(TrackDatabaseDef::TABLE_NAME)
			->select(
				TrackDatabaseDef::COLUMN_ID . ',' .
				TrackDatabaseDef::COLUMN_USER_ID . ',' .
				TrackDatabaseDef::COLUMN_NAME . ',' .
				TrackDatabaseDef::COLUMN_CREATION_DATE . ',' .
				'ST_Length('.TrackDatabaseDef::COLUMN_TRACK.') AS length' . ',' .
				'ST_AsGeoJSON('.TrackDatabaseDef::COLUMN_TRACK.') AS geojson')
			->where(TrackDatabaseDef::COLUMN_USER_ID, $userId)->fetchAll();
		foreach ($rows as $row) {
			$ret[$row[TrackDatabaseDef::COLUMN_ID]] = $this->toEntity($row);
		}
		return $ret;
	}

	/**
	 * @param ActiveRow $data
	 * @return TrackEntry
	 */
	private function toEntity(ActiveRow $data): TrackEntry {
		return new TrackEntry(
			$data->offsetGet(TrackDatabaseDef::COLUMN_ID),
			$data->offsetGet('geojson'),
			$data->offsetGet('length'),
			$data->offsetGet(TrackDatabaseDef::COLUMN_USER_ID),
			$data->offsetGet(TrackDatabaseDef::COLUMN_NAME),
			$data->offsetGet(TrackDatabaseDef::COLUMN_CREATION_DATE)
		);
	}
}
