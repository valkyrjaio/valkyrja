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

namespace Valkyrja\Auth\Entity;

use Valkyrja\Auth\Constant\SessionId;
use Valkyrja\Auth\Constant\UserField;
use Valkyrja\Auth\Model\Contract\AuthenticatedUsers;
use Valkyrja\Auth\Repository\Contract\Repository;

/**
 * Trait UserTrait.
 *
 * @author Melech Mizrachi
 */
trait UserTrait
{
    /**
     * @inheritDoc
     *
     * @return class-string<Repository>|null
     */
    public static function getAuthRepository(): string|null
    {
        return null;
    }

    /**
     * @inheritDoc
     *
     * @return class-string<AuthenticatedUsers>|null
     */
    public static function getAuthCollection(): string|null
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
