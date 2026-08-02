<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Data\Contract;

interface AuthenticatedUsersContract
{
    /**
     * Determine whether there is a current user in the collection.
     */
    public function hasCurrent(): bool;

    /**
     * Get the current user.
     *
     * @return non-empty-string|int
     */
    public function getCurrent(): string|int;

    /**
     * Set the current user.
     *
     * @param non-empty-string|int $id The user
     */
    public function setCurrent(string|int $id): static;

    /**
     * Determine whether there is an impersonated user in the collection.
     */
    public function isImpersonating(): bool;

    /**
     * Get the impersonated user.
     *
     * @return non-empty-string|int
     */
    public function getImpersonated(): string|int;

    /**
     * Set a user to impersonate.
     *
     * @param non-empty-string|int $id The user
     */
    public function setImpersonated(string|int $id): static;

    /**
     * Check if a user is authenticated.
     *
     * @param non-empty-string|int $id The user to check
     */
    public function isUserAuthenticated(string|int $id): bool;

    /**
     * Add a user to the collection.
     *
     * @param non-empty-string|int $id The user
     */
    public function add(string|int $id): static;

    /**
     * Remove a user from the collection.
     *
     * @param non-empty-string|int $id The id of the user to remove
     */
    public function remove(string|int $id): static;

    /**
     * Get all the users in the collection.
     *
     * @return array<int|non-empty-string, non-empty-string|int>
     */
    public function all(): array;
}
