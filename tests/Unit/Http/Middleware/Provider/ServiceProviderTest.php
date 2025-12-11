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

namespace Valkyrja\Tests\Unit\Http\Middleware\Provider;

use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
use Valkyrja\Http\Middleware\Handler;
use Valkyrja\Http\Middleware\Provider\ServiceProvider;
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

    public function testPublishRequestReceivedHandler(): void
    {
        ServiceProvider::publishRequestReceivedHandler($this->container);

        self::assertInstanceOf(
            Handler\RequestReceivedHandler::class,
            $this->container->getSingleton(Handler\Contract\RequestReceivedHandler::class)
        );
    }

    public function testPublishRouteDispatchedHandler(): void
    {
        ServiceProvider::publishRouteDispatchedHandler($this->container);

        self::assertInstanceOf(
            Handler\RouteDispatchedHandler::class,
            $this->container->getSingleton(Handler\Contract\RouteDispatchedHandler::class)
        );
    }

    public function testPublishThrowableCaughtHandler(): void
    {
        ServiceProvider::publishThrowableCaughtHandler($this->container);

        self::assertInstanceOf(
            Handler\ThrowableCaughtHandler::class,
            $this->container->getSingleton(Handler\Contract\ThrowableCaughtHandler::class)
        );
    }

    public function testPublishRouteMatchedHandler(): void
    {
        ServiceProvider::publishRouteMatchedHandler($this->container);

        self::assertInstanceOf(
            Handler\RouteMatchedHandler::class,
            $this->container->getSingleton(Handler\Contract\RouteMatchedHandler::class)
        );
    }

    public function testPublishRouteNotMatchedHandler(): void
    {
        ServiceProvider::publishRouteNotMatchedHandler($this->container);

        self::assertInstanceOf(
            Handler\RouteNotMatchedHandler::class,
            $this->container->getSingleton(Handler\Contract\RouteNotMatchedHandler::class)
        );
    }

    public function testPublishSendingResponseHandler(): void
    {
        ServiceProvider::publishSendingResponseHandler($this->container);

        self::assertInstanceOf(
            Handler\SendingResponseHandler::class,
            $this->container->getSingleton(Handler\Contract\SendingResponseHandler::class)
        );
    }

    public function testPublishTerminatedHandler(): void
    {
        ServiceProvider::publishTerminatedHandler($this->container);

        self::assertInstanceOf(
            Handler\TerminatedHandler::class,
            $this->container->getSingleton(Handler\Contract\TerminatedHandler::class)
        );
    }

    public function testPublishCacheResponseMiddleware(): void
    {
        $this->container->setSingleton(
            Filesystem::class,
            $this->createStub(Filesystem::class)
        );

        ServiceProvider::publishCacheResponseMiddleware($this->container);

        self::assertInstanceOf(
            CacheResponseMiddleware::class,
            $this->container->getSingleton(CacheResponseMiddleware::class)
        );
    }
}
