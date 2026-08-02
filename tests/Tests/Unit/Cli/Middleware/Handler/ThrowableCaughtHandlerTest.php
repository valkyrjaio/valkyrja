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

use Exception;
use Override;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Handler\ThrowableCaughtHandlerFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ThrowableCaughtMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ThrowableCaughtMiddlewareFixture;

/**
 * Test the throwable caught handler.
 */
final class ThrowableCaughtHandlerTest extends HandlerTestCase
{
    protected Exception $exception;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->exception = new Exception('Test exception');
    }

    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new ThrowableCaughtHandlerFixture($this->container);

        $before = $beforeHandler->throwableCaught($this->input, $this->output, $this->exception);

        self::assertSame($this->output, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        ThrowableCaughtMiddlewareChangedFixture::resetCounter();

        $handler = new ThrowableCaughtHandlerFixture($this->container);

        $handler->add(ThrowableCaughtMiddlewareChangedFixture::class);
        $before = $handler->throwableCaught($this->input, $this->output, $this->exception);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, ThrowableCaughtMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        ThrowableCaughtMiddlewareChangedFixture::resetCounter();
        ThrowableCaughtMiddlewareFixture::resetCounter();

        $handler = new ThrowableCaughtHandlerFixture(
            $this->container,
            ThrowableCaughtMiddlewareFixture::class
        );

        $handler->add(ThrowableCaughtMiddlewareChangedFixture::class);
        $before = $handler->throwableCaught($this->input, $this->output, $this->exception);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, ThrowableCaughtMiddlewareChangedFixture::getCounter());
        self::assertSame(1, ThrowableCaughtMiddlewareFixture::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        ThrowableCaughtMiddlewareChangedFixture::resetCounter();
        ThrowableCaughtMiddlewareFixture::resetCounter();

        $handler = new ThrowableCaughtHandlerFixture(
            $this->container,
            ThrowableCaughtMiddlewareFixture::class,
            ThrowableCaughtMiddlewareFixture::class
        );

        $before = $handler->throwableCaught($this->input, $this->output, $this->exception);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, ThrowableCaughtMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, ThrowableCaughtMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->output, $before);
    }
}
