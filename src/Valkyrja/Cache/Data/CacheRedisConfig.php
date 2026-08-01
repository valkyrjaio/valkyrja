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

namespace Valkyrja\Cache\Data;

use Valkyrja\Cache\Data\Contract\CacheRedisConfigContract;

class CacheRedisConfig implements CacheRedisConfigContract
{
    /**
     * @param non-empty-string $redisHost   The host to connect to
     * @param int              $redisPort   The port to connect to
     * @param string           $redisPrefix The prefix to prepend to every key
     */
    public function __construct(
        public readonly string $redisHost = '127.0.0.1',
        public readonly int $redisPort = 6379,
        public readonly string $redisPrefix = '',
    ) {
    }
}
