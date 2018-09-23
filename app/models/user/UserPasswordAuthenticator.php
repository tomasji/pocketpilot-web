<?php

namespace PP\User;

use Nette\Database\Context;
use Nette\Security\Passwords;
use Nette\Utils\Validators;

class UserPasswordAuthenticator {

	/**
	 * @var Context
	 */
	private $database;

	/**
	 * @var UserRead
	 */
	private $read;

	public function __construct(Context $database, UserRead $read) {
		$this->database = $database;
		$this->read = $read;
	}

	/**
	 * Performs an authentication.
	 * @param string $email
	 * @param string $password
	 * @return UserEntry
	 * @throws \Nette\Utils\AssertionException
	 * @throws EmailNotFoundException
	 * @throws IncorrectPasswordException
	 */
	public function authenticate(string $email, string $password) : UserEntry {
		Validators::assert($email, 'email');
		Validators::assert($password, 'string:1..');
		$entry = $this->read->fetchBy($email);
		$hash = $this->database->table(UserDatabaseDef::TABLE_NAME)
			->where(UserDatabaseDef::COLUMN_ID, $entry->getId())->fetchField(UserDatabaseDef::COLUMN_PASSWORD_HASH);
		if (!Passwords::verify($password, $hash)) {
			throw new IncorrectPasswordException('Entered password is incorrect.');
		} elseif (Passwords::needsRehash($hash)) {
			$this->database->table(UserDatabaseDef::TABLE_NAME)
				->where(UserDatabaseDef::COLUMN_ID, $entry->getId())
				->update([UserDatabaseDef::COLUMN_PASSWORD_HASH => Passwords::hash($password)]);
		}
		return $entry;
	}
}
