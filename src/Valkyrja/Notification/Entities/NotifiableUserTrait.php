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

    /**
     * Get the name field value.
     *
     * @return string
     */
    public function getNameFieldValue(): string
    {
        return static::hasNameField()
            ? $this->{static::getNameField()}
            : '';
    }

    /**
     * Set the name field value.
     *
     * @param string $name The name
     *
     * @return void
     */
    public function setNameFieldValue(string $name): void
    {
        if (static::hasNameField()) {
            $this->{static::getNameField()} = $name;
        }
    }

    /**
     * Get the phone number field value.
     *
     * @return string
     */
    public function getPhoneNumberFieldValue(): string
    {
        return static::hasPhoneNumberField()
            ? $this->{static::getPhoneNumberField()}
            : '';
    }

    /**
     * Set the phone number field value.
     *
     * @param string $phoneNumber The phone number
     *
     * @return void
     */
    public function setPhoneNumberFieldValue(string $phoneNumber): void
    {
        if (static::hasPhoneNumberField()) {
            $this->{static::getPhoneNumberField()} = $phoneNumber;
        }
    }

    /**
     * Get the secret id field value.
     *
     * @return string
     */
    public function getSecretIdFieldValue(): string
    {
        return static::hasSecretIdField()
            ? $this->{static::getSecretIdField()}
            : '';
    }

    /**
     * Set the secret id field value.
     *
     * @param string $secretId The secret id
     *
     * @return void
     */
    public function setSecretIdFieldValue(string $secretId): void
    {
        if (static::hasSecretIdField()) {
            $this->{static::getSecretIdField()} = $secretId;
        }
    }
}
