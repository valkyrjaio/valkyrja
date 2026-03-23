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

use ReflectionProperty;
use Valkyrja\Application\Data\Config;
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
use Valkyrja\Tests\Classes\Http\Middleware\Data\ConfigClass;
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
        self::assertArrayHasKey(RequestReceivedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ThrowableCaughtHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RouteMatchedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RouteNotMatchedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RouteDispatchedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(SendingResponseHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(TerminatedHandlerContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(RequestReceivedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(ThrowableCaughtHandlerContract::class, ServiceProvider::provides());
        self::assertContains(RouteMatchedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(RouteNotMatchedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(RouteDispatchedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(SendingResponseHandlerContract::class, ServiceProvider::provides());
        self::assertContains(TerminatedHandlerContract::class, ServiceProvider::provides());
    }

    public function testPublishRequestReceivedHandler(): void
    {
        $callback = ServiceProvider::publishers()[RequestReceivedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RequestReceivedHandler::class,
            $this->container->getSingleton(RequestReceivedHandlerContract::class)
        );
    }

    public function testPublishRequestReceivedHandlerWithConfig(): void
    {
        $this->container->setSingleton(Config::class, $config = new ConfigClass());

        $callback = ServiceProvider::publishers()[RequestReceivedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RequestReceivedHandler::class,
            $handler = $this->container->getSingleton(RequestReceivedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame($config->requestReceivedMiddleware, $middleware);
    }

    public function testPublishRouteDispatchedHandler(): void
    {
        $callback = ServiceProvider::publishers()[RouteDispatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteDispatchedHandler::class,
            $this->container->getSingleton(RouteDispatchedHandlerContract::class)
        );
    }

    public function testPublishRouteDispatchedHandlerWithConfig(): void
    {
        $this->container->setSingleton(Config::class, $config = new ConfigClass());

        $callback = ServiceProvider::publishers()[RouteDispatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteDispatchedHandler::class,
            $handler = $this->container->getSingleton(RouteDispatchedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame($config->routeDispatchedMiddleware, $middleware);
    }

    public function testPublishThrowableCaughtHandler(): void
    {
        $callback = ServiceProvider::publishers()[ThrowableCaughtHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            ThrowableCaughtHandler::class,
            $this->container->getSingleton(ThrowableCaughtHandlerContract::class)
        );
    }

    public function testPublishThrowableCaughtHandlerWithConfig(): void
    {
        $this->container->setSingleton(Config::class, $config = new ConfigClass());

        $callback = ServiceProvider::publishers()[ThrowableCaughtHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            ThrowableCaughtHandler::class,
            $handler = $this->container->getSingleton(ThrowableCaughtHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame($config->throwableCaughtMiddleware, $middleware);
    }

    public function testPublishRouteMatchedHandler(): void
    {
        $callback = ServiceProvider::publishers()[RouteMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteMatchedHandler::class,
            $this->container->getSingleton(RouteMatchedHandlerContract::class)
        );
    }

    public function testPublishRouteMatchedHandlerWithConfig(): void
    {
        $this->container->setSingleton(Config::class, $config = new ConfigClass());

        $callback = ServiceProvider::publishers()[RouteMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteMatchedHandler::class,
            $handler = $this->container->getSingleton(RouteMatchedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame($config->routeMatchedMiddleware, $middleware);
    }

    public function testPublishRouteNotMatchedHandler(): void
    {
        $callback = ServiceProvider::publishers()[RouteNotMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteNotMatchedHandler::class,
            $this->container->getSingleton(RouteNotMatchedHandlerContract::class)
        );
    }

    public function testPublishRouteNotMatchedHandlerWithConfig(): void
    {
        $this->container->setSingleton(Config::class, $config = new ConfigClass());

        $callback = ServiceProvider::publishers()[RouteNotMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteNotMatchedHandler::class,
            $handler = $this->container->getSingleton(RouteNotMatchedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame($config->routeNotMatchedMiddleware, $middleware);
    }

    public function testPublishSendingResponseHandler(): void
    {
        $callback = ServiceProvider::publishers()[SendingResponseHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            SendingResponseHandler::class,
            $this->container->getSingleton(SendingResponseHandlerContract::class)
        );
    }

    public function testPublishSendingResponseHandlerWithConfig(): void
    {
        $this->container->setSingleton(Config::class, $config = new ConfigClass());

        $callback = ServiceProvider::publishers()[SendingResponseHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            SendingResponseHandler::class,
            $handler = $this->container->getSingleton(SendingResponseHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame($config->sendingResponseMiddleware, $middleware);
    }

    public function testPublishTerminatedHandler(): void
    {
        $callback = ServiceProvider::publishers()[TerminatedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            TerminatedHandler::class,
            $this->container->getSingleton(TerminatedHandlerContract::class)
        );
    }

    public function testPublishTerminatedHandlerWithConfig(): void
    {
        $this->container->setSingleton(Config::class, $config = new ConfigClass());

        $callback = ServiceProvider::publishers()[TerminatedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            TerminatedHandler::class,
            $handler = $this->container->getSingleton(TerminatedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame($config->terminatedMiddleware, $middleware);
    }
}
