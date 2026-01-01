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

use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;
use Valkyrja\Auth\Entity\Contract\UserContract;

/**
 * Interface StoreContract.
 *
 * @template U of UserContract
 */
interface StoreContract
{
    /**
     * Retrieve a user with given criteria.
     *
     * @param RetrievalContract $retrieval The retrieval criteria
     * @param class-string<U>   $user      The user class
     *
     * @return U|null
     */
    public function retrieve(RetrievalContract $retrieval, string $user): UserContract|null;

    /**
     * Create a new user.
     *
     * @param U $user The user
     *
     * @return void
     */
    public function create(UserContract $user): void;

    /**
     * Update a given user.
     *
     * @param U $user The user
     *
     * @return void
     */
    public function update(UserContract $user): void;
}
