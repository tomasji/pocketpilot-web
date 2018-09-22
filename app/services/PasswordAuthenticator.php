<?php

namespace PP\User;

use Nette\SmartObject;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

class PasswordAuthenticator implements IAuthenticator {

	use SmartObject;

	/**
	 * @var UserModel
	 */
	public $userModel;

	public function __construct(UserModel $userModel) {
		$this->userModel = $userModel;
	}

	/**
	 * Performs an authentication.
	 * @param array $credentials
	 * @return Identity
	 * @throws AuthenticationException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function authenticate(array $credentials) : Identity {
		list($email, $password) = $credentials;
		try {
			$user = $this->userModel->getUserBy($email);
		} catch (EmailNotFoundException $e) {
			throw new AuthenticationException($e->getMessage(), self::IDENTITY_NOT_FOUND, $e);
		}

		if (!Passwords::verify($password, $user->getHash())) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		} elseif (Passwords::needsRehash($user->getHash())) {
			$entry = new UserEntry($user->getId(), $user->getName(), $user->getEmail(), $user->getRole(), Passwords::hash($password), $user->getFbUid());
			$this->userModel->updateUser($user->getId(), $entry);
		}
		return $this->userModel->createIdentity($user);
	}
}
