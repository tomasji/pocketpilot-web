<?php

namespace PP;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use PP\Facebook\FacebookAuthenticator;
use PP\Facebook\FacebookCredentials;
use PP\Facebook\IncorrectUidException;
use PP\User\EmailNotFoundException;
use PP\User\IncorrectPasswordException;
use PP\User\PasswordCredentials;
use PP\User\PasswordAuthenticator;
use PP\User\UserEntry;

class Authenticator implements IAuthenticator {
	/**
	 * @var PasswordAuthenticator
	 */
	private $pwAuthenticator;

	/**
	 * @var FacebookAuthenticator
	 */
	private $fbAuthenticator;

	public function __construct(PasswordAuthenticator $pwAuthenticator, FacebookAuthenticator $fbAuthenticator) {
		$this->pwAuthenticator = $pwAuthenticator;
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
		if (count($credentials)) {
			$credentials = $credentials[0];
		} else {
			throw new \InvalidArgumentException('$credentials array must contain exactly one value.');
		}
		try {
			switch (true) {
				case $credentials instanceof PasswordCredentials:
					$user = $this->pwAuthenticator->authenticate($credentials);
					break;
				case $credentials instanceof FacebookCredentials:
					$user = $this->fbAuthenticator->authenticate($credentials);
					break;
				default:
					throw new \UnexpectedValueException('Only PasswordCredentials and FacebookCredentials allowed.');
			}
			return $this->createIdentity($user);
		} catch (EmailNotFoundException $e) {
			throw new AuthenticationException($e->getMessage(), self::IDENTITY_NOT_FOUND, $e);
		} catch (IncorrectPasswordException $e) {
			throw new AuthenticationException($e->getMessage(), self::INVALID_CREDENTIAL, $e);
		} catch (IncorrectUidException $e) {
			throw new AuthenticationException($e->getMessage(), self::INVALID_CREDENTIAL, $e);
		}
	}

	/**
	 * @param UserEntry $user
	 * @return Identity
	 */
	private function createIdentity(UserEntry $user) : Identity {
		return new Identity($user->getId(), [$user->getRole()], ["username" => $user->getName()]);
	}
}
