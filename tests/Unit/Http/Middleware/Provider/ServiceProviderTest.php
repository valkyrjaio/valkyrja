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

use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Middleware\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testPublishRequestReceivedHandler(): void
    {
        ServiceProvider::publishRequestReceivedHandler($this->container);

        self::assertInstanceOf(
            RequestReceivedHandler::class,
            $this->container->getSingleton(RequestReceivedHandlerContract::class)
        );
    }

    public function testPublishRouteDispatchedHandler(): void
    {
        ServiceProvider::publishRouteDispatchedHandler($this->container);

        self::assertInstanceOf(
            RouteDispatchedHandler::class,
            $this->container->getSingleton(RouteDispatchedHandlerContract::class)
        );
    }

    public function testPublishThrowableCaughtHandler(): void
    {
        ServiceProvider::publishThrowableCaughtHandler($this->container);

        self::assertInstanceOf(
            ThrowableCaughtHandler::class,
            $this->container->getSingleton(ThrowableCaughtHandlerContract::class)
        );
    }

    public function testPublishRouteMatchedHandler(): void
    {
        ServiceProvider::publishRouteMatchedHandler($this->container);

        self::assertInstanceOf(
            RouteMatchedHandler::class,
            $this->container->getSingleton(RouteMatchedHandlerContract::class)
        );
    }

    public function testPublishRouteNotMatchedHandler(): void
    {
        ServiceProvider::publishRouteNotMatchedHandler($this->container);

        self::assertInstanceOf(
            RouteNotMatchedHandler::class,
            $this->container->getSingleton(RouteNotMatchedHandlerContract::class)
        );
    }

    public function testPublishSendingResponseHandler(): void
    {
        ServiceProvider::publishSendingResponseHandler($this->container);

        self::assertInstanceOf(
            SendingResponseHandler::class,
            $this->container->getSingleton(SendingResponseHandlerContract::class)
        );
    }

    public function testPublishTerminatedHandler(): void
    {
        ServiceProvider::publishTerminatedHandler($this->container);

        self::assertInstanceOf(
            TerminatedHandler::class,
            $this->container->getSingleton(TerminatedHandlerContract::class)
        );
    }

    public function testPublishCacheResponseMiddleware(): void
    {
        $this->container->setSingleton(
            FilesystemContract::class,
            self::createStub(FilesystemContract::class)
        );

        ServiceProvider::publishCacheResponseMiddleware($this->container);

        self::assertInstanceOf(
            CacheResponseMiddleware::class,
            $this->container->getSingleton(CacheResponseMiddleware::class)
        );
    }
}
