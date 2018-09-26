<?php

namespace PP\Facebook;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphUser;

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
	 * @throws FacebookSDKException
	 */
	public function fetch() : GraphUser {
		$response = $this->fb->get('/me?fields=email,first_name,id', $this->fb->getRedirectLoginHelper()->getAccessToken());
		return $response->getGraphUser();
	}
}
