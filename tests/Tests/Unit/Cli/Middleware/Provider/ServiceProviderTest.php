<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Middleware\Provider;

use ReflectionProperty;
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ProcessExitingHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ProcessExitingHandler;
use Valkyrja\Cli\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareServiceProvider;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Cli\Middleware\InputReceivedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ProcessExitingMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteDispatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteNotMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ThrowableCaughtMiddlewareFixture;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /**
     * @inheritDoc
     *
     * @var class-string<ServiceProviderContract>
     */
    protected static string $provider = CliMiddlewareServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(InputReceivedHandlerContract::class, new CliMiddlewareServiceProvider()->publishers());
        self::assertArrayHasKey(ThrowableCaughtHandlerContract::class, new CliMiddlewareServiceProvider()->publishers());
        self::assertArrayHasKey(RouteMatchedHandlerContract::class, new CliMiddlewareServiceProvider()->publishers());
        self::assertArrayHasKey(RouteNotMatchedHandlerContract::class, new CliMiddlewareServiceProvider()->publishers());
        self::assertArrayHasKey(RouteDispatchedHandlerContract::class, new CliMiddlewareServiceProvider()->publishers());
        self::assertArrayHasKey(ProcessExitingHandlerContract::class, new CliMiddlewareServiceProvider()->publishers());
    }

    public function testPublishInputReceivedHandler(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());

        $callback = new CliMiddlewareServiceProvider()->publishers()[InputReceivedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            InputReceivedHandler::class,
            $this->container->getSingleton(InputReceivedHandlerContract::class)
        );
    }

    public function testPublishInputReceivedHandlerWithCustomConfig(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig(inputReceivedMiddleware: [InputReceivedMiddlewareFixture::class]));

        $callback = new CliMiddlewareServiceProvider()->publishers()[InputReceivedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            InputReceivedHandler::class,
            $handler = $this->container->getSingleton(InputReceivedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame([InputReceivedMiddlewareFixture::class], $middleware);
    }

    public function testPublishRouteDispatchedHandler(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());

        $callback = new CliMiddlewareServiceProvider()->publishers()[RouteDispatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteDispatchedHandler::class,
            $this->container->getSingleton(RouteDispatchedHandlerContract::class)
        );
    }

    public function testPublishRouteDispatchedHandlerWithCustomConfig(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig(routeDispatchedMiddleware: [RouteDispatchedMiddlewareFixture::class]));

        $callback = new CliMiddlewareServiceProvider()->publishers()[RouteDispatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteDispatchedHandler::class,
            $handler = $this->container->getSingleton(RouteDispatchedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame([RouteDispatchedMiddlewareFixture::class], $middleware);
    }

    public function testPublishThrowableCaughtHandler(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());

        $callback = new CliMiddlewareServiceProvider()->publishers()[ThrowableCaughtHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            ThrowableCaughtHandler::class,
            $this->container->getSingleton(ThrowableCaughtHandlerContract::class)
        );
    }

    public function testPublishThrowableCaughtHandlerWithCustomConfig(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig(throwableCaughtMiddleware: [ThrowableCaughtMiddlewareFixture::class]));

        $callback = new CliMiddlewareServiceProvider()->publishers()[ThrowableCaughtHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            ThrowableCaughtHandler::class,
            $handler = $this->container->getSingleton(ThrowableCaughtHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame([ThrowableCaughtMiddlewareFixture::class], $middleware);
    }

    public function testPublishRouteMatchedHandler(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());

        $callback = new CliMiddlewareServiceProvider()->publishers()[RouteMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteMatchedHandler::class,
            $this->container->getSingleton(RouteMatchedHandlerContract::class)
        );
    }

    public function testPublishRouteMatchedHandlerWithCustomConfig(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig(routeMatchedMiddleware: [RouteMatchedMiddlewareFixture::class]));

        $callback = new CliMiddlewareServiceProvider()->publishers()[RouteMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteMatchedHandler::class,
            $handler = $this->container->getSingleton(RouteMatchedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame([RouteMatchedMiddlewareFixture::class], $middleware);
    }

    public function testPublishRouteNotMatchedHandler(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());

        $callback = new CliMiddlewareServiceProvider()->publishers()[RouteNotMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteNotMatchedHandler::class,
            $this->container->getSingleton(RouteNotMatchedHandlerContract::class)
        );
    }

    public function testPublishRouteNotMatchedHandlerWithCustomConfig(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig(routeNotMatchedMiddleware: [RouteNotMatchedMiddlewareFixture::class]));

        $callback = new CliMiddlewareServiceProvider()->publishers()[RouteNotMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteNotMatchedHandler::class,
            $handler = $this->container->getSingleton(RouteNotMatchedHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame([RouteNotMatchedMiddlewareFixture::class], $middleware);
    }

    public function testPublishProcessExitingHandler(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());

        $callback = new CliMiddlewareServiceProvider()->publishers()[ProcessExitingHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            ProcessExitingHandler::class,
            $this->container->getSingleton(ProcessExitingHandlerContract::class)
        );
    }

    public function testPublishProcessExitingHandlerWithCustomConfig(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig(processExitingMiddleware: [ProcessExitingMiddlewareFixture::class]));

        $callback = new CliMiddlewareServiceProvider()->publishers()[ProcessExitingHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            ProcessExitingHandler::class,
            $handler = $this->container->getSingleton(ProcessExitingHandlerContract::class)
        );

        $reflection = new ReflectionProperty($handler, 'middleware');
        $middleware = $reflection->getValue($handler);

        self::assertSame([ProcessExitingMiddlewareFixture::class], $middleware);
    }
}
