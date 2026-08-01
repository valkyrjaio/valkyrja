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

namespace Valkyrja\Tests\Unit\Cache\Data;

use Valkyrja\Cache\Data\CacheConfig;
use Valkyrja\Cache\Data\CacheLogConfig;
use Valkyrja\Cache\Data\CacheNullConfig;
use Valkyrja\Cache\Data\CacheRedisConfig;
use Valkyrja\Cache\Data\Contract\CacheConfigContract;
use Valkyrja\Cache\Manager\NullCache;
use Valkyrja\Cache\Manager\RedisCache;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CacheConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(CacheConfigContract::class, new CacheConfig());
    }

    public function testDefaults(): void
    {
        $config = new CacheConfig();

        self::assertSame(RedisCache::class, $config->defaultCache);
        self::assertSame('127.0.0.1', $config->redisCache->host);
        self::assertSame(6379, $config->redisCache->port);
        self::assertSame('', $config->logCache->prefix);
        self::assertSame('', $config->nullCache->prefix);
    }

    public function testCustomValuesAreStored(): void
    {
        $redisCache = new CacheRedisConfig(host: 'redis.test');
        $logCache   = new CacheLogConfig(prefix: 'log:');
        $nullCache  = new CacheNullConfig(prefix: 'null:');

        $config = new CacheConfig(
            defaultCache: NullCache::class,
            redisCache: $redisCache,
            logCache: $logCache,
            nullCache: $nullCache,
        );

        self::assertSame(NullCache::class, $config->defaultCache);
        self::assertSame($redisCache, $config->redisCache);
        self::assertSame($logCache, $config->logCache);
        self::assertSame($nullCache, $config->nullCache);
    }
}
