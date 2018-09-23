<?php

namespace PP\User;

use Nette\Utils\Validators;

class UserEntry {

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

	public function __construct(int $id, string $name, string $email, string $role) {
		Validators::assert($id, 'int:1..');
		Validators::assert($name, 'string:1..');
		Validators::assert($email, 'email');
		Validators::assert($role, 'string:1..');
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->role = $role;
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
}
