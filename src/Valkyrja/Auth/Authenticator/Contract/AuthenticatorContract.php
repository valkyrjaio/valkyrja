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

namespace Valkyrja\Auth\Authenticator\Contract;

use Valkyrja\Auth\Data\Attempt\Contract\AuthenticationAttemptContract;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Entity\Contract\UserContract;

/**
 * Interface AuthenticatorContract.
 *
 * @author Melech Mizrachi
 *
 * @template U of UserContract
 */
interface AuthenticatorContract
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
     * @return UserContract|null
     */
    public function getAuthenticated(): UserContract|null;

    /**
     * Get the current impersonated user if one exists.
     *
     * @return UserContract|null
     */
    public function getImpersonated(): UserContract|null;

    /**
     * Get the authenticated users.
     *
     * @return AuthenticatedUsersContract
     */
    public function getAuthenticatedUsers(): AuthenticatedUsersContract;

    /**
     * Set the authenticated users.
     *
     * @param AuthenticatedUsersContract $authenticatedUsers The authenticated users
     *
     * @return static
     */
    public function setAuthenticatedUsers(AuthenticatedUsersContract $authenticatedUsers): static;

    /**
     * Process an authentication attempt.
     *
     * @param AuthenticationAttemptContract $attempt The authentication attempt
     *
     * @return UserContract
     */
    public function authenticate(AuthenticationAttemptContract $attempt): UserContract;

    /**
     * Unauthenticate a user by their id.
     *
     * @param non-empty-string|int $id The user's id
     *
     * @return static
     */
    public function unauthenticate(string|int $id): static;
}
