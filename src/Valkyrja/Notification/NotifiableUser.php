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

use Valkyrja\Auth\EmailableUser;

/**
 * Interface NotifiableUser.
 *
 * @author Melech Mizrachi
 */
interface NotifiableUser extends EmailableUser
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
     * Get the name field value.
     *
     * @return string
     */
    public function getNameFieldValue(): string;

    /**
     * Set the name field value.
     *
     * @param string $name The name
     *
     * @return void
     */
    public function setNameFieldValue(string $name): void;

    /**
     * Get the phone number field value.
     *
     * @return string
     */
    public function getPhoneNumberFieldValue(): string;

    /**
     * Set the phone number field value.
     *
     * @param string $phoneNumber The phone number
     *
     * @return void
     */
    public function setPhoneNumberFieldValue(string $phoneNumber): void;
}
