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
use Valkyrja\Tests\Fixtures\Http\Middleware\Handler\RouteDispatchedHandlerFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteDispatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteDispatchedMiddlewareFixture;

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
        $dispatchedHandler = new RouteDispatchedHandlerFixture($this->container);

        $dispatched = $dispatchedHandler->routeDispatched($this->request, $this->response, $this->route);

        self::assertSame($this->response, $dispatched);

        self::assertSame(1, $dispatchedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RouteDispatchedMiddlewareChangedFixture::resetCounter();

        $handler = new RouteDispatchedHandlerFixture($this->container);

        $handler->add(RouteDispatchedMiddlewareChangedFixture::class);
        $dispatched = $handler->routeDispatched($this->request, $this->response, $this->route);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RouteDispatchedMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->response, $dispatched);
        self::assertInstanceOf(Response::class, $dispatched);
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
        $dispatched = $handler->routeDispatched($this->request, $this->response, $this->route);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RouteDispatchedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, RouteDispatchedMiddlewareFixture::getCounter());
        self::assertNotSame($this->response, $dispatched);
        self::assertInstanceOf(Response::class, $dispatched);
    }

    /**
     * Test the dispatched method.
     */
    public function testDispatched(): void
    {
        RouteDispatchedMiddlewareChangedFixture::resetCounter();
        RouteDispatchedMiddlewareFixture::resetCounter();

        $handler = new RouteDispatchedHandlerFixture(
            $this->container,
            RouteDispatchedMiddlewareFixture::class,
            RouteDispatchedMiddlewareFixture::class
        );

        $dispatched = $handler->routeDispatched($this->request, $this->response, $this->route);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RouteDispatchedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, RouteDispatchedMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->response, $dispatched);
    }
}
