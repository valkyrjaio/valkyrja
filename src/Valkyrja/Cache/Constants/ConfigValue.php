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

namespace Valkyrja\Cache\Constants;

use Valkyrja\Cache\Stores\RedisStore;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const STORE  = CKP::REDIS;
    public const STORES = [
        CKP::REDIS => RedisStore::class,
    ];

    public static array $defaults = [
        CKP::DEFAULT => self::STORE,
        CKP::STORES  => self::STORES,
    ];
}
