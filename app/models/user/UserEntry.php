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
	private $hash;

	/**
	 * @var string
	 */
	private $fbUid;

	/**
	 * @var string
	 */
	private $role;

	public function __construct(int $id, string $name, string $email, string $role, string $hash = null, string $fbUid = null) {
		Validators::assert($id, 'int:1..');
		Validators::assert($name, 'string:1..');
		Validators::assert($email, 'email');
		Validators::assert($role, 'string:1..');
		Validators::assert($hash, 'string:1..|null');
		Validators::assert($fbUid, 'string:1..|null');
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->role = $role;
		$this->hash = $hash;
		$this->fbUid = $fbUid;
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
	 * @return string|null
	 */
	public function getHash(): ?string {
		return $this->hash;
	}

	/**
	 * @return string|null
	 */
	public function getFbUid(): ?string {
		return $this->fbUid;
	}
}
