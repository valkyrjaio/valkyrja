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

namespace Valkyrja\Notification\Entities;

use Valkyrja\Notification\Constants\UserField;

/**
 * Trait NotifiableUserTrait.
 *
 * @author Melech Mizrachi
 */
trait NotifiableUserTrait
{
    /**
     * Whether this user entity has a name field.
     *
     * @return bool
     */
    public static function hasNameField(): bool
    {
        return true;
    }

    /**
     * Get the name field.
     *
     * @return string
     */
    public static function getNameField(): string
    {
        return UserField::NAME;
    }

    /**
     * Whether this user entity has a phone number field.
     *
     * @return bool
     */
    public static function hasPhoneNumberField(): bool
    {
        return true;
    }

    /**
     * Get the phone number field.
     *
     * @return string
     */
    public static function getPhoneNumberField(): string
    {
        return UserField::PHONE_NUMBER;
    }

    /**
     * Whether this user entity has a secret id field.
     *
     * @return bool
     */
    public static function hasSecretIdField(): bool
    {
        return true;
    }

    /**
     * Get the secret id field.
     *
     * @return string
     */
    public static function getSecretIdField(): string
    {
        return UserField::SECRET_ID;
    }
}
