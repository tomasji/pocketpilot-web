<?php

namespace PP\User;

use Nette\Database\Context;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\Passwords;

/**
 * @author Andrej Souček
 */
class UserRegister {

	/** @var Context */
	private $database;

	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @param string $username
	 * @param string $email
	 * @param string $fb_uid
	 * @param string $password
	 * @throws DuplicateNameException
	 */
	public function registerUser(string $username, string $email, string $fb_uid = null, string $password = null) : void {
		try {
			$this->database->table(UserDatabaseDef::TABLE_NAME)->insert(array(
				UserDatabaseDef::COLUMN_NAME => $username,
				UserDatabaseDef::COLUMN_EMAIL => $email,
				UserDatabaseDef::COLUMN_FB_UID => $fb_uid,
				UserDatabaseDef::COLUMN_PASSWORD_HASH => $password ? Passwords::hash($password) : null,
			));
		} catch (UniqueConstraintViolationException $e) {
			throw new DuplicateNameException("Username already exists.");
		}
	}
}
