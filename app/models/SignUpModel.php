<?php

namespace PP;

use PP\User\UserRegister;

/**
 * @author Andrej Souček
 */
class SignUpModel {

	/**
	 * @var UserRegister
	 */
	private $register;

	public function __construct(UserRegister $register) {
		$this->register = $register;
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
}
