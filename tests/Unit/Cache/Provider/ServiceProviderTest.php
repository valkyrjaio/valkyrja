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
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Manager\LogCache;
use Valkyrja\Cache\Manager\NullCache;
use Valkyrja\Cache\Manager\RedisCache;
use Valkyrja\Cache\Provider\ServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CacheContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RedisCache::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Client::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LogCache::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullCache::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(CacheContract::class, ServiceProvider::provides());
        self::assertContains(RedisCache::class, ServiceProvider::provides());
        self::assertContains(Client::class, ServiceProvider::provides());
        self::assertContains(LogCache::class, ServiceProvider::provides());
        self::assertContains(NullCache::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishCache(): void
    {
        $this->container->setSingleton(RedisCache::class, self::createStub(RedisCache::class));

        $callback = ServiceProvider::publishers()[CacheContract::class];
        $callback($this->container);

        self::assertInstanceOf(RedisCache::class, $this->container->getSingleton(CacheContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRedisCache(): void
    {
        $this->container->setSingleton(Client::class, self::createStub(Client::class));

        $callback = ServiceProvider::publishers()[RedisCache::class];
        $callback($this->container);

        self::assertInstanceOf(RedisCache::class, $this->container->getSingleton(RedisCache::class));
    }

    public function testPublishRedisClient(): void
    {
        $callback = ServiceProvider::publishers()[Client::class];
        $callback($this->container);

        self::assertInstanceOf(Client::class, $this->container->getSingleton(Client::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogCache(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = ServiceProvider::publishers()[LogCache::class];
        $callback($this->container);

        self::assertInstanceOf(LogCache::class, $this->container->getSingleton(LogCache::class));
    }

    public function testPublishNullCache(): void
    {
        $callback = ServiceProvider::publishers()[NullCache::class];
        $callback($this->container);

        self::assertInstanceOf(NullCache::class, $this->container->getSingleton(NullCache::class));
    }
}
