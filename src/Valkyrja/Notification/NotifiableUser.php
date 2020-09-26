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

namespace Valkyrja\Notification;

use Valkyrja\Auth\MailableUser;

/**
 * Interface NotifiableUser.
 *
 * @author Melech Mizrachi
 */
interface NotifiableUser extends MailableUser
{
    /**
     * Whether this user entity has a name field.
     *
     * @return bool
     */
    public static function hasNameField(): bool;

    /**
     * Get the name field.
     *
     * @return string
     */
    public static function getNameField(): string;

    /**
     * Whether this user entity has a phone number field.
     *
     * @return bool
     */
    public static function hasPhoneNumberField(): bool;

    /**
     * Get the phone number field.
     *
     * @return string
     */
    public static function getPhoneNumberField(): string;

    /**
     * Whether this user entity has a secret id field.
     *
     * @return bool
     */
    public static function hasSecretIdField(): bool;

    /**
     * Get the secret id field.
     *
     * @return string
     */
    public static function getSecretIdField(): string;
}
