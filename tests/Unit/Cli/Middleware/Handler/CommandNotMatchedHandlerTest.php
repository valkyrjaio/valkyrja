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
use Valkyrja\Tests\Classes\Cli\Middleware\CommandNotMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\CommandNotMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\CommandNotMatchedHandlerClass;

/**
 * Test the command not matched handler.
 */
class CommandNotMatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new CommandNotMatchedHandlerClass($this->container);

        $before = $beforeHandler->commandNotMatched($this->input, $this->output);

        self::assertSame($this->output, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        CommandNotMatchedMiddlewareChangedClass::resetCounter();

        $handler = new CommandNotMatchedHandlerClass($this->container);

        $handler->add(CommandNotMatchedMiddlewareChangedClass::class);
        $before = $handler->commandNotMatched($this->input, $this->output);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, CommandNotMatchedMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        CommandNotMatchedMiddlewareChangedClass::resetCounter();
        CommandNotMatchedMiddlewareClass::resetCounter();

        $handler = new CommandNotMatchedHandlerClass(
            $this->container,
            CommandNotMatchedMiddlewareClass::class
        );

        $handler->add(CommandNotMatchedMiddlewareChangedClass::class);
        $before = $handler->commandNotMatched($this->input, $this->output);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, CommandNotMatchedMiddlewareChangedClass::getCounter());
        self::assertSame(1, CommandNotMatchedMiddlewareClass::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        CommandNotMatchedMiddlewareChangedClass::resetCounter();
        CommandNotMatchedMiddlewareClass::resetCounter();

        $handler = new CommandNotMatchedHandlerClass(
            $this->container,
            CommandNotMatchedMiddlewareClass::class,
            CommandNotMatchedMiddlewareClass::class
        );

        $before = $handler->commandNotMatched($this->input, $this->output);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, CommandNotMatchedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, CommandNotMatchedMiddlewareClass::getAndResetCounter());
        self::assertSame($this->output, $before);
    }
}
