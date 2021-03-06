<?php

declare(strict_types=1);

namespace PP;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;
use Nette\Security\Identity;
use Nette\SmartObject;
use PP\User\FacebookAuthenticator;
use PP\User\FacebookCredentials;
use PP\User\PasswordCredentials;
use PP\User\PasswordAuthenticator;
use PP\User\TokenAuthenticator;
use PP\User\TokenCredentials;
use PP\User\UserEntry;

/**
 * @author Andrej Souček
 */
class Authenticator implements IAuthenticator
{
    use SmartObject;

    /**
     * @var PasswordAuthenticator
     */
    private $pwAuthenticator;

    /**
     * @var FacebookAuthenticator
     */
    private $fbAuthenticator;

    /**
     * @var TokenAuthenticator
     */
    private $tokenAuthenticator;

    public function __construct(
        PasswordAuthenticator $pwAuthenticator,
        FacebookAuthenticator $fbAuthenticator,
        TokenAuthenticator $tokenAuthenticator
    ) {
        $this->pwAuthenticator = $pwAuthenticator;
        $this->fbAuthenticator = $fbAuthenticator;
        $this->tokenAuthenticator = $tokenAuthenticator;
    }

    /**
     * Performs an authentication.
     * @param array $credentials
     * @return IIdentity
     * @throws AuthenticationException
     * @throws \Nette\Utils\AssertionException
     */
    public function authenticate(array $credentials): IIdentity
    {
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
                case $credentials instanceof TokenCredentials:
                    $user = $this->tokenAuthenticator->authenticate($credentials);
                    break;
                default:
                    throw new \UnexpectedValueException('Only PasswordCredentials and FacebookCredentials allowed.');
            }
            return $this->createIdentity($user);
        } catch (IncorrectCredentialsException $e) {
            throw new AuthenticationException($e->getMessage(), self::FAILURE, $e);
        }
    }

    /**
     * @param UserEntry $user
     * @return Identity
     */
    private function createIdentity(UserEntry $user): Identity
    {
        return new Identity(
            $user->getId(),
            [$user->getRole()],
            ["name" => $user->getName(), "username" => $user->getName(), "email" => $user->getEmail()]
        );
    }
}
