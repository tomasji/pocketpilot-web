<?php

declare(strict_types=1);

namespace PP;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphUser;
use Nette\Security\AuthenticationException;
use Nette\SmartObject;
use Nette\Utils\Validators;

/**
 * @author Andrej Souček
 */
class FacebookService
{
    use SmartObject;

    /**
     * @var Facebook
     */
    private $fb;

    public function __construct(Facebook $fb)
    {
        $this->fb = $fb;
    }

    /**
     * @param string $redirectUrl
     * @return string
     * @throws \Nette\Utils\AssertionException
     */
    public function generateLoginUrl(string $redirectUrl): string
    {
        Validators::assert($redirectUrl, 'url');
        return $this->fb->getRedirectLoginHelper()->getLoginUrl($redirectUrl, ['email']);
    }


    /**
     * @return GraphUser
     * @throws AuthenticationException
     */
    public function fetchUser(): GraphUser
    {
        try {
            $response = $this->fb->get(
                '/me?fields=email,first_name,id',
                $this->fb->getRedirectLoginHelper()->getAccessToken()
            );
            return $response->getGraphUser();
        } catch (FacebookSDKException $e) {
            throw new AuthenticationException("Error while FB authentication.");
        }
    }
}
