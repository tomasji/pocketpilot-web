<?php

namespace PP\Facebook;

use Nette\Database\Context;
use Nette\Utils\AssertionException;
use PP\User\Credentials;
use PP\User\EmailNotFoundException;
use PP\User\UserDatabaseDef;
use PP\User\UserEntry;
use PP\User\UserRead;
use PP\User\UserRegister;

/**
 * @author Andrej Souček
 */
class FacebookAuthenticator {

	/**
	 * @var Context
	 */
	private $database;
	/**
	 * @var UserRead
	 */
	private $read;

	public function __construct(Context $database, UserRead $read, UserRegister $register) {
		$this->database = $database;
		$this->read = $read;
	}

	/**
	 * @param Credentials $credentials
	 * @return UserEntry
	 * @throws AssertionException
	 * @throws IncorrectUidException
	 * @throws EmailNotFoundException
	 */
	public function authenticate(Credentials $credentials) : UserEntry {
		$entry = $this->read->fetchBy($credentials->getEmail());
		$uid = $this->database->table(UserDatabaseDef::TABLE_NAME)
			->where(UserDatabaseDef::COLUMN_ID, $entry->getId())->fetchField(UserDatabaseDef::COLUMN_FB_UID);
		if ($uid !== $credentials->getAuthString()) {
			throw new IncorrectUidException("FB user ID does not match with local FB uID.");
		}
		return $entry;
	}
}
