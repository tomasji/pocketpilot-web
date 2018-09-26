<?php

namespace PP\User;

use Nette\Utils\Validators;

/**
 * @author Andrej Souček
 */
class PasswordCredentials implements Credentials {

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $password;

	public function __construct(string $email, string $password) {
		Validators::assert($email, 'email');
		Validators::assert($password, 'string:1..');
		$this->email = $email;
		$this->password = $password;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getAuthString() {
		return $this->password;
	}
}
