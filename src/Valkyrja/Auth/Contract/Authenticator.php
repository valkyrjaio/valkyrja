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

use Valkyrja\Auth\Data\Attempt\Contract\AuthenticationAttempt;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Entity\Contract\User;

/**
 * Interface Authenticator.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 */
interface Authenticator
{
    /**
     * Determine if a user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * Get the current authenticated user if one exists.
     *
     * @return U|null
     */
    public function getAuthenticated(): User|null;

    /**
     * Get the current impersonated user if one exists.
     *
     * @return U|null
     */
    public function getImpersonated(): User|null;

    /**
     * Get the authenticated users.
     *
     * @return AuthenticatedUsers
     */
    public function getAuthenticatedUsers(): AuthenticatedUsers;

    /**
     * Set the authenticated users.
     *
     * @param AuthenticatedUsers $authenticatedUsers The authenticated users
     *
     * @return static
     */
    public function setAuthenticatedUsers(AuthenticatedUsers $authenticatedUsers): static;

    /**
     * Process an authentication attempt.
     *
     * @param AuthenticationAttempt $attempt The authentication attempt
     *
     * @return User
     */
    public function authenticate(AuthenticationAttempt $attempt): User;

    /**
     * Unauthenticate a user by their id.
     *
     * @param non-empty-string|int $id The user's id
     *
     * @return static
     */
    public function unauthenticate(string|int $id): static;
}
