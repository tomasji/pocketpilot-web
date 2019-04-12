<?php

declare(strict_types=1);

namespace PP\User;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\Passwords;
use Nette\SmartObject;
use Nette\Utils\Validators;
use PP\IncorrectCredentialsException;

/**
 * @author Andrej Souček
 */
class UserRegister {

	use SmartObject;

	/** @var Context */
	private $database;

	/**
	 * @var Passwords
	 */
	private $passwords;

	public function __construct(Context $database, Passwords $passwords) {
		$this->database = $database;
		$this->passwords = $passwords;
	}

	/**
	 * @param string $username
	 * @param string $email
	 * @param string|null $fbUid
	 * @param string $password
	 * @return ActiveRow
	 * @throws IncorrectCredentialsException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function process(string $username, string $email, string $fbUid = null, string $password = null): ?ActiveRow {
		Validators::assert($username, 'string:1..');
		Validators::assert($email, 'email');
		Validators::assert($password, 'string:1..|null');
		Validators::assert($fbUid, 'string:1..|null');
		try {
			return $this->database->table(UserDatabaseDef::TABLE_NAME)->insert(array(
				UserDatabaseDef::COLUMN_NAME => $username,
				UserDatabaseDef::COLUMN_EMAIL => $email,
				UserDatabaseDef::COLUMN_FB_UID => $fbUid,
				UserDatabaseDef::COLUMN_PASSWORD_HASH => $password ? $this->passwords->hash($password) : null,
			));
		} catch (UniqueConstraintViolationException $e) {
			throw new IncorrectCredentialsException("Username already exists.");
		}
	}
}
