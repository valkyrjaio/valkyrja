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
use Valkyrja\Session\Adapters\PHPAdapter;
use Valkyrja\Session\Drivers\Driver;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = CKP::DEFAULT;
    public const ADAPTER  = PHPAdapter::class;
    public const DRIVER   = Driver::class;
    public const SESSIONS = [
        CKP::DEFAULT => [
            CKP::ADAPTER       => null,
            CKP::DRIVER        => null,
            CKP::ID            => null,
            CKP::NAME          => null,
            /**
             * @example
             *  [
             *      'lifetime' => 600,
             *      'path'     => '/',
             *      'domain'   => 'example.com',
             *      'secure'   => true,
             *      'httponly' => true,
             *      'samesite' => 'lax',
             *  ]
             */
            CKP::COOKIE_PARAMS => null,
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTER  => self::ADAPTER,
        CKP::DRIVER   => self::DRIVER,
        CKP::SESSIONS => self::SESSIONS,
    ];
}
