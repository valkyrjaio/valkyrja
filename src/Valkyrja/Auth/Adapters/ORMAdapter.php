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

namespace Valkyrja\Auth\Adapters;

use Exception;
use Valkyrja\Auth\Adapter as Contract;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Auth\LockableUser;
use Valkyrja\Auth\User;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\Repository;
use Valkyrja\Support\Type\Str;

use const PASSWORD_DEFAULT;

/**
 * Class Adapter.
 *
 * @author Melech Mizrachi
 */
class ORMAdapter implements Contract
{
    /**
     * The crypt.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * The orm
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * Adapter constructor.
     *
     * @param Crypt $crypt The crypt
     * @param ORM   $orm   The orm
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
        $dbUser = $this->getUserViaLoginFields($user);

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

        $token = $this->crypt->encryptArray($user->__tokenized());

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
     * Get a user via login fields.
     *
     * @param User $user
     *
     * @return User|null
     */
    public function getUserViaLoginFields(User $user): ?User
    {
        $loginFields = $user::getLoginFields();
        $find        = $this->getUserRepository($user)->find();

        // Iterate through the login fields
        foreach ($loginFields as $loginField) {
            // Set a where clause for each field
            $find->where($loginField, null, $user->__get($loginField));
        }

        /** @var User $dbUser */
        $dbUser = $find->getOneOrNull();

        return $dbUser;
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
            $userProperties = $this->crypt->decryptArray($token);
            /** @var User $user */
            /** @var User $userModel */
            $userModel = $user::fromArray($userProperties);
        } catch (Exception $exception) {
            return null;
        }

        return $userModel;
    }

    /**
     * Get a user from a reset token.
     *
     * @param string $user
     * @param string $resetToken
     *
     * @return User|null
     */
    public function getUserFromResetToken(string $user, string $resetToken): ?User
    {
        /** @var User|string $user */
        $resetTokenField = $user::getResetTokenField();
        /** @var User $dbUser */
        $dbUser = $this->getUserRepository($user)
            ->find()
            ->where($resetTokenField, null, $user->__get($resetTokenField))
            ->getOneOrNull();

        return $dbUser;
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
        $freshUser = $this->getUserRepository($user)
            ->findOne($user->__get($user::getIdField()))
            ->getOneOrFail();

        return $freshUser;
    }

    /**
     * Save a user.
     *
     * @param User $user
     *
     * @return void
     */
    public function saveUser(User $user): void
    {
        // Get the ORM repository
        $repository = $this->getUserRepository($user);

        $repository->save($user, false);
        $repository->persist();
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
        $resetTokenField = $user::getResetTokenField();

        $user->__set($resetTokenField, null);
        $user->__set($user::getPasswordField(), $this->hashPassword($password));

        $this->saveUser($user);
    }

    /**
     * Generate a reset token for a user.
     *
     * @param User $user
     *
     * @throws Exception
     *
     * @return void
     */
    public function generateResetToken(User $user): void
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
     * Register a new user.
     *
     * @param User $user
     *
     * @throws InvalidRegistrationException
     *
     * @return bool
     */
    public function register(User $user): bool
    {
        $repository    = $this->getUserRepository($user);
        $passwordField = $user::getPasswordField();

        try {
            $user->__set($passwordField, $this->hashPassword($user->__get($passwordField)));

            $this->orm->ensureTransaction();
            $repository->create($user, true);
            $repository->persist();

            return true;
        } catch (Exception $exception) {
            throw new InvalidRegistrationException($exception->getMessage());
        }
    }

    /**
     * Determine if a user is registered.
     *
     * @param User $user
     *
     * @return bool
     */
    public function isRegistered(User $user): bool
    {
        $loginFields = $user::getLoginFields();
        $find        = $this->getUserRepository($user)->find();

        // Iterate through the login fields
        foreach ($loginFields as $loginField) {
            // Find a user with any of the login fields
            $find->orWhere($loginField, null, $user->__get($loginField));
        }

        // If a user is found a user is registered with one of the login fields
        if ($find->getOneOrNull()) {
            return true;
        }

        return false;
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
     * Get an ORM repository for the user.
     *
     * @param User $user The user
     *
     * @return Repository
     */
    protected function getUserRepository(User $user): Repository
    {
        return $this->orm->getRepositoryFromClass($user);
    }
}
