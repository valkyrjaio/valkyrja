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

use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Tests\Classes\Cli\Middleware\CommandMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\CommandMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\CommandMatchedHandlerClass;

/**
 * Test the command matched handler.
 *
 * @author Melech Mizrachi
 */
class CommandMatchedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new CommandMatchedHandlerClass($this->container);

        $before = $beforeHandler->commandMatched($this->input, $this->command);

        self::assertSame($this->command, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        CommandMatchedMiddlewareChangedClass::resetCounter();

        $handler = new CommandMatchedHandlerClass($this->container);

        $handler->add(CommandMatchedMiddlewareChangedClass::class);
        $before = $handler->commandMatched($this->input, $this->command);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, CommandMatchedMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->command, $before);
        self::assertInstanceOf(Output::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        CommandMatchedMiddlewareChangedClass::resetCounter();
        CommandMatchedMiddlewareClass::resetCounter();

        $handler = new CommandMatchedHandlerClass(
            $this->container,
            CommandMatchedMiddlewareClass::class
        );

        $handler->add(CommandMatchedMiddlewareChangedClass::class);
        $before = $handler->commandMatched($this->input, $this->command);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, CommandMatchedMiddlewareChangedClass::getCounter());
        self::assertSame(1, CommandMatchedMiddlewareClass::getCounter());
        self::assertNotSame($this->command, $before);
        self::assertInstanceOf(Output::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        CommandMatchedMiddlewareChangedClass::resetCounter();
        CommandMatchedMiddlewareClass::resetCounter();

        $handler = new CommandMatchedHandlerClass(
            $this->container,
            CommandMatchedMiddlewareClass::class,
            CommandMatchedMiddlewareClass::class
        );

        $before = $handler->commandMatched($this->input, $this->command);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, CommandMatchedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, CommandMatchedMiddlewareClass::getAndResetCounter());
        self::assertSame($this->command, $before);
    }
}
