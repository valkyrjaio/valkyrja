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

namespace Valkyrja\Tests\Unit\Cli\Middleware\Provider;

use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Cli\Middleware\InputReceived\CheckForVersionOptionsMiddleware;
use Valkyrja\Cli\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Cli\Middleware\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(InputReceivedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ThrowableCaughtHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RouteMatchedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RouteNotMatchedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RouteDispatchedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ExitedHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CheckForHelpOptionsMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CheckForVersionOptionsMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CheckGlobalInteractionOptionsMiddleware::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(InputReceivedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(ThrowableCaughtHandlerContract::class, ServiceProvider::provides());
        self::assertContains(RouteMatchedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(RouteNotMatchedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(RouteDispatchedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(ExitedHandlerContract::class, ServiceProvider::provides());
        self::assertContains(CheckForHelpOptionsMiddleware::class, ServiceProvider::provides());
        self::assertContains(CheckForVersionOptionsMiddleware::class, ServiceProvider::provides());
        self::assertContains(CheckGlobalInteractionOptionsMiddleware::class, ServiceProvider::provides());
    }

    public function testPublishInputReceivedHandler(): void
    {
        $callback = ServiceProvider::publishers()[InputReceivedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            InputReceivedHandler::class,
            $this->container->getSingleton(InputReceivedHandlerContract::class)
        );
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

    public function testPublishThrowableCaughtHandler(): void
    {
        $callback = ServiceProvider::publishers()[ThrowableCaughtHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            ThrowableCaughtHandler::class,
            $this->container->getSingleton(ThrowableCaughtHandlerContract::class)
        );
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

    public function testPublishRouteNotMatchedHandler(): void
    {
        $callback = ServiceProvider::publishers()[RouteNotMatchedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            RouteNotMatchedHandler::class,
            $this->container->getSingleton(RouteNotMatchedHandlerContract::class)
        );
    }

    public function testPublishExitedHandler(): void
    {
        $callback = ServiceProvider::publishers()[ExitedHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            ExitedHandler::class,
            $this->container->getSingleton(ExitedHandlerContract::class)
        );
    }
}
