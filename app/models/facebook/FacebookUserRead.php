<?php

namespace PP\Facebook;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphUser;
use Nette\Database\Context;
use Nette\Security\AuthenticationException;
use Nette\Utils\AssertionException;
use PP\User\EmailNotFoundException;
use PP\User\UserDatabaseDef;
use PP\User\UserEntry;
use PP\User\UserRead;

/**
 * @author Andrej Souček
 */
class FacebookUserRead {

	/**
	 * @var Facebook
	 */
	private $fb;

	public function __construct(Facebook $fb) {
		$this->fb = $fb;
	}

	/**
	 * @return GraphUser
	 * @throws AuthenticationException
	 */
	public function fetch() : GraphUser {
		try {
			$response = $this->fb->get('/me?fields=email,first_name,id', $this->fb->getRedirectLoginHelper()->getAccessToken());
			return $response->getGraphUser();
		} catch (FacebookSDKException $e) {
			throw new AuthenticationException("Error while FB authentication.");
		}
	}
}
