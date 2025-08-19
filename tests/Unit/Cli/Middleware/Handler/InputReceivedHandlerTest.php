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
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\InputReceivedHandlerClass;
use Valkyrja\Tests\Classes\Cli\Middleware\InputReceivedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\InputReceivedMiddlewareClass;

/**
 * Test the input received handler.
 *
 * @author Melech Mizrachi
 */
class InputReceivedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new InputReceivedHandlerClass($this->container);

        $before = $beforeHandler->inputReceived($this->input);

        self::assertSame($this->input, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        InputReceivedMiddlewareChangedClass::resetCounter();

        $handler = new InputReceivedHandlerClass($this->container);

        $handler->add(InputReceivedMiddlewareChangedClass::class);
        $before = $handler->inputReceived($this->input);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, InputReceivedMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->input, $before);
        self::assertInstanceOf(Output::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        InputReceivedMiddlewareChangedClass::resetCounter();
        InputReceivedMiddlewareClass::resetCounter();

        $handler = new InputReceivedHandlerClass(
            $this->container,
            InputReceivedMiddlewareClass::class
        );

        $handler->add(InputReceivedMiddlewareChangedClass::class);
        $before = $handler->inputReceived($this->input);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, InputReceivedMiddlewareChangedClass::getCounter());
        self::assertSame(1, InputReceivedMiddlewareClass::getCounter());
        self::assertNotSame($this->input, $before);
        self::assertInstanceOf(Output::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        InputReceivedMiddlewareChangedClass::resetCounter();
        InputReceivedMiddlewareClass::resetCounter();

        $handler = new InputReceivedHandlerClass(
            $this->container,
            InputReceivedMiddlewareClass::class,
            InputReceivedMiddlewareClass::class
        );

        $before = $handler->inputReceived($this->input);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, InputReceivedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, InputReceivedMiddlewareClass::getAndResetCounter());
        self::assertSame($this->input, $before);
    }
}
