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

namespace Valkyrja\Tests\Unit\Broadcast\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Pusher\Pusher;
use Pusher\PusherException;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Broadcaster\CryptPusherBroadcaster;
use Valkyrja\Broadcast\Broadcaster\LogBroadcaster;
use Valkyrja\Broadcast\Broadcaster\NullBroadcaster;
use Valkyrja\Broadcast\Broadcaster\PusherBroadcaster;
use Valkyrja\Broadcast\Provider\ServiceProvider;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(BroadcasterContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PusherBroadcaster::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CryptPusherBroadcaster::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Pusher::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LogBroadcaster::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullBroadcaster::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(BroadcasterContract::class, ServiceProvider::provides());
        self::assertContains(PusherBroadcaster::class, ServiceProvider::provides());
        self::assertContains(CryptPusherBroadcaster::class, ServiceProvider::provides());
        self::assertContains(Pusher::class, ServiceProvider::provides());
        self::assertContains(LogBroadcaster::class, ServiceProvider::provides());
        self::assertContains(NullBroadcaster::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishBroadcaster(): void
    {
        $this->container->setSingleton(PusherBroadcaster::class, self::createStub(PusherBroadcaster::class));

        $callback = ServiceProvider::publishers()[BroadcasterContract::class];
        $callback($this->container);

        self::assertInstanceOf(PusherBroadcaster::class, $this->container->getSingleton(BroadcasterContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPusherBroadcaster(): void
    {
        $this->container->setSingleton(Pusher::class, self::createStub(Pusher::class));

        $callback = ServiceProvider::publishers()[PusherBroadcaster::class];
        $callback($this->container);

        self::assertInstanceOf(PusherBroadcaster::class, $this->container->getSingleton(PusherBroadcaster::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCryptPusherBroadcaster(): void
    {
        $this->container->setSingleton(Pusher::class, self::createStub(Pusher::class));
        $this->container->setSingleton(CryptContract::class, self::createStub(CryptContract::class));

        $callback = ServiceProvider::publishers()[CryptPusherBroadcaster::class];
        $callback($this->container);

        self::assertInstanceOf(CryptPusherBroadcaster::class, $this->container->getSingleton(CryptPusherBroadcaster::class));
    }

    /**
     * @throws PusherException
     */
    public function testPublishPusher(): void
    {
        $callback = ServiceProvider::publishers()[Pusher::class];
        $callback($this->container);

        self::assertInstanceOf(Pusher::class, $this->container->getSingleton(Pusher::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogBroadcaster(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = ServiceProvider::publishers()[LogBroadcaster::class];
        $callback($this->container);

        self::assertInstanceOf(LogBroadcaster::class, $this->container->getSingleton(LogBroadcaster::class));
    }

    public function testPublishNullBroadcaster(): void
    {
        $callback = ServiceProvider::publishers()[NullBroadcaster::class];
        $callback($this->container);

        self::assertInstanceOf(NullBroadcaster::class, $this->container->getSingleton(NullBroadcaster::class));
    }
}
