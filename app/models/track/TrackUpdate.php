<?php

declare(strict_types=1);

namespace PP\Track;

use Nette\Database\Context;
use Nette\SmartObject;
use Nette\Utils\Validators;

/**
 * @author Andrej Souček
 */
class TrackUpdate {

	use SmartObject;

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	public function process(int $trackId, string $trackName, array $waypoints) : int {
		Validators::assert($trackId, 'numericint:1..');
		Validators::assert($trackName, 'string:1..50');
		return $this->database->table(TrackDatabaseDef::TABLE_NAME)
			->where(TrackDatabaseDef::COLUMN_ID, $trackId)
			->update([
				TrackDatabaseDef::COLUMN_NAME => $trackName,
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
