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
     * @inheritDoc
     */
    public static function getStorableHiddenFields(): array
    {
        return [
            static::getPasswordField(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getAuthRepository(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function getAuthCollection(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function getUserSessionId(): string
    {
        return SessionId::USER;
    }

    /**
     * @inheritDoc
     */
    public static function getUsernameField(): string
    {
        return UserField::USERNAME;
    }

    /**
     * @inheritDoc
     */
    public static function getPasswordField(): string
    {
        return UserField::PASSWORD;
    }

    /**
     * @inheritDoc
     */
    public static function getResetTokenField(): string
    {
        return UserField::RESET_TOKEN;
    }

    /**
     * @inheritDoc
     */
    public static function getAuthenticationFields(): array
    {
        return [
            static::getUsernameField(),
        ];
    }
}
