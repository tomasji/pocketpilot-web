<?php

declare(strict_types=1);

namespace PP\Track;

use Nette\Database\Context;
use Nette\SmartObject;
use Nette\Utils\Validators;

/**
 * @author Andrej Souček
 */
class TrackDelete {

	use SmartObject;

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	public function process(int $trackId): int {
		Validators::assert($trackId, 'numericint:1..');
		return $this->database->table(TrackDatabaseDef::TABLE_NAME)
			->where(TrackDatabaseDef::COLUMN_ID, $trackId)
			->delete();
	}
}
