<?php

declare(strict_types=1);

namespace PP\User;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\Validators;
use PP\IncorrectCredentialsException;

/**
 * @author Andrej Souček
 */
class UserRead {

	use SmartObject;

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @param string $email
	 * @return UserEntry
	 * @throws IncorrectCredentialsException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function fetch(string $email): UserEntry {
		Validators::assert($email, 'email');
		$row = $this->database->table(UserDatabaseDef::TABLE_NAME)->where(UserDatabaseDef::COLUMN_EMAIL, $email)->fetch();
		if ($row) {
			return $this->toEntity($row);
		} else {
			throw new IncorrectCredentialsException("User with email '$email' does not exists.");
		}
	}

	/**
	 * @param ActiveRow $data
	 * @return UserEntry
	 */
	private function toEntity(ActiveRow $data): UserEntry {
		return new UserEntry(
			$data->offsetGet(UserDatabaseDef::COLUMN_ID),
			$data->offsetGet(UserDatabaseDef::COLUMN_NAME),
			$data->offsetGet(UserDatabaseDef::COLUMN_EMAIL),
			$data->offsetGet(UserDatabaseDef::COLUMN_ROLE),
			$data->offsetGet(UserDatabaseDef::COLUMN_TOKEN)
		);
	}
}
