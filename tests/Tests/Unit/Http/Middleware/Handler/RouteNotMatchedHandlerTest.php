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
use Valkyrja\Tests\Fixtures\Http\Middleware\Handler\RouteNotMatchedHandlerFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteNotMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteNotMatchedMiddlewareFixture;

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
        $routeNotMatchedHandler = new RouteNotMatchedHandlerFixture($this->container);

        $routeNotMatched = $routeNotMatchedHandler->routeNotMatched($this->request, $this->response);

        self::assertSame($this->response, $routeNotMatched);

        self::assertSame(1, $routeNotMatchedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RouteNotMatchedMiddlewareChangedFixture::resetCounter();

        $handler = new RouteNotMatchedHandlerFixture($this->container);

        $handler->add(RouteNotMatchedMiddlewareChangedFixture::class);
        $routeNotMatched = $handler->routeNotMatched($this->request, $this->response);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RouteNotMatchedMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->response, $routeNotMatched);
        self::assertInstanceOf(Response::class, $routeNotMatched);
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
        $routeNotMatched = $handler->routeNotMatched($this->request, $this->response);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RouteNotMatchedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, RouteNotMatchedMiddlewareFixture::getCounter());
        self::assertNotSame($this->response, $routeNotMatched);
        self::assertInstanceOf(Response::class, $routeNotMatched);
    }

    /**
     * Test the routeNotMatched method.
     */
    public function testRouteNotMatched(): void
    {
        RouteNotMatchedMiddlewareChangedFixture::resetCounter();
        RouteNotMatchedMiddlewareFixture::resetCounter();

        $handler = new RouteNotMatchedHandlerFixture(
            $this->container,
            RouteNotMatchedMiddlewareFixture::class,
            RouteNotMatchedMiddlewareFixture::class
        );

        $routeNotMatched = $handler->routeNotMatched($this->request, $this->response);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RouteNotMatchedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, RouteNotMatchedMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->response, $routeNotMatched);
    }
}
