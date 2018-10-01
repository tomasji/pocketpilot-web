<?php

namespace PP\User;

use PP\IncorrectCredentialsException;

/**
 * @author Andrej Souček
 */
class UserModel {

	/**
	 * @var UserRead
	 */
	private $read;
	/**
	 * @var UserRegister
	 */
	private $register;
	/**
	 * @var UserUpdate
	 */
	private $update;

	public function __construct(UserRead $read, UserRegister $register, UserUpdate $update) {
		$this->read = $read;
		$this->register = $register;
		$this->update = $update;
	}

	/**
	 * @param string $email
	 * @return UserEntry
	 * @throws \Nette\Utils\AssertionException
	 */
	public function getUserBy(string $email) : UserEntry {
		return $this->read->fetchBy($email);
	}

	/**
	 * @param string $username
	 * @param string $email
	 * @param string|null $fb_uid
	 * @param string|null $password
	 * @throws IncorrectCredentialsException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function registerUser(string $username, string $email, string $fb_uid = null, string $password = null) : void {
		$this->register->process($username, $email, $fb_uid, $password);
	}

	/**
	 * @param UserChanges $changes
	 */
	public function updateUser(UserChanges $changes) : void {
		$this->update->process($changes);
	}
}
