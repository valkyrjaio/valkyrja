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
use Valkyrja\Tests\Fixtures\Cli\Middleware\Handler\RouteNotMatchedHandlerFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteNotMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteNotMatchedMiddlewareFixture;

/**
 * Test the route not matched handler.
 */
final class RouteNotMatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new RouteNotMatchedHandlerFixture($this->container);

        $before = $beforeHandler->routeNotMatched($this->input, $this->output);

        self::assertSame($this->output, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RouteNotMatchedMiddlewareChangedFixture::resetCounter();

        $handler = new RouteNotMatchedHandlerFixture($this->container);

        $handler->add(RouteNotMatchedMiddlewareChangedFixture::class);
        $before = $handler->routeNotMatched($this->input, $this->output);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RouteNotMatchedMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        RouteNotMatchedMiddlewareChangedFixture::resetCounter();
        RouteNotMatchedMiddlewareFixture::resetCounter();

        $handler = new RouteNotMatchedHandlerFixture(
            $this->container,
            RouteNotMatchedMiddlewareFixture::class
        );

        $handler->add(RouteNotMatchedMiddlewareChangedFixture::class);
        $before = $handler->routeNotMatched($this->input, $this->output);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RouteNotMatchedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, RouteNotMatchedMiddlewareFixture::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        RouteNotMatchedMiddlewareChangedFixture::resetCounter();
        RouteNotMatchedMiddlewareFixture::resetCounter();

        $handler = new RouteNotMatchedHandlerFixture(
            $this->container,
            RouteNotMatchedMiddlewareFixture::class,
            RouteNotMatchedMiddlewareFixture::class
        );

        $before = $handler->routeNotMatched($this->input, $this->output);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RouteNotMatchedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, RouteNotMatchedMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->output, $before);
    }
}
