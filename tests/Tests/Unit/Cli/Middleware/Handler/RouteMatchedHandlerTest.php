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
use Valkyrja\Tests\Fixtures\Cli\Middleware\Handler\RouteMatchedHandlerFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteMatchedMiddlewareFixture;

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
        $beforeHandler = new RouteMatchedHandlerFixture($this->container);

        $before = $beforeHandler->routeMatched($this->input, $this->command);

        self::assertSame($this->command, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RouteMatchedMiddlewareChangedFixture::resetCounter();

        $handler = new RouteMatchedHandlerFixture($this->container);

        $handler->add(RouteMatchedMiddlewareChangedFixture::class);
        $before = $handler->routeMatched($this->input, $this->command);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RouteMatchedMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->command, $before);
        self::assertInstanceOf(OutputContract::class, $before);
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
        $before = $handler->routeMatched($this->input, $this->command);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RouteMatchedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, RouteMatchedMiddlewareFixture::getCounter());
        self::assertNotSame($this->command, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        RouteMatchedMiddlewareChangedFixture::resetCounter();
        RouteMatchedMiddlewareFixture::resetCounter();

        $handler = new RouteMatchedHandlerFixture(
            $this->container,
            RouteMatchedMiddlewareFixture::class,
            RouteMatchedMiddlewareFixture::class
        );

        $before = $handler->routeMatched($this->input, $this->command);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RouteMatchedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, RouteMatchedMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->command, $before);
    }
}
