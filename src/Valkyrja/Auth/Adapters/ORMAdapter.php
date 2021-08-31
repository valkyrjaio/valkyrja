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
use Valkyrja\Auth\User;
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
     * The orm
     *
     * @var ORM
     */
    protected ORM $orm;

    /**
     * Adapter constructor.
     *
     * @param ORM $orm The orm
     */
    public function __construct(ORM $orm)
    {
        $this->orm = $orm;
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
        $dbUser = $this->retrieve($user);

        // If there is a user and the password matches
        if ($dbUser && $this->verifyPassword($dbUser, $user->__get($user::getPasswordField()))) {
            // Update the user model with all the properties from the database
            $user->updateProperties($dbUser->asStorableArray());

            return true;
        }

        return false;
    }

    /**
     * Get a user via login fields.
     *
     * @param User $user
     *
     * @return User|null
     */
    public function retrieve(User $user): ?User
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
     * Get a user from a reset token.
     *
     * @param User   $user
     * @param string $resetToken
     *
     * @return User|null
     */
    public function retrieveByResetToken(User $user, string $resetToken): ?User
    {
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
    public function retrieveById(User $user): User
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
    public function save(User $user): void
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
    public function verifyPassword(User $user, string $password): bool
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

        $this->save($user);
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
    public function updateResetToken(User $user): void
    {
        $user->__set($user::getResetTokenField(), Str::random());

        $this->save($user);
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
    public function create(User $user): bool
    {
        $repository    = $this->getUserRepository($user);
        $passwordField = $user::getPasswordField();

        try {
            if ($this->retrieve($user)) {
                return false;
            }

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
