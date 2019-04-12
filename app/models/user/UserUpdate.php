<?php

declare(strict_types=1);

namespace PP\User;

use Nette\Database\Context;
use Nette\SmartObject;

/**
 * @author Andrej Souček
 */
class UserUpdate {

	use SmartObject;

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @param UserChanges $changes
	 */
	public function process(UserChanges $changes): void {
		$this->database->table(UserDatabaseDef::TABLE_NAME)
			->where(UserDatabaseDef::COLUMN_ID, $changes->getId())
			->update($this->toArray($changes));
	}

	/**
	 * @param UserChanges $changes
	 * @return array
	 */
	private function toArray(UserChanges $changes): array {
		$xs = [
			UserDatabaseDef::COLUMN_NAME => $changes->getName(),
			UserDatabaseDef::COLUMN_EMAIL => $changes->getEmail(),
			UserDatabaseDef::COLUMN_ROLE => $changes->getRole()
		];
		return array_filter($xs);
	}
}
