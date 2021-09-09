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

use Valkyrja\Support\Model\Model;

/**
 * Interface AuthenticatedUsers.
 *
 * @author   Melech Mizrachi
 * @template T
 */
interface AuthenticatedUsers extends Model
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
     * @return User|T|null
     */
    public function getCurrent(): ?User;

    /**
     * Set the current user.
     *
     * @param User $user The user
     *
     * @return static
     */
    public function setCurrent(User $user): self;

    /**
     * Add a user to the collection.
     *
     * @param User|T $user The user
     *
     * @return static
     */
    public function add(User $user): self;

    /**
     * Remove a user from the collection.
     *
     * @param User|T $user The user
     *
     * @return static
     */
    public function remove(User $user): self;

    /**
     * Get all the users in the collection
     *
     * @return User[]|array<int|string, T>
     */
    public function all(): array;
}
