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

namespace Valkyrja\Notification\Entity\Trait;

use Valkyrja\Notification\Constant\UserField;

trait NotifiableUserTrait
{
    /**
     * @inheritDoc
     */
    public static function hasNameField(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getNameField(): string
    {
        return UserField::NAME;
    }

    /**
     * @inheritDoc
     */
    public static function hasPhoneNumberField(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getPhoneNumberField(): string
    {
        return UserField::PHONE_NUMBER;
    }

    /**
     * @inheritDoc
     */
    public static function hasSecretIdField(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getSecretIdField(): string
    {
        return UserField::SECRET_ID;
    }
}
