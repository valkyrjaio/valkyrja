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

use Valkyrja\Auth\Data\Contract\AuthenticationAttempt;
use Valkyrja\Auth\Data\Contract\AuthenticationRetrieval;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Exception\InvalidRegistrationException;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter2
{
    /**
     * Attempt to authenticate a user.
     *
     * @param AuthenticationAttempt $attempt
     *
     * @return User|null
     */
    public function authenticate(AuthenticationAttempt $attempt): User|null;

    /**
     * Retrieve a user.
     *
     * @param AuthenticationRetrieval $retrieval
     *
     * @return User|null
     */
    public function retrieve(AuthenticationRetrieval $retrieval): User|null;

    /**
     * Create a new user.
     *
     * @param User $user
     *
     * @throws InvalidRegistrationException
     *
     * @return void
     */
    public function create(User $user): void;

    /**
     * Save a user.
     *
     * @param User $user
     *
     * @return void
     */
    public function save(User $user): void;
}
