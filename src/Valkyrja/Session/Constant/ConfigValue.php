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

namespace Valkyrja\Session\Constant;

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Session\Adapter\PHPAdapter;
use Valkyrja\Session\Driver\Driver;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = 'default';
    public const ADAPTER  = PHPAdapter::class;
    public const DRIVER   = Driver::class;
    public const SESSIONS = [
        CKP::DEFAULT => [
            CKP::ADAPTER       => null,
            CKP::DRIVER        => null,
            CKP::ID            => null,
            CKP::NAME          => null,
            /*
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

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTER  => self::ADAPTER,
        CKP::DRIVER   => self::DRIVER,
        CKP::SESSIONS => self::SESSIONS,
    ];
}
