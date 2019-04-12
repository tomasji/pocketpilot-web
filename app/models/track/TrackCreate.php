<?php

declare(strict_types=1);

namespace PP\Track;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\Validators;

/**
 * @author Andrej Souček
 */
class TrackCreate {

	use SmartObject;

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	public function process(string $trackName, int $userId, array $waypoints) : ?ActiveRow {
		Validators::assert($trackName, 'string:1..50');
		Validators::assert($userId, 'numericint:1..');
		return $this->database->table(TrackDatabaseDef::TABLE_NAME)->insert([
			TrackDatabaseDef::COLUMN_NAME => $trackName,
			TrackDatabaseDef::COLUMN_USER_ID => $userId,
			TrackDatabaseDef::COLUMN_TRACK => $this->database::literal($this->prepareQuery($waypoints))
		]);
	}

	private function prepareQuery(array $waypoints): string {
		$out = 'ST_GeomFromText(\'LINESTRING(';
		$points = [];
		foreach ($waypoints as $waypoint) {
			$lat = $waypoint['lat'];
			$lng = $waypoint['lng'];
			$points[] = "$lat $lng";
		}
		$out .= implode(',', $points);
		$out .= ')\')';
		return $out;
	}
}
