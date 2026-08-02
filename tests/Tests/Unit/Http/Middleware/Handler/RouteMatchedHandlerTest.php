<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Middleware\Handler;

use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Tests\Fixtures\Http\Middleware\Handler\RouteMatchedHandlerFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteMatchedMiddlewareFixture;

/**
 * Test the route matched handler.
 */
final class RouteMatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $routeMatchedHandler = new RouteMatchedHandlerFixture($this->container);

        $routeMatched = $routeMatchedHandler->routeMatched($this->request, $this->route);

        self::assertSame($this->route, $routeMatched);

        self::assertSame(1, $routeMatchedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RouteMatchedMiddlewareChangedFixture::resetCounter();

        $handler = new RouteMatchedHandlerFixture($this->container);

        $handler->add(RouteMatchedMiddlewareChangedFixture::class);
        $routeMatched = $handler->routeMatched($this->request, $this->route);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RouteMatchedMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->request, $routeMatched);
        self::assertInstanceOf(Response::class, $routeMatched);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        RouteMatchedMiddlewareChangedFixture::resetCounter();
        RouteMatchedMiddlewareFixture::resetCounter();

        $handler = new RouteMatchedHandlerFixture(
            $this->container,
            RouteMatchedMiddlewareFixture::class
        );

        $handler->add(RouteMatchedMiddlewareChangedFixture::class);
        $routeMatched = $handler->routeMatched($this->request, $this->route);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RouteMatchedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, RouteMatchedMiddlewareFixture::getCounter());
        self::assertNotSame($this->request, $routeMatched);
        self::assertInstanceOf(Response::class, $routeMatched);
    }

    /**
     * Test the routeMatched method.
     */
    public function testRouteMatched(): void
    {
        RouteMatchedMiddlewareChangedFixture::resetCounter();
        RouteMatchedMiddlewareFixture::resetCounter();

        $handler = new RouteMatchedHandlerFixture(
            $this->container,
            RouteMatchedMiddlewareFixture::class,
            RouteMatchedMiddlewareFixture::class
        );

        $routeMatched = $handler->routeMatched($this->request, $this->route);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RouteMatchedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, RouteMatchedMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->route, $routeMatched);
    }
}
