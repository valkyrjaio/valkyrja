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

namespace Valkyrja\Auth\Model\Contract;

use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Type\Model\Contract\CastableModel;

/**
 * Interface AuthenticatedUsers.
 *
 * @author   Melech Mizrachi
 *
 * @template User of User
 */
interface AuthenticatedUsers extends CastableModel
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
     * @return User|null
     */
    public function getCurrent(): ?User;

    /**
     * Set the current user.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function setCurrent(User $user): static;

    /**
     * Determine whether there is an impersonated user in the collection.
     *
     * @return bool
     */
    public function isImpersonating(): bool;

    /**
     * Get the impersonated user.
     *
     * @return User|null
     */
    public function getImpersonated(): ?User;

    /**
     * Set a user to impersonate.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function setImpersonated(User $user): static;

    /**
     * Check if a user is authenticated.
     *
     * @param User $user The user to check
     *
     * @return bool
     */
    public function isAuthenticated(User $user): bool;

    /**
     * Add a user to the collection.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function add(User $user): static;

    /**
     * Remove a user from the collection.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function remove(User $user): static;

    /**
     * Get all the users in the collection.
     *
     * @return array<int|string, User>
     */
    public function all(): array;

    /**
     * Get all the users in the collection as a storable array.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array{currentId: string|int|null, users: array<string|int, array<string, mixed>>}
     */
    public function asStorableArray(string ...$properties): array;
}
