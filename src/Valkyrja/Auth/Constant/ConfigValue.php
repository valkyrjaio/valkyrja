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

namespace Valkyrja\Auth\Constant;

use Valkyrja\Auth\Adapter\ORMAdapter;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Gate\Gate;
use Valkyrja\Auth\Policy\UserPermissiblePolicy;
use Valkyrja\Auth\Repository\Repository;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const ADAPTER                = ORMAdapter::class;
    public const USER_ENTITY            = User::class;
    public const REPOSITORY             = Repository::class;
    public const GATE                   = Gate::class;
    public const POLICY                 = UserPermissiblePolicy::class;
    public const ALWAYS_AUTHENTICATE    = false;
    public const KEEP_USER_FRESH        = false;
    public const AUTHENTICATE_ROUTE     = RouteName::AUTHENTICATE;
    public const AUTHENTICATE_URL       = null;
    public const NOT_AUTHENTICATE_ROUTE = RouteName::DASHBOARD;
    public const NOT_AUTHENTICATE_URL   = null;
    public const PASSWORD_CONFIRM_ROUTE = RouteName::PASSWORD_CONFIRM;
    public const USE_SESSION            = true;

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::ADAPTER                => self::ADAPTER,
        CKP::USER_ENTITY            => self::USER_ENTITY,
        CKP::REPOSITORY             => self::REPOSITORY,
        CKP::GATE                   => self::GATE,
        CKP::POLICY                 => self::POLICY,
        CKP::ALWAYS_AUTHENTICATE    => self::ALWAYS_AUTHENTICATE,
        CKP::KEEP_USER_FRESH        => self::KEEP_USER_FRESH,
        CKP::AUTHENTICATE_ROUTE     => self::AUTHENTICATE_ROUTE,
        CKP::AUTHENTICATE_URL       => self::AUTHENTICATE_URL,
        CKP::NOT_AUTHENTICATE_ROUTE => self::NOT_AUTHENTICATE_ROUTE,
        CKP::NOT_AUTHENTICATE_URL   => self::NOT_AUTHENTICATE_URL,
        CKP::PASSWORD_CONFIRM_ROUTE => self::PASSWORD_CONFIRM_ROUTE,
        CKP::USE_SESSION            => self::USE_SESSION,
    ];
}
