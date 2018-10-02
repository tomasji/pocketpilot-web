<?php

namespace PP\User;

use Facebook\Facebook;
use Nette\Utils\Validators;

/**
 * @author Andrej Souček
 */
class FacebookLinkBuilder {

	/**
	 * @var Facebook
	 */
	private $fb;

	public function __construct(Facebook $fb) {
		$this->fb = $fb;
	}

	/**
	 * @param string $redirectUrl
	 * @return string
	 * @throws \Nette\Utils\AssertionException
	 */
	public function generate(string $redirectUrl) {
		Validators::assert($redirectUrl, 'url');
		return $this->fb->getRedirectLoginHelper()->getLoginUrl($redirectUrl, ['email']);
	}
}
