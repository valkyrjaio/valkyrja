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

use Valkyrja\Auth\Constants\SessionId;
use Valkyrja\Auth\Constants\UserField;

/**
 * Trait UserTrait.
 *
 * @author Melech Mizrachi
 */
trait UserTrait
{
    /**
     * Get a list of hidden fields we can expose for storage.
     *
     * @return string[]
     */
    public static function getStorableHiddenFields(): array
    {
        return [
            static::getPasswordField(),
        ];
    }

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
     * Get the authenticated users collection class.
     *
     * @return string|null
     */
    public static function getAuthCollection(): ?string
    {
        return null;
    }

    /**
     * Get the user session id.
     *
     * @return string
     */
    public static function getUserSessionId(): string
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
     * Get the login fields to use.
     *
     * @return string[]
     */
    public static function getAuthenticationFields(): array
    {
        return [
            static::getUsernameField(),
        ];
    }
}
