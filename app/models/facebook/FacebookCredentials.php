<?php

namespace PP\Facebook;

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

	public function __construct(string $email, string $uid) {
		Validators::assert($email, 'email');
		Validators::assert($uid, 'string:1..');
		$this->email = $email;
		$this->uid = $uid;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getAuthString() {
		return $this->uid;
	}
}
