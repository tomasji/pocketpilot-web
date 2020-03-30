<?php

declare(strict_types=1);

namespace PP\Airfield;

use Nette\Database\Context;
use Nette\Database\IRow;
use Nette\SmartObject;

/**
 * @author Andrej Souček
 */
class AirfieldRead {

	use SmartObject;

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @return array<AirfieldEntry>
	 */
	public function fetchAll(): array {
		$ret = [];
		$rows = $this->database
			->table(AirfieldDatabaseDef::TABLE_NAME)
			->select(
				AirfieldDatabaseDef::COLUMN_NAME  . ',' .
				AirfieldDatabaseDef::COLUMN_DESCRIPTION . ',' .
				'ST_X(' . AirfieldDatabaseDef::COLUMN_LOCATION . '::geometry) AS longitude' . ',' .
				'ST_Y(' . AirfieldDatabaseDef::COLUMN_LOCATION . '::geometry) AS latitude'
			)
			->order(AirfieldDatabaseDef::COLUMN_NAME);
		foreach ($rows as $row) {
			$ret[] = $this->toEntity($row);
		}
		return $ret;
	}

	private function toEntity(IRow $data): AirfieldEntry {
		return new AirfieldEntry(
			(string)$data->offsetGet(AirfieldDatabaseDef::COLUMN_NAME),
			(string)$data->offsetGet(AirfieldDatabaseDef::COLUMN_DESCRIPTION),
			(string)$data->offsetGet('longitude'),
			(string)$data->offsetGet('latitude')
		);
	}
}
