<?php

namespace PP\User;

use Nette\Security\Identity;

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
	 * @throws EmailNotFoundException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function getUserBy(string $email) {
		return $this->read->getUserBy($email);
	}

	/**
	 * @param string $username
	 * @param string $email
	 * @param string|null $fb_uid
	 * @param string|null $password
	 * @throws DuplicateNameException
	 */
	public function registerUser(string $username, string $email, string $fb_uid = null, string $password = null) {
		$this->register->registerUser($username, $email, $fb_uid, $password);
	}

	/**
	 * @param int $id
	 * @param UserEntry $user
	 */
	public function updateUser(int $id, UserEntry $user) {
		$this->update->updateUser($id, $user);
	}

	/**
	 * @param UserEntry $user
	 * @return Identity
	 */
	public function createIdentity(UserEntry $user) : Identity{
		return new Identity($user->getId(), [$user->getRole()], ["username" => $user->getName()]);
	}
}
