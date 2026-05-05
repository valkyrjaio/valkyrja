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

use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareServiceProvider;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Handler\RequestHandler;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\RequestStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\ResponseStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\ViewThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Provider\HttpServerServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;
use Valkyrja\View\Renderer\Contract\RendererContract;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = HttpServerServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(RequestHandlerContract::class, HttpServerServiceProvider::publishers());
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, HttpServerServiceProvider::publishers());
        self::assertArrayHasKey(ViewThrowableCaughtMiddleware::class, HttpServerServiceProvider::publishers());
        self::assertArrayHasKey(RequestStructMiddleware::class, HttpServerServiceProvider::publishers());
        self::assertArrayHasKey(ResponseStructMiddleware::class, HttpServerServiceProvider::publishers());
        self::assertArrayHasKey(ViewRouteNotMatchedMiddleware::class, HttpServerServiceProvider::publishers());
        self::assertArrayHasKey(CacheResponseMiddleware::class, HttpServerServiceProvider::publishers());
    }

    public function testPublishersArray(): void
    {
        $publishers = HttpServerServiceProvider::publishers();

        self::assertArrayHasKey(RequestHandlerContract::class, $publishers);
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, $publishers);
        self::assertArrayHasKey(ViewThrowableCaughtMiddleware::class, $publishers);

        self::assertSame([HttpServerServiceProvider::class, 'publishRequestHandler'], $publishers[RequestHandlerContract::class]);
        self::assertSame([HttpServerServiceProvider::class, 'publishLogThrowableCaughtMiddleware'], $publishers[LogThrowableCaughtMiddleware::class]);
        self::assertSame([HttpServerServiceProvider::class, 'publishViewThrowableCaughtMiddleware'], $publishers[ViewThrowableCaughtMiddleware::class]);
    }

    public function testPublishRequestHandler(): void
    {
        $container = $this->container;

        $container->setSingleton(HttpConfigContract::class, new HttpConfig());

        HttpMiddlewareServiceProvider::publishRequestReceivedHandler($container);
        HttpMiddlewareServiceProvider::publishThrowableCaughtHandler($container);
        HttpMiddlewareServiceProvider::publishSendingResponseHandler($container);
        HttpMiddlewareServiceProvider::publishTerminatedHandler($container);

        $container->setSingleton(RouterContract::class, self::createStub(RouterContract::class));

        $callback = HttpServerServiceProvider::publishers()[RequestHandlerContract::class];
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

        $callback = HttpServerServiceProvider::publishers()[LogThrowableCaughtMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(
            LogThrowableCaughtMiddleware::class,
            $container->getSingleton(LogThrowableCaughtMiddleware::class)
        );
    }

    public function testPublishViewThrowableCaughtMiddleware(): void
    {
        $container = $this->container;

        $container->setSingleton(ViewResponseFactoryContract::class, self::createStub(ViewResponseFactoryContract::class));

        $callback = HttpServerServiceProvider::publishers()[ViewThrowableCaughtMiddleware::class];
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

        $callback = HttpServerServiceProvider::publishers()[RequestStructMiddleware::class];
        $callback($this->container);

        self::assertTrue($container->has(RequestStructMiddleware::class));
        self::assertTrue($container->isSingleton(RequestStructMiddleware::class));
        self::assertInstanceOf(RequestStructMiddleware::class, $container->getSingleton(RequestStructMiddleware::class));
    }

    public function testPublishResponseStructMiddleware(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ResponseStructMiddleware::class));

        $callback = HttpServerServiceProvider::publishers()[ResponseStructMiddleware::class];
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

        $callback = HttpServerServiceProvider::publishers()[ViewRouteNotMatchedMiddleware::class];
        $callback($this->container);

        self::assertTrue($container->has(ViewRouteNotMatchedMiddleware::class));
        self::assertTrue($container->isSingleton(ViewRouteNotMatchedMiddleware::class));
        self::assertInstanceOf(ViewRouteNotMatchedMiddleware::class, $container->getSingleton(ViewRouteNotMatchedMiddleware::class));
    }

    public function testPublishCacheResponseMiddleware(): void
    {
        $callback = HttpServerServiceProvider::publishers()[CacheResponseMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(
            CacheResponseMiddleware::class,
            $this->container->getSingleton(CacheResponseMiddleware::class)
        );
    }
}
