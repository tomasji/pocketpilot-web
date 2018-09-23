<?php

namespace PP\User;


use Facebook\GraphNodes\GraphUser;
use Nette\Database\Context;
use Nette\Security\Identity;

/**
 * @author Andrej Souček
 */
class UserFacebookAuthenticator {

	/**
	 * @var Context
	 */
	private $database;

	/**
	 * @var UserRegister
	 */
	private $register;

	/**
	 * @var UserRead
	 */
	private $read;

	public function __construct(Context $database, UserRegister $register, UserRead $read) {
		$this->database = $database;
		$this->register = $register;
		$this->read = $read;
	}

	/**
	 * @param GraphUser $graphUser
	 * @return UserEntry
	 * @throws \Nette\Utils\AssertionException
	 */
	public function authenticate(GraphUser $graphUser) : UserEntry {
		try {
			$this->register->process($graphUser->getFirstName(), $graphUser->getEmail(), $graphUser->getId());
		} catch(DuplicateNameException $e) {
		}
		$entry = $this->read->fetchBy($graphUser->getEmail());
		$uid = $this->database->table(UserDatabaseDef::TABLE_NAME)
			->where(UserDatabaseDef::COLUMN_ID, $entry->getId())->fetchField(UserDatabaseDef::COLUMN_FB_UID);
		if ($entry && !$uid) {
			$this->database->table(UserDatabaseDef::TABLE_NAME)
				->where(UserDatabaseDef::COLUMN_ID, $entry->getId())
				->update([UserDatabaseDef::COLUMN_FB_UID => $graphUser->getId()]);
		}
		return $entry;
	}
}
