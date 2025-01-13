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

namespace Valkyrja\Tests\Unit\Http\Middleware\Handler;

use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Tests\Classes\Http\Middleware\Handler\TestRouteMatchedHandler;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteMatchedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteMatchedMiddlewareChanged;

/**
 * Test the route matched handler.
 *
 * @author Melech Mizrachi
 */
class RouteMatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $routeMatchedHandler = new TestRouteMatchedHandler($this->container);

        $routeMatched = $routeMatchedHandler->routeMatched($this->request, $this->route);

        self::assertSame($this->route, $routeMatched);

        self::assertSame(1, $routeMatchedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        TestRouteMatchedMiddlewareChanged::resetCounter();

        $handler = new TestRouteMatchedHandler($this->container);

        $handler->add(TestRouteMatchedMiddlewareChanged::class);
        $routeMatched = $handler->routeMatched($this->request, $this->route);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, TestRouteMatchedMiddlewareChanged::getCounter());
        self::assertNotSame($this->request, $routeMatched);
        self::assertInstanceOf(Response::class, $routeMatched);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        TestRouteMatchedMiddlewareChanged::resetCounter();
        TestRouteMatchedMiddleware::resetCounter();

        $handler = new TestRouteMatchedHandler(
            $this->container,
            TestRouteMatchedMiddleware::class
        );

        $handler->add(TestRouteMatchedMiddlewareChanged::class);
        $routeMatched = $handler->routeMatched($this->request, $this->route);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, TestRouteMatchedMiddlewareChanged::getCounter());
        self::assertSame(1, TestRouteMatchedMiddleware::getCounter());
        self::assertNotSame($this->request, $routeMatched);
        self::assertInstanceOf(Response::class, $routeMatched);
    }

    /**
     * Test the routeMatched method.
     */
    public function testRouteMatched(): void
    {
        TestRouteMatchedMiddlewareChanged::resetCounter();
        TestRouteMatchedMiddleware::resetCounter();

        $handler = new TestRouteMatchedHandler(
            $this->container,
            TestRouteMatchedMiddleware::class,
            TestRouteMatchedMiddleware::class
        );

        $routeMatched = $handler->routeMatched($this->request, $this->route);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, TestRouteMatchedMiddlewareChanged::getAndResetCounter());
        self::assertSame(2, TestRouteMatchedMiddleware::getAndResetCounter());
        self::assertSame($this->route, $routeMatched);
    }
}
