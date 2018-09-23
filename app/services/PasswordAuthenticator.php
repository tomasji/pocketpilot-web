<?php

namespace PP\User;

use Nette\SmartObject;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;

class PasswordAuthenticator implements IAuthenticator {

	use SmartObject;

	/**
	 * @var UserAuthenticator
	 */
	private $authenticator;

	public function __construct(UserAuthenticator $authenticator) {
		$this->authenticator = $authenticator;
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
			$user = $this->authenticator->authenticate($email, $password);
		} catch (EmailNotFoundException $e) {
			throw new AuthenticationException($e->getMessage(), self::IDENTITY_NOT_FOUND, $e);
		} catch (IncorrectPasswordException $e) {
			throw new AuthenticationException($e->getMessage(), self::INVALID_CREDENTIAL, $e);
		}
		return $this->createIdentity($user);
	}

	/**
	 * @param UserEntry $user
	 * @return Identity
	 */
	private function createIdentity(UserEntry $user) : Identity{
		return new Identity($user->getId(), [$user->getRole()], ["username" => $user->getName()]);
	}
}
