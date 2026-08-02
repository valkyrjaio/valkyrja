<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Authenticator\Contract;

use Valkyrja\Auth\Data\Attempt\Contract\AuthenticationAttemptContract;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;
use Valkyrja\Auth\Entity\Contract\UserContract;

/**
 * @template U of UserContract
 */
interface AuthenticatorContract
{
    /**
     * Determine if a user is authenticated.
     */
    public function isAuthenticated(): bool;

    /**
     * Get the current authenticated user.
     */
    public function getAuthenticated(): UserContract;

    /**
     * Get the current impersonated user.
     */
    public function getImpersonated(): UserContract;

    /**
     * Get the authenticated users.
     */
    public function getAuthenticatedUsers(): AuthenticatedUsersContract;

    /**
     * Set the authenticated users.
     *
     * @param AuthenticatedUsersContract $authenticatedUsers The authenticated users
     */
    public function setAuthenticatedUsers(AuthenticatedUsersContract $authenticatedUsers): static;

    /**
     * Process an authentication attempt.
     *
     * @param AuthenticationAttemptContract $attempt The authentication attempt
     */
    public function authenticate(AuthenticationAttemptContract $attempt): UserContract;

    /**
     * Unauthenticate a user by their id.
     *
     * @param non-empty-string|int $id The user's id
     */
    public function unauthenticate(string|int $id): static;
}
