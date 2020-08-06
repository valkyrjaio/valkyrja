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
     * The auth repository.
     *
     * @var string|null
     */
    protected static ?string $authRepository = null;

    /**
     * The session id.
     *
     * @var string
     */
    protected static string $sessionId = SessionId::USER;

    /**
     * The username field.
     *
     * @var string
     */
    protected static string $usernameField = UserField::USERNAME;

    /**
     * The hashed password field.
     *
     * @var string
     */
    protected static string $passwordField = UserField::PASSWORD;

    /**
     * The reset token field.
     *
     * @var string
     */
    protected static string $resetTokenField = UserField::RESET_TOKEN;

    /**
     * Get the auth repository.
     *
     * @return string|null
     */
    public static function getAuthRepository(): ?string
    {
        return static::$authRepository;
    }

    /**
     * Get the session id.
     *
     * @return string
     */
    public static function getSessionId(): string
    {
        return static::$sessionId;
    }

    /**
     * Get the username field.
     *
     * @return string
     */
    public static function getUsernameField(): string
    {
        return static::$usernameField;
    }

    /**
     * Get the hashed password field.
     *
     * @return string
     */
    public static function getPasswordField(): string
    {
        return static::$passwordField;
    }

    /**
     * Get the reset token field.
     *
     * @return string
     */
    public static function getResetTokenField(): string
    {
        return static::$resetTokenField;
    }

    /**
     * Get the username field value.
     *
     * @return string
     */
    public function getUsernameFieldValue(): string
    {
        return $this->{static::$usernameField};
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
        $this->{static::$usernameField} = $username;
    }

    /**
     * Get the hashed password field value.
     *
     * @return string
     */
    public function getPasswordFieldValue(): string
    {
        return $this->{static::$passwordField};
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
        $this->{static::$passwordField} = $password;
    }

    /**
     * Get the reset token field value.
     *
     * @return string|null
     */
    public function getResetTokenFieldValue(): ?string
    {
        return $this->{static::$resetTokenField};
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
        $this->{static::$resetTokenField} = $resetToken;
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
