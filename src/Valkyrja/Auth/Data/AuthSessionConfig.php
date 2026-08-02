<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Data;

use Valkyrja\Auth\Constant\SessionItemId;
use Valkyrja\Auth\Data\Contract\AuthenticatedUsersContract;

class AuthSessionConfig
{
    /**
     * @param non-empty-string                           $itemId         The session item id to store the users under
     * @param class-string<AuthenticatedUsersContract>[] $allowedClasses The classes the session may deserialize
     */
    public function __construct(
        public readonly string $itemId = SessionItemId::AUTHENTICATED_USERS,
        public readonly array $allowedClasses = [AuthenticatedUsers::class],
    ) {
    }
}
