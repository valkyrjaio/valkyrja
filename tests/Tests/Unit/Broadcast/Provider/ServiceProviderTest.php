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
use Valkyrja\Broadcast\Provider\BroadcastServiceProvider;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = BroadcastServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(BroadcasterContract::class, new BroadcastServiceProvider()->publishers());
        self::assertArrayHasKey(PusherBroadcaster::class, new BroadcastServiceProvider()->publishers());
        self::assertArrayHasKey(CryptPusherBroadcaster::class, new BroadcastServiceProvider()->publishers());
        self::assertArrayHasKey(Pusher::class, new BroadcastServiceProvider()->publishers());
        self::assertArrayHasKey(LogBroadcaster::class, new BroadcastServiceProvider()->publishers());
        self::assertArrayHasKey(NullBroadcaster::class, new BroadcastServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishBroadcaster(): void
    {
        $this->container->setSingleton(PusherBroadcaster::class, self::createStub(PusherBroadcaster::class));

        $callback = new BroadcastServiceProvider()->publishers()[BroadcasterContract::class];
        $callback($this->container);

        self::assertInstanceOf(PusherBroadcaster::class, $this->container->getSingleton(BroadcasterContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPusherBroadcaster(): void
    {
        $this->container->setSingleton(Pusher::class, self::createStub(Pusher::class));

        $callback = new BroadcastServiceProvider()->publishers()[PusherBroadcaster::class];
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

        $callback = new BroadcastServiceProvider()->publishers()[CryptPusherBroadcaster::class];
        $callback($this->container);

        self::assertInstanceOf(CryptPusherBroadcaster::class, $this->container->getSingleton(CryptPusherBroadcaster::class));
    }

    /**
     * @throws PusherException
     */
    public function testPublishPusher(): void
    {
        $callback = new BroadcastServiceProvider()->publishers()[Pusher::class];
        $callback($this->container);

        self::assertInstanceOf(Pusher::class, $this->container->getSingleton(Pusher::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogBroadcaster(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = new BroadcastServiceProvider()->publishers()[LogBroadcaster::class];
        $callback($this->container);

        self::assertInstanceOf(LogBroadcaster::class, $this->container->getSingleton(LogBroadcaster::class));
    }

    public function testPublishNullBroadcaster(): void
    {
        $callback = new BroadcastServiceProvider()->publishers()[NullBroadcaster::class];
        $callback($this->container);

        self::assertInstanceOf(NullBroadcaster::class, $this->container->getSingleton(NullBroadcaster::class));
    }
}
