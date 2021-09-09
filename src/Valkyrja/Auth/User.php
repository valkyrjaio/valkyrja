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
     * Get the authenticated users collection class.
     *
     * @return string|null
     */
    public static function getAuthCollection(): ?string;

    /**
     * Get the user session id.
     *
     * @return string
     */
    public static function getUserSessionId(): string;

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
     * Get the login fields to use.
     *
     * @return string[]
     */
    public static function getAuthenticationFields(): array;
}
