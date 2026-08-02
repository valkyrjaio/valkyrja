<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
