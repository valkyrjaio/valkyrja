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
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\ORM\ORM;
use Valkyrja\Session\Session;

/**
 * Interface Auth.
 *
 * @author Melech Mizrachi
 */
interface Auth
{
    /**
     * Set the config.
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Set the config.
     *
     * @param array $config
     *
     * @return static
     */
    public function setConfig(array $config): self;

    /**
     * Get the Crypt.
     *
     * @return Crypt
     */
    public function getCrypt(): Crypt;

    /**
     * Set the Crypt.
     *
     * @param Crypt $crypt
     *
     * @return static
     */
    public function setCrypt(Crypt $crypt): self;

    /**
     * Get the ORM.
     *
     * @return ORM
     */
    public function getOrm(): ORM;

    /**
     * Set the ORM.
     *
     * @param ORM $orm The orm
     *
     * @return static
     */
    public function setOrm(ORM $orm): self;

    /**
     * Get the session manager.
     *
     * @return Session
     */
    public function getSession(): Session;

    /**
     * Set the session manager.
     *
     * @param Session $session The session
     *
     * @return static
     */
    public function setSession(Session $session): self;

    /**
     * Get an adapter by name.
     *
     * @param string|null $name The adapter name
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter;

    /**
     * Get a repository by user entity name.
     *
     * @param string|null $user The user
     *
     * @return Repository
     */
    public function getRepository(string $user = null): Repository;

    /**
     * Get the logged in user.
     *
     * @return User
     */
    public function getUser(): User;

    /**
     * Set the logged in user.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function setUser(User $user): self;

    /**
     * Get the user stored in session.
     *
     * @return User
     */
    public function getUserFromSession(): User;

    /**
     * Log a user in.
     *
     * @param User $user The user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function login(User $user): self;

    /**
     * Ensure a token is still valid.
     *
     * @param string $token The token
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function ensureTokenValidity(string $token): self;

    /**
     * Ensure a tokenized user is still valid.
     *
     * @param User $user The tokenized user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function ensureUserValidity(User $user): self;

    /**
     * Log a user in via token.
     *
     * @param string $token The token
     * @param bool   $store [optional] Whether to store the token in session
     *
     * @throws CryptException
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginWithToken(string $token, bool $store = false): self;

    /**
     * Log in with a specific user.
     *
     * @param User $user  The user
     * @param bool $store [optional] Whether to store the user in session
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginWithUser(User $user, bool $store = false): self;

    /**
     * Log a user in via tokenized session.
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function loginFromTokenizedSession(): self;

    /**
     * Log a user in via a user session.
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
     * Get the user token from session.
     *
     * @return string
     */
    public function getTokenFromSession(): string;

    /**
     * Store the user token in session.
     *
     * @param string|null $token [optional] The token to store
     *
     * @throws CryptException
     *
     * @return static
     */
    public function storeToken(string $token = null): self;

    /**
     * Store the user in session.
     *
     * @param User|null $user [optional] The user to store
     *
     * @return static
     */
    public function storeUser(User $user = null): self;

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
     * @param User $user The user
     *
     * @throws InvalidRegistrationException
     *
     * @return static
     */
    public function register(User $user): self;

    /**
     * Forgot password.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function forgot(User $user): self;

    /**
     * Reset a user's password.
     *
     * @param User   $user     The user
     * @param string $password The password
     *
     * @return static
     */
    public function reset(User $user, string $password): self;

    /**
     * Lock a user.
     *
     * @param LockableUser $user The user
     *
     * @return static
     */
    public function lock(LockableUser $user): self;

    /**
     * Unlock a user.
     *
     * @param LockableUser $user The user
     *
     * @return static
     */
    public function unlock(LockableUser $user): self;

    /**
     * Confirm the current user's password.
     *
     * @param string $password The password
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
