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

class CacheRedisConfig
{
    /**
     * @param non-empty-string $host   The host to connect to
     * @param int              $port   The port to connect to
     * @param string           $prefix The prefix to prepend to every key
     */
    public function __construct(
        public readonly string $host = '127.0.0.1',
        public readonly int $port = 6379,
        public readonly string $prefix = '',
    ) {
    }
}
