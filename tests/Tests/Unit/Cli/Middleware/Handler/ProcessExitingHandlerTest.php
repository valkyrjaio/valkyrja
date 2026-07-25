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

use Valkyrja\Tests\Fixtures\Cli\Middleware\Handler\ProcessExitingHandlerFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ProcessExitingMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ProcessExitingMiddlewareFixture;

/**
 * Test the process exiting handler.
 */
final class ProcessExitingHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new ProcessExitingHandlerFixture($this->container);

        $beforeHandler->processExiting($this->input, $this->output);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        ProcessExitingMiddlewareChangedFixture::resetCounter();

        $handler = new ProcessExitingHandlerFixture($this->container);

        $handler->add(ProcessExitingMiddlewareChangedFixture::class);
        $handler->processExiting($this->input, $this->output);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, ProcessExitingMiddlewareChangedFixture::getCounter());
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        ProcessExitingMiddlewareChangedFixture::resetCounter();
        ProcessExitingMiddlewareFixture::resetCounter();

        $handler = new ProcessExitingHandlerFixture(
            $this->container,
            ProcessExitingMiddlewareFixture::class
        );

        $handler->add(ProcessExitingMiddlewareChangedFixture::class);
        $handler->processExiting($this->input, $this->output);

        // Only once because the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, ProcessExitingMiddlewareChangedFixture::getCounter());
        self::assertSame(1, ProcessExitingMiddlewareFixture::getCounter());
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        ProcessExitingMiddlewareChangedFixture::resetCounter();
        ProcessExitingMiddlewareFixture::resetCounter();

        $handler = new ProcessExitingHandlerFixture(
            $this->container,
            ProcessExitingMiddlewareFixture::class,
            ProcessExitingMiddlewareFixture::class
        );

        $handler->processExiting($this->input, $this->output);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, ProcessExitingMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, ProcessExitingMiddlewareFixture::getAndResetCounter());
    }
}
