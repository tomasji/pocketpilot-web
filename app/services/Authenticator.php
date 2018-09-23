<?php

namespace PP;

use Facebook\GraphNodes\GraphUser;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use PP\User\EmailNotFoundException;
use PP\User\IncorrectPasswordException;
use PP\User\UserFacebookAuthenticator;
use PP\User\UserPasswordAuthenticator;
use PP\User\UserEntry;

class Authenticator implements IAuthenticator {
	/**
	 * @var UserPasswordAuthenticator
	 */
	private $authenticator;

	/**
	 * @var UserFacebookAuthenticator
	 */
	private $fbAuthenticator;

	public function __construct(UserPasswordAuthenticator $authenticator, UserFacebookAuthenticator $fbAuthenticator) {
		$this->authenticator = $authenticator;
		$this->fbAuthenticator = $fbAuthenticator;
	}

	/**
	 * Performs an authentication.
	 * @param array $credentials
	 * @return Identity
	 * @throws AuthenticationException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function authenticate(array $credentials) : Identity {
		if (isset($credentials[0]) && $credentials[0] instanceof GraphUser) {
			$user = $this->fbAuthenticator->authenticate($credentials[0]);
		} else {
			list($email, $password) = $credentials;
			try {
				$user = $this->authenticator->authenticate($email, $password);
			} catch (EmailNotFoundException $e) {
				throw new AuthenticationException($e->getMessage(), self::IDENTITY_NOT_FOUND, $e);
			} catch (IncorrectPasswordException $e) {
				throw new AuthenticationException($e->getMessage(), self::INVALID_CREDENTIAL, $e);
			}
		}
		return $this->createIdentity($user);
	}

	/**
	 * @param UserEntry $user
	 * @return Identity
	 */
	private function createIdentity(UserEntry $user) : Identity {
		return new Identity($user->getId(), [$user->getRole()], ["username" => $user->getName()]);
	}
}
