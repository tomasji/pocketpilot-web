<?php
/*
 * Copyright © 2000-2019 ANTEE s.r.o., All rights reserved. Confidential.
 */

namespace PP\User;

use Nette\SmartObject;
use Nette\Utils\Validators;

/**
 * @author Andrej Souček
 */
class TokenCredentials implements Credentials {

	use SmartObject;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $token;

	public function __construct(string $email, string $token) {
		Validators::assert($email, 'email');
		Validators::assert($token, 'string:32');
		$this->email = $email;
		$this->token = $token;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getAuthString() {
		return $this->token;
	}
}
