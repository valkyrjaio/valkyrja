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
use Valkyrja\Tests\Classes\Cli\Middleware\CommandDispatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\CommandDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\CommandDispatchedHandlerClass;

/**
 * Test the command dispatched handler.
 */
class CommandDispatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new CommandDispatchedHandlerClass($this->container);

        $before = $beforeHandler->commandDispatched($this->input, $this->output, $this->command);

        self::assertSame($this->output, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        CommandDispatchedMiddlewareChangedClass::resetCounter();

        $handler = new CommandDispatchedHandlerClass($this->container);

        $handler->add(CommandDispatchedMiddlewareChangedClass::class);
        $before = $handler->commandDispatched($this->input, $this->output, $this->command);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, CommandDispatchedMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        CommandDispatchedMiddlewareChangedClass::resetCounter();
        CommandDispatchedMiddlewareClass::resetCounter();

        $handler = new CommandDispatchedHandlerClass(
            $this->container,
            CommandDispatchedMiddlewareClass::class
        );

        $handler->add(CommandDispatchedMiddlewareChangedClass::class);
        $before = $handler->commandDispatched($this->input, $this->output, $this->command);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, CommandDispatchedMiddlewareChangedClass::getCounter());
        self::assertSame(1, CommandDispatchedMiddlewareClass::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        CommandDispatchedMiddlewareChangedClass::resetCounter();
        CommandDispatchedMiddlewareClass::resetCounter();

        $handler = new CommandDispatchedHandlerClass(
            $this->container,
            CommandDispatchedMiddlewareClass::class,
            CommandDispatchedMiddlewareClass::class
        );

        $before = $handler->commandDispatched($this->input, $this->output, $this->command);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, CommandDispatchedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, CommandDispatchedMiddlewareClass::getAndResetCounter());
        self::assertSame($this->output, $before);
    }
}
