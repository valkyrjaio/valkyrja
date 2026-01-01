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

namespace Valkyrja\Auth\Data\Contract;

/**
 * Interface AuthenticatedUsersContract.
 */
interface AuthenticatedUsersContract
{
    /**
     * Determine whether there is a current user in the collection.
     *
     * @return bool
     */
    public function hasCurrent(): bool;

    /**
     * Get the current user.
     *
     * @return non-empty-string|int|null
     */
    public function getCurrent(): string|int|null;

    /**
     * Set the current user.
     *
     * @param non-empty-string|int $id The user
     *
     * @return static
     */
    public function setCurrent(string|int $id): static;

    /**
     * Determine whether there is an impersonated user in the collection.
     *
     * @return bool
     */
    public function isImpersonating(): bool;

    /**
     * Get the impersonated user.
     *
     * @return non-empty-string|int|null
     */
    public function getImpersonated(): string|int|null;

    /**
     * Set a user to impersonate.
     *
     * @param non-empty-string|int $id The user
     *
     * @return static
     */
    public function setImpersonated(string|int $id): static;

    /**
     * Check if a user is authenticated.
     *
     * @param non-empty-string|int $id The user to check
     *
     * @return bool
     */
    public function isUserAuthenticated(string|int $id): bool;

    /**
     * Add a user to the collection.
     *
     * @param non-empty-string|int $id The user
     *
     * @return static
     */
    public function add(string|int $id): static;

    /**
     * Remove a user from the collection.
     *
     * @param non-empty-string|int $id The id of the user to remove
     *
     * @return static
     */
    public function remove(string|int $id): static;

    /**
     * Get all the users in the collection.
     *
     * @return array<int|non-empty-string, non-empty-string|int>
     */
    public function all(): array;
}
