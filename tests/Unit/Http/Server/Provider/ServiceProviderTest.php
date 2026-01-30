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

namespace Valkyrja\Tests\Unit\Http\Server\Provider;

use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Http\Middleware\Provider\ServiceProvider as MiddlewareServiceProvider;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Handler\RequestHandler;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\RequestStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\ResponseStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\ViewThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Provider\ServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract;
use Valkyrja\View\Renderer\Contract\RendererContract;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(RequestHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ViewThrowableCaughtMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RequestStructMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ResponseStructMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ViewRouteNotMatchedMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CacheResponseMiddleware::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(RequestHandlerContract::class, ServiceProvider::provides());
        self::assertContains(LogThrowableCaughtMiddleware::class, ServiceProvider::provides());
        self::assertContains(ViewThrowableCaughtMiddleware::class, ServiceProvider::provides());
        self::assertContains(RequestStructMiddleware::class, ServiceProvider::provides());
        self::assertContains(ResponseStructMiddleware::class, ServiceProvider::provides());
        self::assertContains(ViewRouteNotMatchedMiddleware::class, ServiceProvider::provides());
        self::assertContains(CacheResponseMiddleware::class, ServiceProvider::provides());
    }

    public function testPublishersArray(): void
    {
        $publishers = ServiceProvider::publishers();

        self::assertArrayHasKey(RequestHandlerContract::class, $publishers);
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, $publishers);
        self::assertArrayHasKey(ViewThrowableCaughtMiddleware::class, $publishers);

        self::assertSame([ServiceProvider::class, 'publishRequestHandler'], $publishers[RequestHandlerContract::class]);
        self::assertSame([ServiceProvider::class, 'publishLogThrowableCaughtMiddleware'], $publishers[LogThrowableCaughtMiddleware::class]);
        self::assertSame([ServiceProvider::class, 'publishViewThrowableCaughtMiddleware'], $publishers[ViewThrowableCaughtMiddleware::class]);
    }

    public function testProvidesArray(): void
    {
        $provides = ServiceProvider::provides();

        self::assertContains(RequestHandlerContract::class, $provides);
        self::assertContains(LogThrowableCaughtMiddleware::class, $provides);
        self::assertContains(ViewThrowableCaughtMiddleware::class, $provides);
    }

    public function testPublishRequestHandler(): void
    {
        $container = $this->container;

        MiddlewareServiceProvider::publishRequestReceivedHandler($container);
        MiddlewareServiceProvider::publishThrowableCaughtHandler($container);
        MiddlewareServiceProvider::publishSendingResponseHandler($container);
        MiddlewareServiceProvider::publishTerminatedHandler($container);

        $container->setSingleton(RouterContract::class, self::createStub(RouterContract::class));

        $callback = ServiceProvider::publishers()[RequestHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RequestHandler::class,
            $container->getSingleton(RequestHandlerContract::class)
        );
    }

    public function testPublishLogThrowableCaughtMiddleware(): void
    {
        $container = $this->container;

        $container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = ServiceProvider::publishers()[LogThrowableCaughtMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(
            LogThrowableCaughtMiddleware::class,
            $container->getSingleton(LogThrowableCaughtMiddleware::class)
        );
    }

    public function testPublishViewThrowableCaughtMiddleware(): void
    {
        $container = $this->container;

        $container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        $callback = ServiceProvider::publishers()[ViewThrowableCaughtMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(
            ViewThrowableCaughtMiddleware::class,
            $container->getSingleton(ViewThrowableCaughtMiddleware::class)
        );
    }

    public function testPublishRequestStructMiddleware(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(RequestStructMiddleware::class));

        $callback = ServiceProvider::publishers()[RequestStructMiddleware::class];
        $callback($this->container);

        self::assertTrue($container->has(RequestStructMiddleware::class));
        self::assertTrue($container->isSingleton(RequestStructMiddleware::class));
        self::assertInstanceOf(RequestStructMiddleware::class, $container->getSingleton(RequestStructMiddleware::class));
    }

    public function testPublishResponseStructMiddleware(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ResponseStructMiddleware::class));

        $callback = ServiceProvider::publishers()[ResponseStructMiddleware::class];
        $callback($this->container);

        self::assertTrue($container->has(ResponseStructMiddleware::class));
        self::assertTrue($container->isSingleton(ResponseStructMiddleware::class));
        self::assertInstanceOf(ResponseStructMiddleware::class, $container->getSingleton(ResponseStructMiddleware::class));
    }

    public function testPublishViewRouteNotMatchedMiddleware(): void
    {
        $container = $this->container;

        $container->setSingleton(RendererContract::class, self::createStub(RendererContract::class));

        self::assertFalse($container->has(ViewRouteNotMatchedMiddleware::class));

        $callback = ServiceProvider::publishers()[ViewRouteNotMatchedMiddleware::class];
        $callback($this->container);

        self::assertTrue($container->has(ViewRouteNotMatchedMiddleware::class));
        self::assertTrue($container->isSingleton(ViewRouteNotMatchedMiddleware::class));
        self::assertInstanceOf(ViewRouteNotMatchedMiddleware::class, $container->getSingleton(ViewRouteNotMatchedMiddleware::class));
    }

    public function testPublishCacheResponseMiddleware(): void
    {
        $this->container->setSingleton(
            FilesystemContract::class,
            self::createStub(FilesystemContract::class)
        );

        $callback = ServiceProvider::publishers()[CacheResponseMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(
            CacheResponseMiddleware::class,
            $this->container->getSingleton(CacheResponseMiddleware::class)
        );
    }
}
