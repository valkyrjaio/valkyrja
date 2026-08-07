<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Middleware\Handler;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Fixtures\Cli\Middleware\AllMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\InputReceivedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\InputReceivedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ProcessExitingMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ProcessExitingMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteDispatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteDispatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteNotMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteNotMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ThrowableCaughtMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ThrowableCaughtMiddlewareFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * The Handler test case.
 */
abstract class HandlerTestCase extends TestCase
{
    protected Container $container;

    protected Input $input;

    protected Output $output;

    protected Route $command;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->container = new Container();

        // Every middleware the handler tests schedule is bound, the same way an application binds its own.
        $this->container->bindSingleton(AllMiddlewareFixture::class, static fn (): AllMiddlewareFixture => new AllMiddlewareFixture());
        $this->container->bindSingleton(InputReceivedMiddlewareChangedFixture::class, static fn (): InputReceivedMiddlewareChangedFixture => new InputReceivedMiddlewareChangedFixture());
        $this->container->bindSingleton(InputReceivedMiddlewareFixture::class, static fn (): InputReceivedMiddlewareFixture => new InputReceivedMiddlewareFixture());
        $this->container->bindSingleton(ProcessExitingMiddlewareChangedFixture::class, static fn (): ProcessExitingMiddlewareChangedFixture => new ProcessExitingMiddlewareChangedFixture());
        $this->container->bindSingleton(ProcessExitingMiddlewareFixture::class, static fn (): ProcessExitingMiddlewareFixture => new ProcessExitingMiddlewareFixture());
        $this->container->bindSingleton(RouteDispatchedMiddlewareChangedFixture::class, static fn (): RouteDispatchedMiddlewareChangedFixture => new RouteDispatchedMiddlewareChangedFixture());
        $this->container->bindSingleton(RouteDispatchedMiddlewareFixture::class, static fn (): RouteDispatchedMiddlewareFixture => new RouteDispatchedMiddlewareFixture());
        $this->container->bindSingleton(RouteMatchedMiddlewareChangedFixture::class, static fn (): RouteMatchedMiddlewareChangedFixture => new RouteMatchedMiddlewareChangedFixture());
        $this->container->bindSingleton(RouteMatchedMiddlewareFixture::class, static fn (): RouteMatchedMiddlewareFixture => new RouteMatchedMiddlewareFixture());
        $this->container->bindSingleton(RouteNotMatchedMiddlewareChangedFixture::class, static fn (): RouteNotMatchedMiddlewareChangedFixture => new RouteNotMatchedMiddlewareChangedFixture());
        $this->container->bindSingleton(RouteNotMatchedMiddlewareFixture::class, static fn (): RouteNotMatchedMiddlewareFixture => new RouteNotMatchedMiddlewareFixture());
        $this->container->bindSingleton(ThrowableCaughtMiddlewareChangedFixture::class, static fn (): ThrowableCaughtMiddlewareChangedFixture => new ThrowableCaughtMiddlewareChangedFixture());
        $this->container->bindSingleton(ThrowableCaughtMiddlewareFixture::class, static fn (): ThrowableCaughtMiddlewareFixture => new ThrowableCaughtMiddlewareFixture());

        $this->input   = new Input();
        $this->output  = new Output();
        $this->command = new Route(
            name: 'test',
            description: 'Test Command',
            handler: static fn (): null => null,
        );
    }
}
