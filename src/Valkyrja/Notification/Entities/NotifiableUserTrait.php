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
     * Does this user have a name field.
     *
     * @var bool
     */
    protected static bool $hasNameField = true;

    /**
     * The name field.
     *
     * @var string
     */
    protected static string $nameField = UserField::NAME;

    /**
     * Does this user have a phone number field.
     *
     * @var bool
     */
    protected static bool $hasPhoneNumberField = true;

    /**
     * The phone number field.
     *
     * @var string
     */
    protected static string $phoneNumberField = UserField::PHONE_NUMBER;

    /**
     * Does this user have a secret id field.
     *
     * @var bool
     */
    protected static bool $hasSecretIdField = true;

    /**
     * The secret id field.
     *
     * @var string
     */
    protected static string $secretIdField = UserField::SECRET_ID;

    /**
     * Whether this user entity has a name field.
     *
     * @return bool
     */
    public static function hasNameField(): bool
    {
        return static::$hasNameField;
    }

    /**
     * Get the name field.
     *
     * @return string
     */
    public static function getNameField(): string
    {
        return static::$nameField;
    }

    /**
     * Whether this user entity has a phone number field.
     *
     * @return bool
     */
    public static function hasPhoneNumberField(): bool
    {
        return static::$hasPhoneNumberField;
    }

    /**
     * Get the phone number field.
     *
     * @return string
     */
    public static function getPhoneNumberField(): string
    {
        return static::$phoneNumberField;
    }

    /**
     * Whether this user entity has a secret id field.
     *
     * @return bool
     */
    public static function hasSecretIdField(): bool
    {
        return static::$hasSecretIdField;
    }

    /**
     * Get the secret id field.
     *
     * @return string
     */
    public static function getSecretIdField(): string
    {
        return static::$phoneNumberField;
    }

    /**
     * Get the name field value.
     *
     * @return string
     */
    public function getNameFieldValue(): string
    {
        return static::$hasNameField
            ? $this->{static::$nameField}
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
        if (static::$hasNameField) {
            $this->{static::$nameField} = $name;
        }
    }

    /**
     * Get the phone number field value.
     *
     * @return string
     */
    public function getPhoneNumberFieldValue(): string
    {
        return static::$hasPhoneNumberField
            ? $this->{static::$phoneNumberField}
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
        if (static::$hasPhoneNumberField) {
            $this->{static::$phoneNumberField} = $phoneNumber;
        }
    }

    /**
     * Get the secret id field value.
     *
     * @return string
     */
    public function getSecretIdFieldValue(): string
    {
        return static::$hasSecretIdField
            ? $this->{static::$phoneNumberField}
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
        if (static::$hasSecretIdField) {
            $this->{static::$phoneNumberField} = $secretId;
        }
    }
}
