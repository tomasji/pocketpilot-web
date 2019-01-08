<?php

namespace PP\Track;

use Nette\Database\Context;
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
	public function fetchBy(int $userId) : array {
		$ret = [];
		$row = $this->database->table(TrackDatabaseDef::TABLE_NAME)
			->select(
				TrackDatabaseDef::COLUMN_ID . ',' .
				TrackDatabaseDef::COLUMN_USER_ID . ',' .
				TrackDatabaseDef::COLUMN_NAME . ',' .
				TrackDatabaseDef::COLUMN_CREATION_DATE . ',' .
				'ST_Length('.TrackDatabaseDef::COLUMN_TRACK.') AS length' . ',' .
				'ST_AsGeoJSON('.TrackDatabaseDef::COLUMN_TRACK.') AS geojson')
			->where(TrackDatabaseDef::COLUMN_USER_ID, $userId)->fetchAll();
		foreach ($row as $entry) {
			$ret[$entry[TrackDatabaseDef::COLUMN_ID]] = $this->toEntity($entry);
		}
		return $ret;
	}

	/**
	 * @param \Traversable $data
	 * @return TrackEntry
	 */
	private function toEntity(\Traversable $data) : TrackEntry {
		return new TrackEntry(
			$data[TrackDatabaseDef::COLUMN_ID],
			$data['geojson'],
			$data['length'],
			$data[TrackDatabaseDef::COLUMN_USER_ID],
			$data[TrackDatabaseDef::COLUMN_NAME],
			$data[TrackDatabaseDef::COLUMN_CREATION_DATE]
		);
	}
}
