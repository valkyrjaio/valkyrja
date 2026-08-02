<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Auth\Entity\Trait;

use Valkyrja\Auth\Constant\UserField;

trait LockableUserMethods
{
    /**
     * @inheritDoc
     */
    public static function getMaxLoginAttempts(): int
    {
        return 3;
    }

    /**
     * @inheritDoc
     */
    public static function getLoginAttemptsField(): string
    {
        return UserField::LOGIN_ATTEMPTS;
    }

    /**
     * @inheritDoc
     */
    public static function getIsLockedField(): string
    {
        return UserField::IS_LOCKED;
    }
}
