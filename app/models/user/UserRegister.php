<?php

namespace PP\User;

use Nette\Database\Context;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\Passwords;
use Nette\Utils\Validators;

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
	 * @param string|null $fbUid
	 * @param string $password
	 * @throws DuplicateNameException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function process(string $username, string $email, string $fbUid = null, string $password = null) : void {
		Validators::assert($username, 'string:1..');
		Validators::assert($email, 'email');
		Validators::assert($password, 'string:1..|null');
		Validators::assert($fbUid, 'string:1..|null');
		try {
			$this->database->table(UserDatabaseDef::TABLE_NAME)->insert(array(
				UserDatabaseDef::COLUMN_NAME => $username,
				UserDatabaseDef::COLUMN_EMAIL => $email,
				UserDatabaseDef::COLUMN_FB_UID => $fbUid,
				UserDatabaseDef::COLUMN_PASSWORD_HASH => $password ? Passwords::hash($password) : null,
			));
		} catch (UniqueConstraintViolationException $e) {
			throw new DuplicateNameException("Username already exists.");
		}
	}
}
