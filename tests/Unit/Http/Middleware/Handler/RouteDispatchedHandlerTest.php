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
use Valkyrja\Tests\Classes\Http\Middleware\Handler\TestRouteDispatchedHandler;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteDispatchedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteDispatchedMiddlewareChanged;

/**
 * Test the route dispatched handler.
 *
 * @author Melech Mizrachi
 */
class RouteDispatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $dispatchedHandler = new TestRouteDispatchedHandler($this->container);

        $dispatched = $dispatchedHandler->routeDispatched($this->request, $this->response, $this->route);

        self::assertSame($this->response, $dispatched);

        self::assertSame(1, $dispatchedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        TestRouteDispatchedMiddlewareChanged::resetCounter();

        $handler = new TestRouteDispatchedHandler($this->container);

        $handler->add(TestRouteDispatchedMiddlewareChanged::class);
        $dispatched = $handler->routeDispatched($this->request, $this->response, $this->route);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, TestRouteDispatchedMiddlewareChanged::getCounter());
        self::assertNotSame($this->response, $dispatched);
        self::assertInstanceOf(Response::class, $dispatched);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        TestRouteDispatchedMiddlewareChanged::resetCounter();
        TestRouteDispatchedMiddleware::resetCounter();

        $handler = new TestRouteDispatchedHandler(
            $this->container,
            TestRouteDispatchedMiddleware::class
        );

        $handler->add(TestRouteDispatchedMiddlewareChanged::class);
        $dispatched = $handler->routeDispatched($this->request, $this->response, $this->route);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, TestRouteDispatchedMiddlewareChanged::getCounter());
        self::assertSame(1, TestRouteDispatchedMiddleware::getCounter());
        self::assertNotSame($this->response, $dispatched);
        self::assertInstanceOf(Response::class, $dispatched);
    }

    /**
     * Test the dispatched method.
     */
    public function testDispatched(): void
    {
        TestRouteDispatchedMiddlewareChanged::resetCounter();
        TestRouteDispatchedMiddleware::resetCounter();

        $handler = new TestRouteDispatchedHandler(
            $this->container,
            TestRouteDispatchedMiddleware::class,
            TestRouteDispatchedMiddleware::class
        );

        $dispatched = $handler->routeDispatched($this->request, $this->response, $this->route);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, TestRouteDispatchedMiddlewareChanged::getAndResetCounter());
        self::assertSame(2, TestRouteDispatchedMiddleware::getAndResetCounter());
        self::assertSame($this->response, $dispatched);
    }
}
