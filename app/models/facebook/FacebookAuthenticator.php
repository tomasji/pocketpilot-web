<?php

namespace PP\Facebook;

use Facebook\GraphNodes\GraphUser;
use Nette\Database\Context;
use Nette\Utils\AssertionException;
use PP\User\Credentials;
use PP\User\DuplicateNameException;
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

	/**
	 * @var UserRegister
	 */
	private $register;

	public function __construct(Context $database, UserRead $read, UserRegister $register) {
		$this->database = $database;
		$this->read = $read;
		$this->register = $register;
	}

	/**
	 * @param FacebookCredentials $credentials
	 * @return UserEntry
	 * @throws AssertionException
	 */
	public function authenticate(FacebookCredentials $credentials) : UserEntry {
		$row = $this->database->table(UserDatabaseDef::TABLE_NAME)->where(UserDatabaseDef::COLUMN_EMAIL, $credentials->getEmail())->fetch();
		if (!$row) {
			$row = $this->register->process($credentials->getFirstName(), $credentials->getEmail(), $credentials->getAuthString());
		}
		if ($row && !isset($row[UserDatabaseDef::COLUMN_FB_UID])) {
			$this->updateMissingUid($credentials);
		}
		if ($row && isset($row[UserDatabaseDef::COLUMN_FB_UID]) && $row[UserDatabaseDef::COLUMN_FB_UID] === $credentials->getAuthString()) {
			return $this->read->fetchBy($credentials->getEmail());
		} else {
			throw new IncorrectUidException("FB user ID does not match with local FB uID.");
		}
	}

	/**
	 * @param FacebookCredentials $credentials
	 */
	private function updateMissingUid(FacebookCredentials $credentials) : void {
		$this->database->table(UserDatabaseDef::TABLE_NAME)
			->where(UserDatabaseDef::COLUMN_EMAIL, $credentials->getEmail())
			->update([UserDatabaseDef::COLUMN_FB_UID => $credentials->getAuthString()]);
	}
}
