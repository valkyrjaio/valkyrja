<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Auth;

use Valkyrja\ORM\Entity;

/**
 * Interface User.
 *
 * @author Melech Mizrachi
 */
interface User extends Entity
{
    /**
     * Get the auth repository.
     *
     * @return string|null
     */
    public static function getAuthRepository(): ?string;

    /**
     * Get the session id.
     *
     * @return string
     */
    public static function getSessionId(): string;

    /**
     * Get the username field.
     *
     * @return string
     */
    public static function getUsernameField(): string;

    /**
     * Get the hashed password field.
     *
     * @return string
     */
    public static function getPasswordField(): string;

    /**
     * Get the reset token field.
     *
     * @return string
     */
    public static function getResetTokenField(): string;

    /**
     * Get the username field value.
     *
     * @return string
     */
    public function getUsernameFieldValue(): string;

    /**
     * Set the username field value.
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsernameFieldValue(string $username): void;

    /**
     * Get the hashed password field value.
     *
     * @return string
     */
    public function getPasswordFieldValue(): string;

    /**
     * Set the password field value.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPasswordFieldValue(string $password): void;

    /**
     * Get the reset token field value.
     *
     * @return string|null
     */
    public function getResetTokenFieldValue(): ?string;

    /**
     * Set the reset token field value.
     *
     * @param string|null $resetToken
     *
     * @return void
     */
    public function setResetTokenFieldValue(string $resetToken = null): void;

    /**
     * Get user as an array for storing in a token.
     *
     * @return array
     */
    public function asArrayForToken(): array;
}
