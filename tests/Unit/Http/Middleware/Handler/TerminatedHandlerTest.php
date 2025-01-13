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

use Valkyrja\Tests\Classes\Http\Middleware\Handler\TestTerminatedHandler;
use Valkyrja\Tests\Classes\Http\Middleware\TestTerminatedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestTerminatedMiddlewareChanged;

/**
 * Test the terminated handler.
 *
 * @author Melech Mizrachi
 */
class TerminatedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $terminatedHandler = new TestTerminatedHandler($this->container);

        $terminatedHandler->terminated($this->request, $this->response);

        self::assertSame(1, $terminatedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        TestTerminatedMiddlewareChanged::resetCounter();

        $handler = new TestTerminatedHandler($this->container);

        $handler->add(TestTerminatedMiddlewareChanged::class);
        $handler->terminated($this->request, $this->response);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, TestTerminatedMiddlewareChanged::getCounter());
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        TestTerminatedMiddlewareChanged::resetCounter();
        TestTerminatedMiddleware::resetCounter();

        $handler = new TestTerminatedHandler(
            $this->container,
            TestTerminatedMiddleware::class
        );

        $handler->add(TestTerminatedMiddlewareChanged::class);
        $handler->terminated($this->request, $this->response);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, TestTerminatedMiddlewareChanged::getCounter());
        self::assertSame(1, TestTerminatedMiddleware::getCounter());
    }

    /**
     * Test the terminated method.
     */
    public function testTerminated(): void
    {
        TestTerminatedMiddlewareChanged::resetCounter();
        TestTerminatedMiddleware::resetCounter();

        $handler = new TestTerminatedHandler(
            $this->container,
            TestTerminatedMiddleware::class,
            TestTerminatedMiddleware::class
        );

        $handler->terminated($this->request, $this->response);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, TestTerminatedMiddlewareChanged::getAndResetCounter());
        self::assertSame(2, TestTerminatedMiddleware::getAndResetCounter());
    }
}
