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

use Valkyrja\Tests\Classes\Cli\Middleware\ExitedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ExitedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\ExitedHandlerClass;

/**
 * Test the exited handler.
 */
final class ExitedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new ExitedHandlerClass($this->container);

        $beforeHandler->exited($this->input, $this->output);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        ExitedMiddlewareChangedClass::resetCounter();

        $handler = new ExitedHandlerClass($this->container);

        $handler->add(ExitedMiddlewareChangedClass::class);
        $handler->exited($this->input, $this->output);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, ExitedMiddlewareChangedClass::getCounter());
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        ExitedMiddlewareChangedClass::resetCounter();
        ExitedMiddlewareClass::resetCounter();

        $handler = new ExitedHandlerClass(
            $this->container,
            ExitedMiddlewareClass::class
        );

        $handler->add(ExitedMiddlewareChangedClass::class);
        $handler->exited($this->input, $this->output);

        // Only once because the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, ExitedMiddlewareChangedClass::getCounter());
        self::assertSame(1, ExitedMiddlewareClass::getCounter());
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        ExitedMiddlewareChangedClass::resetCounter();
        ExitedMiddlewareClass::resetCounter();

        $handler = new ExitedHandlerClass(
            $this->container,
            ExitedMiddlewareClass::class,
            ExitedMiddlewareClass::class
        );

        $handler->exited($this->input, $this->output);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, ExitedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, ExitedMiddlewareClass::getAndResetCounter());
    }
}
