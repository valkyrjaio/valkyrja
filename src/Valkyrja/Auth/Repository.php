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

use Valkyrja\Auth\Exceptions\InvalidAuthenticationException;
use Valkyrja\Auth\Exceptions\InvalidPasswordConfirmationException;
use Valkyrja\Auth\Exceptions\InvalidRegistrationException;
use Valkyrja\Crypt\Exceptions\CryptException;

/**
 * Interface Repository.
 *
 * @author Melech Mizrachi
 */
interface Repository
{
    /**
     * Make a new repository.
     *
     * @param Auth   $auth
     * @param string $user
     *
     * @return static
     */
    public static function make(Auth $auth, string $user): self;

    /**
     * Get the logged in user.
     *
     * @return User
     */
    public function getUser(): User;

    /**
     * Log a user in.
     *
     * @param User $user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function login(User $user): self;

    /**
     * Log a user in via token.
     *
     * @param string $token
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginWithToken(string $token): self;

    /**
     * Log a user in via session.
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginFromSession(): self;

    /**
     * Get the user token.
     *
     * @throws CryptException
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Store the user token in session.
     *
     * @throws CryptException
     *
     * @return static
     */
    public function storeToken(): self;

    /**
     * Determine if a user is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool;

    /**
     * Log the current user out.
     *
     * @return static
     */
    public function logout(): self;

    /**
     * Register a new user.
     *
     * @param User $user
     *
     * @throws InvalidRegistrationException
     *
     * @return static
     */
    public function register(User $user): self;

    /**
     * Forgot password.
     *
     * @param User $user
     *
     * @return static
     */
    public function forgot(User $user): self;

    /**
     * Reset a user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return static
     */
    public function reset(User $user, string $password): self;

    /**
     * Lock a user.
     *
     * @param LockableUser $user
     *
     * @return static
     */
    public function lock(LockableUser $user): self;

    /**
     * Unlock a user.
     *
     * @param LockableUser $user
     *
     * @return static
     */
    public function unlock(LockableUser $user): self;

    /**
     * Confirm the current user's password.
     *
     * @param string $password
     *
     * @throws InvalidPasswordConfirmationException
     *
     * @return static
     */
    public function confirmPassword(string $password): self;

    /**
     * Store the confirmed password timestamp in session.
     *
     * @return static
     */
    public function storeConfirmedPassword(): self;
}
