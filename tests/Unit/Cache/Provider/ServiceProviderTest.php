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
use Valkyrja\Cache\Manager\Contract\CacheContract as Contract;
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

    /**
     * @throws Exception
     */
    public function testPublishCache(): void
    {
        $this->container->setSingleton(RedisCache::class, self::createStub(RedisCache::class));

        ServiceProvider::publishCache($this->container);

        self::assertInstanceOf(RedisCache::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRedisCache(): void
    {
        $this->container->setSingleton(Client::class, self::createStub(Client::class));

        ServiceProvider::publishRedisCache($this->container);

        self::assertInstanceOf(RedisCache::class, $this->container->getSingleton(RedisCache::class));
    }

    public function testPublishRedisClient(): void
    {
        ServiceProvider::publishRedisClient($this->container);

        self::assertInstanceOf(Client::class, $this->container->getSingleton(Client::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogCache(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        ServiceProvider::publishLogCache($this->container);

        self::assertInstanceOf(LogCache::class, $this->container->getSingleton(LogCache::class));
    }

    public function testPublishNullCache(): void
    {
        ServiceProvider::publishNullCache($this->container);

        self::assertInstanceOf(NullCache::class, $this->container->getSingleton(NullCache::class));
    }
}
