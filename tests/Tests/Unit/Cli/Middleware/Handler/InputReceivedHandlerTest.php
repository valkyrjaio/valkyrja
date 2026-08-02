<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Middleware\Handler;

use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Handler\InputReceivedHandlerFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\InputReceivedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\InputReceivedMiddlewareFixture;

/**
 * Test the input received handler.
 */
final class InputReceivedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new InputReceivedHandlerFixture($this->container);

        $before = $beforeHandler->inputReceived($this->input);

        self::assertSame($this->input, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        InputReceivedMiddlewareChangedFixture::resetCounter();

        $handler = new InputReceivedHandlerFixture($this->container);

        $handler->add(InputReceivedMiddlewareChangedFixture::class);
        $before = $handler->inputReceived($this->input);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, InputReceivedMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->input, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        InputReceivedMiddlewareChangedFixture::resetCounter();
        InputReceivedMiddlewareFixture::resetCounter();

        $handler = new InputReceivedHandlerFixture(
            $this->container,
            InputReceivedMiddlewareFixture::class
        );

        $handler->add(InputReceivedMiddlewareChangedFixture::class);
        $before = $handler->inputReceived($this->input);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, InputReceivedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, InputReceivedMiddlewareFixture::getCounter());
        self::assertNotSame($this->input, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        InputReceivedMiddlewareChangedFixture::resetCounter();
        InputReceivedMiddlewareFixture::resetCounter();

        $handler = new InputReceivedHandlerFixture(
            $this->container,
            InputReceivedMiddlewareFixture::class,
            InputReceivedMiddlewareFixture::class
        );

        $before = $handler->inputReceived($this->input);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, InputReceivedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, InputReceivedMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->input, $before);
    }
}
