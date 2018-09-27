<?php

namespace PP\User;

use Nette\Database\Context;
use Nette\Security\Passwords;

class PasswordAuthenticator {

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
	 * @param PasswordCredentials $credentials
	 * @return UserEntry
	 * @throws EmailNotFoundException
	 * @throws IncorrectPasswordException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function authenticate(PasswordCredentials $credentials) : UserEntry {
		$entry = $this->read->fetchBy($credentials->getEmail());
		$hash = $this->database->table(UserDatabaseDef::TABLE_NAME)
			->where(UserDatabaseDef::COLUMN_ID, $entry->getId())->fetchField(UserDatabaseDef::COLUMN_PASSWORD_HASH);
		if (!Passwords::verify($credentials->getAuthString(), $hash)) {
			throw new IncorrectPasswordException('Entered password is incorrect.');
		} elseif (Passwords::needsRehash($hash)) {
			$this->database->table(UserDatabaseDef::TABLE_NAME)
				->where(UserDatabaseDef::COLUMN_ID, $entry->getId())
				->update([UserDatabaseDef::COLUMN_PASSWORD_HASH => Passwords::hash($credentials->getAuthString())]);
		}
		return $entry;
	}
}
