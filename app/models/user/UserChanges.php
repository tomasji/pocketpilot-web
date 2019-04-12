<?php

declare(strict_types=1);

namespace PP\User;

use Nette\SmartObject;
use Nette\Utils\Validators;

/**
 * @author Andrej Souček
 */
class UserChanges {

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
	 * @param int $id
	 * @throws \Nette\Utils\AssertionException
	 */
	public function __construct(int $id) {
		Validators::assert($id, 'int..');
		$this->id = $id;
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
	 * @param string $name
	 * @throws \Nette\Utils\AssertionException
	 */
	public function setName(string $name): void {
		Validators::assert($name, 'string:1..');
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * @param string $email
	 * @throws \Nette\Utils\AssertionException
	 */
	public function setEmail(string $email): void {
		Validators::assert($email, 'email');
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getRole(): string {
		return $this->role;
	}

	/**
	 * @param string $role
	 * @throws \Nette\Utils\AssertionException
	 */
	public function setRole(string $role): void {
		Validators::assert($role, 'string:1..');
		$this->role = $role;
	}
}
