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
use Valkyrja\Tests\Classes\Http\Middleware\Handler\TestRouteNotMatchedHandler;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteNotMatchedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteNotMatchedMiddlewareChanged;

/**
 * Test the route not matched handler.
 *
 * @author Melech Mizrachi
 */
class RouteNotMatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $routeNotMatchedHandler = new TestRouteNotMatchedHandler($this->container);

        $routeNotMatched = $routeNotMatchedHandler->routeNotMatched($this->request, $this->response);

        self::assertSame($this->response, $routeNotMatched);

        self::assertSame(1, $routeNotMatchedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        TestRouteNotMatchedMiddlewareChanged::resetCounter();

        $handler = new TestRouteNotMatchedHandler($this->container);

        $handler->add(TestRouteNotMatchedMiddlewareChanged::class);
        $routeNotMatched = $handler->routeNotMatched($this->request, $this->response);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, TestRouteNotMatchedMiddlewareChanged::getCounter());
        self::assertNotSame($this->response, $routeNotMatched);
        self::assertInstanceOf(Response::class, $routeNotMatched);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        TestRouteNotMatchedMiddlewareChanged::resetCounter();
        TestRouteNotMatchedMiddleware::resetCounter();

        $handler = new TestRouteNotMatchedHandler(
            $this->container,
            TestRouteNotMatchedMiddleware::class
        );

        $handler->add(TestRouteNotMatchedMiddlewareChanged::class);
        $routeNotMatched = $handler->routeNotMatched($this->request, $this->response);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, TestRouteNotMatchedMiddlewareChanged::getCounter());
        self::assertSame(1, TestRouteNotMatchedMiddleware::getCounter());
        self::assertNotSame($this->response, $routeNotMatched);
        self::assertInstanceOf(Response::class, $routeNotMatched);
    }

    /**
     * Test the routeNotMatched method.
     */
    public function testRouteNotMatched(): void
    {
        TestRouteNotMatchedMiddlewareChanged::resetCounter();
        TestRouteNotMatchedMiddleware::resetCounter();

        $handler = new TestRouteNotMatchedHandler(
            $this->container,
            TestRouteNotMatchedMiddleware::class,
            TestRouteNotMatchedMiddleware::class
        );

        $routeNotMatched = $handler->routeNotMatched($this->request, $this->response);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, TestRouteNotMatchedMiddlewareChanged::getAndResetCounter());
        self::assertSame(2, TestRouteNotMatchedMiddleware::getAndResetCounter());
        self::assertSame($this->response, $routeNotMatched);
    }
}
