<?php

declare(strict_types=1);

namespace PP\User;

use Nette\Database\Context;
use Nette\SmartObject;
use PP\IncorrectCredentialsException;

class TokenAuthenticator {

	use SmartObject;

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
	 * @param TokenCredentials $credentials
	 * @return UserEntry
	 * @throws IncorrectCredentialsException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function authenticate(TokenCredentials $credentials): UserEntry {
		$entry = $this->read->fetchBy($credentials->getEmail());
		$row = $this->database->table(UserDatabaseDef::TABLE_NAME)
			->where(UserDatabaseDef::COLUMN_ID, $entry->getId())
			->fetch();
		if ($credentials->getAuthString() !== $row->token) {
			throw new IncorrectCredentialsException('Token is incorrect.');
		}
		return $entry;
	}
}
