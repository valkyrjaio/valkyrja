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

namespace Valkyrja\Auth\Contract;

use Valkyrja\Auth\Adapter\Contract\Adapter;
use Valkyrja\Auth\Config;
use Valkyrja\Auth\Entity\Contract\LockableUser;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidAuthenticationException;
use Valkyrja\Auth\Exception\InvalidPasswordConfirmationException;
use Valkyrja\Auth\Exception\InvalidRegistrationException;
use Valkyrja\Auth\Factory\Contract\Factory;
use Valkyrja\Auth\Gate\Contract\Gate;
use Valkyrja\Auth\Model\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Policy\Contract\Policy;
use Valkyrja\Auth\Repository\Contract\Repository;
use Valkyrja\Crypt\Exception\CryptException;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;

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
     * @return Config|array
     */
    public function getConfig(): Config|array;

    /**
     * Get an adapter by name.
     *
     * @param class-string<Adapter>|null $name [optional] The adapter name
     *
     * @return Adapter
     */
    public function getAdapter(string|null $name = null): Adapter;

    /**
     * Get a repository by user entity name.
     *
     * @param class-string<User>|null    $user    [optional] The user
     * @param class-string<Adapter>|null $adapter [optional] The adapter
     *
     * @return Repository
     */
    public function getRepository(string|null $user = null, string|null $adapter = null): Repository;

    /**
     * Get a gate by name.
     *
     * @param class-string<Gate>|null    $name    [optional] The name
     * @param class-string<User>|null    $user    [optional] The user
     * @param class-string<Adapter>|null $adapter [optional] The adapter
     *
     * @return Gate
     */
    public function getGate(string|null $name = null, string|null $user = null, string|null $adapter = null): Gate;

    /**
     * Get a policy by name.
     *
     * @param class-string<Policy>|null  $name    [optional] The policy name
     * @param class-string<User>|null    $user    [optional] The user
     * @param class-string<Adapter>|null $adapter [optional] The adapter
     *
     * @return Policy
     */
    public function getPolicy(string|null $name = null, string|null $user = null, string|null $adapter = null): Policy;

    /**
     * Get the factory.
     *
     * @return Factory
     */
    public function getFactory(): Factory;

    /**
     * Get a request with auth token header.
     *
     * @param ServerRequest              $request The request
     * @param class-string<User>|null    $user    [optional] The user
     * @param class-string<Adapter>|null $adapter [optional] The adapter
     *
     * @throws CryptException
     *
     * @return ServerRequest
     */
    public function requestWithAuthToken(
        ServerRequest $request,
        string|null $user = null,
        string|null $adapter = null
    ): ServerRequest;

    /**
     * Get a request without auth token header.
     *
     * @param ServerRequest $request The request
     *
     * @return ServerRequest
     */
    public function requestWithoutAuthToken(ServerRequest $request): ServerRequest;

    /**
     * Determine if a user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * Get the authenticated user.
     *
     * @return User
     */
    public function getUser(): User;

    /**
     * Set the authenticated user.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function setUser(User $user): static;

    /**
     * Get the authenticated users.
     *
     * @return AuthenticatedUsers
     */
    public function getUsers(): AuthenticatedUsers;

    /**
     * Set the authenticated users.
     *
     * @param AuthenticatedUsers $users The users
     *
     * @return static
     */
    public function setUsers(AuthenticatedUsers $users): static;

    /**
     * Authenticate a user with credentials.
     *
     * @param User $user The user
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function authenticate(User $user): static;

    /**
     * Authenticate a user from an active session.
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function authenticateFromSession(): static;

    /**
     * Authenticate a user from a request.
     *
     * @param ServerRequest $request The request
     *
     * @throws InvalidAuthenticationException
     *
     * @return static
     */
    public function authenticateFromRequest(ServerRequest $request): static;

    /**
     * Un-authenticate any active users.
     *
     * @param User|null $user [optional] The user to un-authenticate
     *
     * @return static
     */
    public function unAuthenticate(User|null $user = null): static;

    /**
     * Set the authenticated user in the session.
     *
     * @return static
     */
    public function setSession(): static;

    /**
     * Unset the authenticated user from the session.
     *
     * @return static
     */
    public function unsetSession(): static;

    /**
     * Register a new user.
     *
     * @param User $user The user
     *
     * @throws InvalidRegistrationException
     *
     * @return static
     */
    public function register(User $user): static;

    /**
     * Forgot password.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function forgot(User $user): static;

    /**
     * Reset a user's password.
     *
     * @param string $resetToken The reset token
     * @param string $password   The password
     *
     * @return static
     */
    public function reset(string $resetToken, string $password): static;

    /**
     * Lock a user.
     *
     * @param LockableUser $user The user
     *
     * @return static
     */
    public function lock(LockableUser $user): static;

    /**
     * Unlock a user.
     *
     * @param LockableUser $user The user
     *
     * @return static
     */
    public function unlock(LockableUser $user): static;

    /**
     * Confirm the current user's password.
     *
     * @param string $password The password
     *
     * @throws InvalidPasswordConfirmationException
     *
     * @return static
     */
    public function confirmPassword(string $password): static;

    /**
     * Determine if a re-authentication needs to occur.
     *
     * @return bool
     */
    public function isReAuthenticationRequired(): bool;
}
