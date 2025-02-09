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

namespace Valkyrja\Auth\Adapter\Contract;

use Exception;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidRegistrationException;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter
{
    /**
     * Attempt to authenticate a user.
     *
     * @param User $user
     *
     * @return bool
     */
    public function authenticate(User $user): bool;

    /**
     * Get a user via login fields.
     *
     * @param User $user
     *
     * @return User|null
     */
    public function retrieve(User $user): ?User;

    /**
     * Get a user from token.
     *
     * @param User   $user
     * @param string $resetToken
     *
     * @return User|null
     */
    public function retrieveByResetToken(User $user, string $resetToken): ?User;

    /**
     * Refresh a user from the data store.
     *
     * @param User $user
     *
     * @return User
     */
    public function retrieveById(User $user): User;

    /**
     * Save a user.
     *
     * @param User $user
     *
     * @return void
     */
    public function save(User $user): void;

    /**
     * Determine if a password verifies against the user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return bool
     */
    public function verifyPassword(User $user, string $password): bool;

    /**
     * Update a user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @throws Exception
     *
     * @return void
     */
    public function updatePassword(User $user, string $password): void;

    /**
     * Generate a reset token for a user.
     *
     * @param User $user
     *
     * @throws Exception
     *
     * @return void
     */
    public function updateResetToken(User $user): void;

    /**
     * Register a new user.
     *
     * @param User $user
     *
     * @throws InvalidRegistrationException
     *
     * @return bool
     */
    public function create(User $user): bool;
}
