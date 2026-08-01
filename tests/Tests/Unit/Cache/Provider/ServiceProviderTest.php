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

namespace Valkyrja\Tests\Unit\Cache\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Predis\Client;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Cache\Data\CacheConfig;
use Valkyrja\Cache\Data\CacheLogConfig;
use Valkyrja\Cache\Data\CacheNullConfig;
use Valkyrja\Cache\Data\CacheRedisConfig;
use Valkyrja\Cache\Data\Contract\CacheConfigContract;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Manager\LogCache;
use Valkyrja\Cache\Manager\NullCache;
use Valkyrja\Cache\Manager\RedisCache;
use Valkyrja\Cache\Provider\CacheServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Cache\Data\CacheConfigFixture;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = CacheServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CacheConfigContract::class, new CacheServiceProvider()->publishers());
        self::assertArrayHasKey(CacheContract::class, new CacheServiceProvider()->publishers());
        self::assertArrayHasKey(RedisCache::class, new CacheServiceProvider()->publishers());
        self::assertArrayHasKey(Client::class, new CacheServiceProvider()->publishers());
        self::assertArrayHasKey(LogCache::class, new CacheServiceProvider()->publishers());
        self::assertArrayHasKey(NullCache::class, new CacheServiceProvider()->publishers());
    }

    public function testPublishConfig(): void
    {
        $callback = new CacheServiceProvider()->publishers()[CacheConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(CacheConfigContract::class, $config = $this->container->getSingleton(CacheConfigContract::class));
        self::assertSame(RedisCache::class, $config->defaultCache);
        self::assertSame('127.0.0.1', $config->redisCache->host);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new CacheConfigFixture(
            redisCache: new CacheRedisConfig(host: 'redis.test'),
        ));

        $callback = new CacheServiceProvider()->publishers()[CacheConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(CacheConfigContract::class, $config = $this->container->getSingleton(CacheConfigContract::class));
        self::assertSame(NullCache::class, $config->defaultCache);
        self::assertSame('redis.test', $config->redisCache->host);
    }

    /**
     * @throws Exception
     */
    public function testPublishCache(): void
    {
        $this->container->setSingleton(CacheConfigContract::class, new CacheConfig());
        $this->container->setSingleton(RedisCache::class, self::createStub(RedisCache::class));

        $callback = new CacheServiceProvider()->publishers()[CacheContract::class];
        $callback($this->container);

        self::assertInstanceOf(RedisCache::class, $this->container->getSingleton(CacheContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCacheWithConfiguredDefault(): void
    {
        $this->container->setSingleton(CacheConfigContract::class, new CacheConfig(defaultCache: NullCache::class));
        $this->container->setSingleton(NullCache::class, self::createStub(NullCache::class));

        $callback = new CacheServiceProvider()->publishers()[CacheContract::class];
        $callback($this->container);

        self::assertInstanceOf(NullCache::class, $this->container->getSingleton(CacheContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRedisCache(): void
    {
        $this->container->setSingleton(CacheConfigContract::class, new CacheConfig(
            redisCache: new CacheRedisConfig(prefix: 'redis:'),
        ));
        $this->container->setSingleton(Client::class, self::createStub(Client::class));

        $callback = new CacheServiceProvider()->publishers()[RedisCache::class];
        $callback($this->container);

        self::assertInstanceOf(RedisCache::class, $this->container->getSingleton(RedisCache::class));
    }

    public function testPublishRedisClient(): void
    {
        $this->container->setSingleton(CacheConfigContract::class, new CacheConfig(
            redisCache: new CacheRedisConfig(host: 'redis.test', port: 6380),
        ));

        $callback = new CacheServiceProvider()->publishers()[Client::class];
        $callback($this->container);

        self::assertInstanceOf(Client::class, $this->container->getSingleton(Client::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogCache(): void
    {
        $this->container->setSingleton(CacheConfigContract::class, new CacheConfig(
            logCache: new CacheLogConfig(prefix: 'log:'),
        ));
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = new CacheServiceProvider()->publishers()[LogCache::class];
        $callback($this->container);

        self::assertInstanceOf(LogCache::class, $this->container->getSingleton(LogCache::class));
    }

    public function testPublishNullCache(): void
    {
        $this->container->setSingleton(CacheConfigContract::class, new CacheConfig(
            nullCache: new CacheNullConfig(prefix: 'null:'),
        ));

        $callback = new CacheServiceProvider()->publishers()[NullCache::class];
        $callback($this->container);

        self::assertInstanceOf(NullCache::class, $this->container->getSingleton(NullCache::class));
    }
}
