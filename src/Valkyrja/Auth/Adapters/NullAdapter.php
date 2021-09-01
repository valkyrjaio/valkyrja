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
use Valkyrja\Support\Type\Str;

use const PASSWORD_DEFAULT;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Contract
{
    protected array $config;

    /**
     * NullAdapter constructor.
     *
     * @param array $config The config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
        return true;
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
        return $user;
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
        return $user;
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
        return $user;
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
        return true;
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
}
