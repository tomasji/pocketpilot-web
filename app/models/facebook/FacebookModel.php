<?php

namespace PP\Facebook;

use Facebook\GraphNodes\GraphUser;

/**
 * @author Andrej Souček
 */
class FacebookModel {

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
	 * @return \Facebook\GraphNodes\GraphUser
	 * @throws \Facebook\Exceptions\FacebookSDKException
	 */
	public function getFbUser() : GraphUser {
		return $this->read->fetch();
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
