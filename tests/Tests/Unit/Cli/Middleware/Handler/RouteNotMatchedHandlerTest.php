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

namespace Valkyrja\Tests\Unit\Cli\Middleware\Handler;

use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\RouteNotMatchedHandlerClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteNotMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteNotMatchedMiddlewareClass;

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
        $beforeHandler = new RouteNotMatchedHandlerClass($this->container);

        $before = $beforeHandler->routeNotMatched($this->input, $this->output);

        self::assertSame($this->output, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RouteNotMatchedMiddlewareChangedClass::resetCounter();

        $handler = new RouteNotMatchedHandlerClass($this->container);

        $handler->add(RouteNotMatchedMiddlewareChangedClass::class);
        $before = $handler->routeNotMatched($this->input, $this->output);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RouteNotMatchedMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        RouteNotMatchedMiddlewareChangedClass::resetCounter();
        RouteNotMatchedMiddlewareClass::resetCounter();

        $handler = new RouteNotMatchedHandlerClass(
            $this->container,
            RouteNotMatchedMiddlewareClass::class
        );

        $handler->add(RouteNotMatchedMiddlewareChangedClass::class);
        $before = $handler->routeNotMatched($this->input, $this->output);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RouteNotMatchedMiddlewareChangedClass::getCounter());
        self::assertSame(1, RouteNotMatchedMiddlewareClass::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        RouteNotMatchedMiddlewareChangedClass::resetCounter();
        RouteNotMatchedMiddlewareClass::resetCounter();

        $handler = new RouteNotMatchedHandlerClass(
            $this->container,
            RouteNotMatchedMiddlewareClass::class,
            RouteNotMatchedMiddlewareClass::class
        );

        $before = $handler->routeNotMatched($this->input, $this->output);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RouteNotMatchedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, RouteNotMatchedMiddlewareClass::getAndResetCounter());
        self::assertSame($this->output, $before);
    }
}
