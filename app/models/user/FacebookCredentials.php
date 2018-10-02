<?php

namespace PP\User;

use Nette\Utils\Validators;
use PP\User\Credentials;

/**
 * @author Andrej Souček
 */
class FacebookCredentials implements Credentials {

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $uid;

	/**
	 * @var string
	 */
	private $firstName;

	public function __construct(string $email, string $uid, string $firstName) {
		Validators::assert($email, 'email');
		Validators::assert($uid, 'string:1..');
		Validators::assert($firstName, 'string:1..');
		$this->email = $email;
		$this->uid = $uid;
		$this->firstName = $firstName;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getAuthString() {
		return $this->uid;
	}

	public function getFirstName() {
		return $this->firstName;
	}
}
