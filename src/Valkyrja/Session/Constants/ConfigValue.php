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

namespace Valkyrja\Session\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Session\Adapters\CacheAdapter;
use Valkyrja\Session\Adapters\CookieAdapter;
use Valkyrja\Session\Adapters\NullAdapter;
use Valkyrja\Session\Adapters\PHPAdapter;
use Valkyrja\Session\Managers\SessionManager;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = CKP::DEFAULT;
    public const ADAPTERS = [
        CKP::CACHE  => CacheAdapter::class,
        CKP::COOKIE => CookieAdapter::class,
        CKP::NULL   => NullAdapter::class,
        CKP::PHP    => PHPAdapter::class,
    ];
    public const DRIVERS  = [
        CKP::DEFAULT => SessionManager::class,
    ];
    public const SESSIONS = [
        CKP::DEFAULT => [
            CKP::ADAPTER => CKP::PHP,
            CKP::DRIVER  => CKP::DEFAULT,
            CKP::ID      => null,
            CKP::NAME    => null,
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::DRIVERS  => self::DRIVERS,
        CKP::SESSIONS => self::SESSIONS,
    ];
}
