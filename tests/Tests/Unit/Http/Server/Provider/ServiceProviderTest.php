<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Server\Provider;

use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareServiceProvider;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Server\Data\Contract\HttpServerConfigContract;
use Valkyrja\Http\Server\Data\HttpServerConfig;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Handler\RequestHandler;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\RequestReceived\RedirectTrailingSlashMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\RequestStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\ResponseStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\SendingResponse\NoCacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\ViewThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Provider\HttpServerServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Http\Server\Data\HttpServerConfigFixture;
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
        self::assertArrayHasKey(RequestHandlerContract::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(ViewThrowableCaughtMiddleware::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(RequestStructMiddleware::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(ResponseStructMiddleware::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(ViewRouteNotMatchedMiddleware::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(CacheResponseMiddleware::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(NoCacheResponseMiddleware::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(RedirectTrailingSlashMiddleware::class, new HttpServerServiceProvider()->publishers());
        self::assertArrayHasKey(HttpServerConfigContract::class, new HttpServerServiceProvider()->publishers());
    }

    public function testPublishersArray(): void
    {
        $publishers = new HttpServerServiceProvider()->publishers();

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
        HttpMiddlewareServiceProvider::publishResponseSentHandler($container);

        $container->setSingleton(RouterContract::class, self::createStub(RouterContract::class));

        $callback = new HttpServerServiceProvider()->publishers()[RequestHandlerContract::class];
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

        $callback = new HttpServerServiceProvider()->publishers()[LogThrowableCaughtMiddleware::class];
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

        $callback = new HttpServerServiceProvider()->publishers()[ViewThrowableCaughtMiddleware::class];
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

        $callback = new HttpServerServiceProvider()->publishers()[RequestStructMiddleware::class];
        $callback($this->container);

        self::assertTrue($container->has(RequestStructMiddleware::class));
        self::assertTrue($container->isSingleton(RequestStructMiddleware::class));
        self::assertInstanceOf(RequestStructMiddleware::class, $container->getSingleton(RequestStructMiddleware::class));
    }

    public function testPublishResponseStructMiddleware(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ResponseStructMiddleware::class));

        $callback = new HttpServerServiceProvider()->publishers()[ResponseStructMiddleware::class];
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

        $callback = new HttpServerServiceProvider()->publishers()[ViewRouteNotMatchedMiddleware::class];
        $callback($this->container);

        self::assertTrue($container->has(ViewRouteNotMatchedMiddleware::class));
        self::assertTrue($container->isSingleton(ViewRouteNotMatchedMiddleware::class));
        self::assertInstanceOf(ViewRouteNotMatchedMiddleware::class, $container->getSingleton(ViewRouteNotMatchedMiddleware::class));
    }

    public function testPublishConfig(): void
    {
        $callback = new HttpServerServiceProvider()->publishers()[HttpServerConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(HttpServerConfigContract::class, $config = $this->container->getSingleton(HttpServerConfigContract::class));
        self::assertNull($config->responseCacheFilePath);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new HttpServerConfigFixture());

        $callback = new HttpServerServiceProvider()->publishers()[HttpServerConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(HttpServerConfigContract::class, $config = $this->container->getSingleton(HttpServerConfigContract::class));
        self::assertSame('/tmp/response-cache', $config->responseCacheFilePath);
    }

    public function testPublishNoCacheResponseMiddleware(): void
    {
        $callback = new HttpServerServiceProvider()->publishers()[NoCacheResponseMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(
            NoCacheResponseMiddleware::class,
            $this->container->getSingleton(NoCacheResponseMiddleware::class)
        );
    }

    public function testPublishRedirectTrailingSlashMiddleware(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(RedirectTrailingSlashMiddleware::class));

        $callback = new HttpServerServiceProvider()->publishers()[RedirectTrailingSlashMiddleware::class];
        $callback($this->container);

        self::assertTrue($container->has(RedirectTrailingSlashMiddleware::class));
        self::assertTrue($container->isSingleton(RedirectTrailingSlashMiddleware::class));
        self::assertInstanceOf(
            RedirectTrailingSlashMiddleware::class,
            $container->getSingleton(RedirectTrailingSlashMiddleware::class)
        );
    }

    public function testPublishCacheResponseMiddleware(): void
    {
        $this->container->setSingleton(HttpServerConfigContract::class, new HttpServerConfig());

        $callback = new HttpServerServiceProvider()->publishers()[CacheResponseMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(
            CacheResponseMiddleware::class,
            $this->container->getSingleton(CacheResponseMiddleware::class)
        );
    }

    public function testPublishCacheResponseMiddlewareWithConfiguredFilePath(): void
    {
        $this->container->setSingleton(
            HttpServerConfigContract::class,
            new HttpServerConfig(responseCacheFilePath: '/tmp/response-cache')
        );

        $callback = new HttpServerServiceProvider()->publishers()[CacheResponseMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(
            CacheResponseMiddleware::class,
            $this->container->getSingleton(CacheResponseMiddleware::class)
        );
    }
}
