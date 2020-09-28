<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Auth\Authenticators;

use Exception;
use Valkyrja\Auth\Authenticator as Contract;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\User;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\ORM\ORM;
use Valkyrja\Support\Type\Str;

use function password_hash;
use function password_verify;

use const PASSWORD_DEFAULT;

/**
 * Class Authenticator.
 *
 * @author Melech Mizrachi
 */
class Authenticator implements Contract
{
    /**
     * The Crypt.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * The ORM.
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * Authenticator constructor.
     *
     * @param Crypt $crypt
     * @param ORM   $orm
     */
    public function __construct(Crypt $crypt, ORM $orm)
    {
        $this->crypt = $crypt;
        $this->orm   = $orm;
    }

    /**
     * Attempt to authenticate a user.
     *
     * @param User $user
     *
     * @return bool
     */
    public function authenticate(User $user): bool
    {
        $repository  = $this->orm->getRepositoryFromClass($user);
        $loginFields = $user::getLoginFields();
        $find        = $repository->find();

        // Iterate through the login fields
        foreach ($loginFields as $loginField) {
            // Set a where clause for each field
            $find->where($loginField, null, $user->__get($loginField));
        }

        /** @var User $dbUser */
        $dbUser = $find->getOneOrNull();

        // If there is a user and the password matches
        if ($dbUser && $this->isPassword($dbUser, $user->__get($user::getPasswordField()))) {
            // Update the user model with all the properties from the database
            $user->__setProperties($dbUser->__storable());

            return true;
        }

        return false;
    }

    /**
     * Get the user token.
     *
     * @param User $user
     *
     * @throws CryptException
     *
     * @return string
     */
    public function getToken(User $user): string
    {
        // Get the password field
        $passwordField = $user::getPasswordField();

        $user->__expose($passwordField);

        $token = $this->crypt->encryptObject($user);

        $user->__unexpose($passwordField);

        return $token;
    }

    /**
     * Determine if a token is valid.
     *
     * @param string $token
     *
     * @return bool
     */
    public function isValidToken(string $token): bool
    {
        return $this->crypt->isValidEncryptedMessage($token);
    }

    /**
     * Get a user from token.
     *
     * @param string $user
     * @param string $token
     *
     * @return User|null
     */
    public function getUserFromToken(string $user, string $token): ?User
    {
        try {
            $userProperties = $this->crypt->decryptObject($token);
            /** @var User $user */
            /** @var User $userModel */
            $userModel = $user::fromArray((array) $userProperties);
        } catch (Exception $exception) {
            return null;
        }

        return $userModel;
    }

    /**
     * Refresh a user from the data store.
     *
     * @param User $user
     *
     * @return User
     */
    public function getFreshUser(User $user): User
    {
        /** @var User $freshUser */
        $freshUser = $this->orm->getRepositoryFromClass($user)->findOne($user->__get($user::getIdField()))->getOneOrFail();

        return $freshUser;
    }

    /**
     * Determine if a password verifies against the user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return bool
     */
    public function isPassword(User $user, string $password): bool
    {
        return password_verify($password, $user->__get($user::getPasswordField()));
    }

    /**
     * Update a user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return void
     */
    public function updatePassword(User $user, string $password): void
    {
        $user->__set($user::getResetTokenField(), null);
        $user->__set($user::getPasswordField(), $this->hashPassword($password));

        $this->saveUser($user);
    }

    /**
     * Reset a user's password.
     *
     * @param User $user
     *
     * @throws Exception
     *
     * @return void
     */
    public function resetPassword(User $user): void
    {
        $user->__set($user::getResetTokenField(), Str::random());

        $this->saveUser($user);
    }

    /**
     * Lock a user.
     *
     * @param LockableUser $user
     *
     * @return void
     */
    public function lock(LockableUser $user): void
    {
        $this->lockUnlock($user, true);
    }

    /**
     * Unlock a user.
     *
     * @param LockableUser $user
     *
     * @return void
     */
    public function unlock(LockableUser $user): void
    {
        $this->lockUnlock($user, false);
    }

    /**
     * Hash a plain password.
     *
     * @param string $password
     *
     * @return string
     */
    protected function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Lock or unlock a user.
     *
     * @param LockableUser $user
     * @param bool         $lock
     *
     * @return void
     */
    protected function lockUnlock(LockableUser $user, bool $lock): void
    {
        $user->__set($user::getIsLockedField(), $lock);

        $this->saveUser($user);
    }

    /**
     * Save a user.
     *
     * @param User $user
     *
     * @return void
     */
    protected function saveUser(User $user): void
    {
        // Get the ORM repository
        $repository = $this->orm->getRepositoryFromClass($user);

        $repository->save($user, false);
        $repository->persist();
    }
}
