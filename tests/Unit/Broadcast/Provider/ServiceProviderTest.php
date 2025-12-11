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
use Valkyrja\Broadcast\Contract\Broadcaster as Contract;
use Valkyrja\Broadcast\CryptPusherBroadcaster;
use Valkyrja\Broadcast\LogBroadcaster;
use Valkyrja\Broadcast\NullBroadcaster;
use Valkyrja\Broadcast\Provider\ServiceProvider;
use Valkyrja\Broadcast\PusherBroadcaster;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishBroadcaster(): void
    {
        $this->container->setSingleton(PusherBroadcaster::class, $this->createStub(PusherBroadcaster::class));

        ServiceProvider::publishBroadcaster($this->container);

        self::assertInstanceOf(PusherBroadcaster::class, $this->container->getSingleton(Contract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishPusherBroadcaster(): void
    {
        $this->container->setSingleton(Pusher::class, $this->createStub(Pusher::class));

        ServiceProvider::publishPusherBroadcaster($this->container);

        self::assertInstanceOf(PusherBroadcaster::class, $this->container->getSingleton(PusherBroadcaster::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCryptPusherBroadcaster(): void
    {
        $this->container->setSingleton(Pusher::class, $this->createStub(Pusher::class));
        $this->container->setSingleton(Crypt::class, $this->createStub(Crypt::class));

        ServiceProvider::publishCryptPusherBroadcaster($this->container);

        self::assertInstanceOf(CryptPusherBroadcaster::class, $this->container->getSingleton(CryptPusherBroadcaster::class));
    }

    /**
     * @throws PusherException
     */
    public function testPublishPusher(): void
    {
        ServiceProvider::publishPusher($this->container);

        self::assertInstanceOf(Pusher::class, $this->container->getSingleton(Pusher::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogBroadcaster(): void
    {
        $this->container->setSingleton(Logger::class, $this->createStub(Logger::class));

        ServiceProvider::publishLogBroadcaster($this->container);

        self::assertInstanceOf(LogBroadcaster::class, $this->container->getSingleton(LogBroadcaster::class));
    }

    public function testPublishNullBroadcaster(): void
    {
        ServiceProvider::publishNullBroadcaster($this->container);

        self::assertInstanceOf(NullBroadcaster::class, $this->container->getSingleton(NullBroadcaster::class));
    }
}
