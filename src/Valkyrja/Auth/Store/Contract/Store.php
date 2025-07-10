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

namespace Valkyrja\Auth\Store\Contract;

use Valkyrja\Auth\Data\Retrieval\Contract\Retrieval;
use Valkyrja\Auth\Entity\Contract\User;

/**
 * Interface Store.
 *
 * @author Melech Mizrachi
 *
 * @template U of User
 */
interface Store
{
    /**
     * Retrieve a user with given criteria.
     *
     * @param Retrieval       $retrieval The retrieval criteria
     * @param class-string<U> $user      The user class
     *
     * @return U|null
     */
    public function retrieve(Retrieval $retrieval, string $user): User|null;

    /**
     * Create a new user.
     *
     * @param U $user The user
     *
     * @return void
     */
    public function create(User $user): void;

    /**
     * Update a given user.
     *
     * @param U $user The user
     *
     * @return void
     */
    public function update(User $user): void;
}
