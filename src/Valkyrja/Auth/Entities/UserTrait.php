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

namespace Valkyrja\Auth\Entities;

use Valkyrja\Auth\Constants\UserField;
use Valkyrja\Auth\Constants\SessionId;

/**
 * Trait UserTrait.
 *
 * @author Melech Mizrachi
 */
trait UserTrait
{
    /**
     * Get the auth repository.
     *
     * @return string|null
     */
    public static function getAuthRepository(): ?string
    {
        return null;
    }

    /**
     * Get the session id.
     *
     * @return string
     */
    public static function getSessionId(): string
    {
        return SessionId::USER;
    }

    /**
     * Get the username field.
     *
     * @return string
     */
    public static function getUsernameField(): string
    {
        return UserField::USERNAME;
    }

    /**
     * Get the hashed password field.
     *
     * @return string
     */
    public static function getPasswordField(): string
    {
        return UserField::PASSWORD;
    }

    /**
     * Get the reset token field.
     *
     * @return string
     */
    public static function getResetTokenField(): string
    {
        return UserField::RESET_TOKEN;
    }

    /**
     * Get the username field value.
     *
     * @return string
     */
    public function getUsernameFieldValue(): string
    {
        return $this->{static::getUsernameField()};
    }

    /**
     * Set the username field value.
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsernameFieldValue(string $username): void
    {
        $this->{static::getUsernameField()} = $username;
    }

    /**
     * Get the hashed password field value.
     *
     * @return string
     */
    public function getPasswordFieldValue(): string
    {
        return $this->{static::getPasswordField()};
    }

    /**
     * Set the password field value.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPasswordFieldValue(string $password): void
    {
        $this->{static::getPasswordField()} = $password;
    }

    /**
     * Get the reset token field value.
     *
     * @return string|null
     */
    public function getResetTokenFieldValue(): ?string
    {
        return $this->{static::getResetTokenField()};
    }

    /**
     * Set the reset token field value.
     *
     * @param string|null $resetToken
     *
     * @return void
     */
    public function setResetTokenFieldValue(string $resetToken = null): void
    {
        $this->{static::getResetTokenField()} = $resetToken;
    }

    /**
     * Get user as an array for storing in a token.
     *
     * @return array
     */
    public function asArrayForToken(): array
    {
        return $this->forDataStore();
    }

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @return array
     */
    abstract public function forDataStore(): array;
}
