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

namespace Valkyrja\Auth;

use Exception;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Crypt\Exceptions\CryptException;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter
{
    /**
     * Attempt to authenticate a user.
     *
     * @param User $user
     *
     * @return bool
     */
    public function authenticate(User $user): bool;

    /**
     * Get the user token.
     *
     * @param User $user
     *
     * @throws CryptException
     *
     * @return string
     */
    public function getToken(User $user): string;

    /**
     * Determine if a token is valid.
     *
     * @param string $token
     *
     * @return bool
     */
    public function isValidToken(string $token): bool;

    /**
     * Get a user via login fields.
     *
     * @param User $user
     *
     * @return User|null
     */
    public function getUserViaLoginFields(User $user): ?User;

    /**
     * Get a user from token.
     *
     * @param string $user
     * @param string $token
     *
     * @return User|null
     */
    public function getUserFromToken(string $user, string $token): ?User;

    /**
     * Get a user from token.
     *
     * @param string $user
     * @param string $resetToken
     *
     * @return User|null
     */
    public function getUserFromResetToken(string $user, string $resetToken): ?User;

    /**
     * Refresh a user from the data store.
     *
     * @param User $user
     *
     * @return User
     */
    public function getFreshUser(User $user): User;

    /**
     * Save a user.
     *
     * @param User $user
     *
     * @return void
     */
    public function saveUser(User $user): void;

    /**
     * Determine if a password verifies against the user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return bool
     */
    public function isPassword(User $user, string $password): bool;

    /**
     * Update a user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @throws Exception
     *
     * @return void
     */
    public function updatePassword(User $user, string $password): void;

    /**
     * Generate a reset token for a user.
     *
     * @param User $user
     *
     * @throws Exception
     *
     * @return void
     */
    public function generateResetToken(User $user): void;

    /**
     * Lock a user.
     *
     * @param LockableUser $user
     *
     * @return void
     */
    public function lock(LockableUser $user): void;

    /**
     * Unlock a user.
     *
     * @param LockableUser $user
     *
     * @return void
     */
    public function unlock(LockableUser $user): void;

    /**
     * Register a new user.
     *
     * @param User $user
     *
     * @throws InvalidRegistrationException
     *
     * @return bool
     */
    public function register(User $user): bool;

    /**
     * Determine if a user is registered.
     *
     * @param User $user
     *
     * @return bool
     */
    public function isRegistered(User $user): bool;
}
