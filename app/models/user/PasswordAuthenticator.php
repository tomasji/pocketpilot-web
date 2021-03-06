<?php

declare(strict_types=1);

namespace PP\User;

use Nette\Database\Context;
use Nette\Security\Passwords;
use Nette\SmartObject;
use PP\IncorrectCredentialsException;

class PasswordAuthenticator
{
    use SmartObject;

    /**
     * @var Context
     */
    private $database;

    /**
     * @var UserRead
     */
    private $read;

    /**
     * @var Passwords
     */
    private $passwords;

    public function __construct(Context $database, UserRead $read, Passwords $passwords)
    {
        $this->database = $database;
        $this->read = $read;
        $this->passwords = $passwords;
    }

    /**
     * Performs an authentication.
     * @param PasswordCredentials $credentials
     * @return UserEntry
     * @throws IncorrectCredentialsException
     * @throws \Nette\Utils\AssertionException
     */
    public function authenticate(PasswordCredentials $credentials): UserEntry
    {
        $entry = $this->read->fetchBy($credentials->getEmail());
        $row = $this->database->table(UserDatabaseDef::TABLE_NAME)
            ->where(UserDatabaseDef::COLUMN_ID, $entry->getId())->fetch();
        $hash = $row[UserDatabaseDef::COLUMN_PASSWORD_HASH];
        if (!$this->passwords->verify($credentials->getAuthString(), $hash)) {
            throw new IncorrectCredentialsException('Entered password is incorrect.');
        } elseif ($this->passwords->needsRehash($hash)) {
            $this->database->table(UserDatabaseDef::TABLE_NAME)
                ->where(UserDatabaseDef::COLUMN_ID, $entry->getId())
                ->update([
                    UserDatabaseDef::COLUMN_PASSWORD_HASH => $this->passwords->hash($credentials->getAuthString())
                ]);
        }
        return $entry;
    }
}
