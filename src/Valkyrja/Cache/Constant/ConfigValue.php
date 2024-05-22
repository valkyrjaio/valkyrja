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

namespace Valkyrja\Cache\Constant;

use Valkyrja\Cache\Adapter\RedisAdapter;
use Valkyrja\Cache\Driver\Driver;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT = CKP::REDIS;
    public const ADAPTER = RedisAdapter::class;
    public const DRIVER  = Driver::class;
    public const STORES  = [
        CKP::REDIS => [
            CKP::ADAPTER => CKP::REDIS,
            CKP::DRIVER  => CKP::DEFAULT,
            CKP::HOST    => '',
            CKP::PORT    => '',
            CKP::PREFIX  => '',
        ],
        CKP::NULL  => [
            CKP::ADAPTER => CKP::NULL,
            CKP::DRIVER  => CKP::DEFAULT,
            CKP::PREFIX  => '',
        ],
        CKP::LOG   => [
            CKP::ADAPTER => CKP::LOG,
            CKP::DRIVER  => CKP::DEFAULT,
            CKP::LOG     => null,
            CKP::PREFIX  => '',
        ],
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT => self::DEFAULT,
        CKP::ADAPTER => self::ADAPTER,
        CKP::DRIVER  => self::DRIVER,
        CKP::STORES  => self::STORES,
    ];
}
