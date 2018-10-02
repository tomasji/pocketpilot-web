<?php

namespace PP;

use PP\User\FacebookCredentials;
use PP\User\FacebookLinkBuilder;
use PP\User\FacebookUserRead;

/**
 * @author Andrej Souček
 */
class HomepageModel {

	/**
	 * @var FacebookUserRead
	 */
	private $read;

	/**
	 * @var FacebookLinkBuilder
	 */
	private $builder;

	public function __construct(FacebookUserRead $read, FacebookLinkBuilder $builder) {
		$this->read = $read;
		$this->builder = $builder;
	}

	/**
	 * @return FacebookCredentials
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function getFacebookCredentials() : FacebookCredentials {
		$graphUser = $this->read->fetch();
		return new FacebookCredentials($graphUser->getEmail(), $graphUser->getId(), $graphUser->getFirstName());
	}

	/**
	 * @param string $redirectUrl
	 * @return string
	 * @throws \Nette\Utils\AssertionException
	 */
	public function generateLoginUrl(string $redirectUrl) {
		return $this->builder->generate($redirectUrl);
	}
}
