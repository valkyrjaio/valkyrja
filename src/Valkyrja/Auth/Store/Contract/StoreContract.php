<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Store\Contract;

use Valkyrja\Auth\Data\Retrieval\Contract\RetrievalContract;
use Valkyrja\Auth\Entity\Contract\UserContract;

/**
 * @template U of UserContract
 */
interface StoreContract
{
    /**
     * Determine if a user is retrievable with given criteria.
     *
     * @param RetrievalContract $retrieval The retrieval criteria
     * @param class-string<U>   $user      The user class
     */
    public function hasRetrievable(RetrievalContract $retrieval, string $user): bool;

    /**
     * Retrieve a user with given criteria.
     *
     * @param RetrievalContract $retrieval The retrieval criteria
     * @param class-string<U>   $user      The user class
     *
     * @return U
     */
    public function retrieve(RetrievalContract $retrieval, string $user): UserContract;

    /**
     * Create a new user.
     *
     * @param U $user The user
     */
    public function create(UserContract $user): void;

    /**
     * Update a given user.
     *
     * @param U $user The user
     */
    public function update(UserContract $user): void;
}
