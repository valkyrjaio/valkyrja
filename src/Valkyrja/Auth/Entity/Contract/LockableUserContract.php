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

interface LockableUserContract extends UserContract
{
    /**
     * Get the max number login attempts before locking.
     */
    public static function getMaxLoginAttempts(): int;

    /**
     * Get the login attempts field.
     */
    public static function getLoginAttemptsField(): string;

    /**
     * Get the locked flag field.
     */
    public static function getIsLockedField(): string;
}
