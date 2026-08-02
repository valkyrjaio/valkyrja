<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Data\Retrieval\Contract;

use Valkyrja\Auth\Entity\Contract\UserContract;

interface RetrievalContract
{
    /**
     * Get the fields to use to retrieve a user by.
     * - Assumed to be in the order of fields as they are in User::getAuthenticationFields().
     *
     * @param class-string<UserContract> $user
     *
     * @return array<non-empty-string, non-empty-string|int|bool|null>
     */
    public function getRetrievalFields(string $user): array;
}
