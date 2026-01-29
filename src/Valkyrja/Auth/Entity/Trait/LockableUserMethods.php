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
