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
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\RouteMatchedHandlerClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteMatchedMiddlewareClass;

/**
 * Test the route matched handler.
 */
class RouteMatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new RouteMatchedHandlerClass($this->container);

        $before = $beforeHandler->routeMatched($this->input, $this->command);

        self::assertSame($this->command, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RouteMatchedMiddlewareChangedClass::resetCounter();

        $handler = new RouteMatchedHandlerClass($this->container);

        $handler->add(RouteMatchedMiddlewareChangedClass::class);
        $before = $handler->routeMatched($this->input, $this->command);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RouteMatchedMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->command, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        RouteMatchedMiddlewareChangedClass::resetCounter();
        RouteMatchedMiddlewareClass::resetCounter();

        $handler = new RouteMatchedHandlerClass(
            $this->container,
            RouteMatchedMiddlewareClass::class
        );

        $handler->add(RouteMatchedMiddlewareChangedClass::class);
        $before = $handler->routeMatched($this->input, $this->command);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RouteMatchedMiddlewareChangedClass::getCounter());
        self::assertSame(1, RouteMatchedMiddlewareClass::getCounter());
        self::assertNotSame($this->command, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        RouteMatchedMiddlewareChangedClass::resetCounter();
        RouteMatchedMiddlewareClass::resetCounter();

        $handler = new RouteMatchedHandlerClass(
            $this->container,
            RouteMatchedMiddlewareClass::class,
            RouteMatchedMiddlewareClass::class
        );

        $before = $handler->routeMatched($this->input, $this->command);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RouteMatchedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, RouteMatchedMiddlewareClass::getAndResetCounter());
        self::assertSame($this->command, $before);
    }
}
