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

use Valkyrja\Cache\Data\Contract\CacheConfigContract;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Manager\RedisCache;

class CacheConfig implements CacheConfigContract
{
    /**
     * @param class-string<CacheContract> $defaultCache The cache to use by default
     */
    public function __construct(
        public readonly string $defaultCache = RedisCache::class,
    ) {
    }
}
