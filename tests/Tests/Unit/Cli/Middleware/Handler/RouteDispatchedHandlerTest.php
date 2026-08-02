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

use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Handler\RouteDispatchedHandlerFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteDispatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteDispatchedMiddlewareFixture;

/**
 * Test the route dispatched handler.
 */
final class RouteDispatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new RouteDispatchedHandlerFixture($this->container);

        $before = $beforeHandler->routeDispatched($this->input, $this->output, $this->command);

        self::assertSame($this->output, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RouteDispatchedMiddlewareChangedFixture::resetCounter();

        $handler = new RouteDispatchedHandlerFixture($this->container);

        $handler->add(RouteDispatchedMiddlewareChangedFixture::class);
        $before = $handler->routeDispatched($this->input, $this->output, $this->command);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RouteDispatchedMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        RouteDispatchedMiddlewareChangedFixture::resetCounter();
        RouteDispatchedMiddlewareFixture::resetCounter();

        $handler = new RouteDispatchedHandlerFixture(
            $this->container,
            RouteDispatchedMiddlewareFixture::class
        );

        $handler->add(RouteDispatchedMiddlewareChangedFixture::class);
        $before = $handler->routeDispatched($this->input, $this->output, $this->command);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RouteDispatchedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, RouteDispatchedMiddlewareFixture::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        RouteDispatchedMiddlewareChangedFixture::resetCounter();
        RouteDispatchedMiddlewareFixture::resetCounter();

        $handler = new RouteDispatchedHandlerFixture(
            $this->container,
            RouteDispatchedMiddlewareFixture::class,
            RouteDispatchedMiddlewareFixture::class
        );

        $before = $handler->routeDispatched($this->input, $this->output, $this->command);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RouteDispatchedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, RouteDispatchedMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->output, $before);
    }
}
