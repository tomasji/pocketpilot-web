<?php

namespace PP;

use PP\User\FacebookCredentials;
use PP\User\UserRegister;

/**
 * @author Andrej Souček
 */
class SignModel {

	/**
	 * @var UserRegister
	 */
	private $register;

	/**
	 * @var FacebookService
	 */
	private $fb;

	public function __construct(UserRegister $register, FacebookService $fb) {
		$this->register = $register;
		$this->fb = $fb;
	}

	/**
	 * @param string $username
	 * @param string $email
	 * @param string|null $fb_uid
	 * @param string|null $password
	 * @throws IncorrectCredentialsException
	 * @throws \Nette\Utils\AssertionException
	 */
	public function registerUser(string $username, string $email, string $fb_uid = null, string $password = null) : void {
		$this->register->process($username, $email, $fb_uid, $password);
	}

	/**
	 * @return FacebookCredentials
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function getFacebookCredentials() : FacebookCredentials {
		$graphUser = $this->fb->fetchUser();
		return new FacebookCredentials($graphUser->getEmail(), $graphUser->getId(), $graphUser->getFirstName());
	}

	/**
	 * @param string $redirectUrl
	 * @return string
	 * @throws \Nette\Utils\AssertionException
	 */
	public function generateLoginUrl(string $redirectUrl) {
		return $this->fb->generateLoginUrl($redirectUrl);
	}
}
