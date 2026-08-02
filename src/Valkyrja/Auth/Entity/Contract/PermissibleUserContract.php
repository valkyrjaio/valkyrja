<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Entity\Contract;

interface PermissibleUserContract extends UserContract
{
    /**
     * Get whether the user is allowed.
     *
     * @param string $permission The permission
     */
    public function isAllowed(string $permission): bool;

    /**
     * Get whether the user is denied.
     *
     * @param string $permission The permission
     */
    public function isDenied(string $permission): bool;
}
