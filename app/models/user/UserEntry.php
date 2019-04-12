<?php

namespace PP\User;

use Nette\SmartObject;
use Nette\Utils\Validators;

class UserEntry {

	use SmartObject;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $role;

	/**
	 * @var string
	 */
	private $token;

	public function __construct(int $id, string $name, string $email, string $role, string $token) {
		Validators::assert($id, 'int:1..');
		Validators::assert($name, 'string:1..');
		Validators::assert($email, 'email');
		Validators::assert($role, 'string:1..');
		Validators::assert($token, 'string:32');
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->role = $role;
		$this->token = $token;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * @return string
	 */
	public function getRole(): string {
		return $this->role;
	}

	/**
	 * @return string
	 */
	public function getToken(): string {
		return $this->token;
	}
}
