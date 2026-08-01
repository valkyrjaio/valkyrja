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

namespace Valkyrja\Tests\Fixtures\Cache\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Cache\Data\CacheLogConfig;
use Valkyrja\Cache\Data\CacheNullConfig;
use Valkyrja\Cache\Data\CacheRedisConfig;
use Valkyrja\Cache\Data\Contract\CacheConfigContract;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Manager\NullCache;

final class CacheConfigFixture extends Config implements CacheConfigContract
{
    /**
     * @param class-string<CacheContract> $defaultCache
     */
    public function __construct(
        public string $defaultCache = NullCache::class,
        public CacheRedisConfig $redisCache = new CacheRedisConfig(),
        public CacheLogConfig $logCache = new CacheLogConfig(),
        public CacheNullConfig $nullCache = new CacheNullConfig(),
    ) {
        parent::__construct();
    }
}
