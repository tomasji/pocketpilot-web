<?php

namespace PP\User;

use Nette\Database\Context;

/**
 * @author Andrej Souček
 */
class UserUpdate {

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @param int $id
	 * @param UserEntry $user
	 */
	public function updateUser(int $id, UserEntry $user) : void {
		$this->database->table(UserDatabaseDef::TABLE_NAME)
			->where(UserDatabaseDef::COLUMN_ID, $id)
			->update($this->toArray($user));
	}

	/**
	 * @param UserEntry $user
	 * @return array
	 */
	private function toArray(UserEntry $user) : array {
		return [
			UserDatabaseDef::COLUMN_ID => $user->getId(),
			UserDatabaseDef::COLUMN_NAME => $user->getName(),
			UserDatabaseDef::COLUMN_EMAIL => $user->getEmail(),
			UserDatabaseDef::COLUMN_ROLE => $user->getRole(),
			UserDatabaseDef::COLUMN_PASSWORD_HASH => $user->getHash(),
			UserDatabaseDef::COLUMN_FB_UID => $user->getFbUid()
		];
	}
}
