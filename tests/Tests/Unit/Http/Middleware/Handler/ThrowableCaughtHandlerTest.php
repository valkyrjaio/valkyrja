<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Middleware\Handler;

use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Tests\Fixtures\Http\Middleware\Handler\ThrowableCaughtHandlerFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ThrowableCaughtMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ThrowableCaughtMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Throwable\Exception\ValkyrjaRuntimeExceptionFixture;

/**
 * Test the throwable caught handler.
 */
final class ThrowableCaughtHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $exception = new ValkyrjaRuntimeExceptionFixture();

        $handler = new ThrowableCaughtHandlerFixture($this->container);

        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        self::assertSame($this->response, $exceptionResponse);

        self::assertSame(1, $handler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        ThrowableCaughtMiddlewareChangedFixture::resetCounter();

        $exception = new ValkyrjaRuntimeExceptionFixture();

        $handler = new ThrowableCaughtHandlerFixture($this->container);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        $handler->add(ThrowableCaughtMiddlewareChangedFixture::class);
        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        self::assertSame(1, $handler->getCount());
        self::assertSame(1, ThrowableCaughtMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->response, $exceptionResponse);
        self::assertInstanceOf(Response::class, $exceptionResponse);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        ThrowableCaughtMiddlewareChangedFixture::resetCounter();
        ThrowableCaughtMiddlewareFixture::resetCounter();

        $exception = new ValkyrjaRuntimeExceptionFixture();

        $handler = new ThrowableCaughtHandlerFixture(
            $this->container,
            ThrowableCaughtMiddlewareFixture::class
        );

        $handler->add(ThrowableCaughtMiddlewareChangedFixture::class);
        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, ThrowableCaughtMiddlewareChangedFixture::getCounter());
        self::assertSame(1, ThrowableCaughtMiddlewareFixture::getCounter());
        self::assertNotSame($this->response, $exceptionResponse);
        self::assertInstanceOf(Response::class, $exceptionResponse);
    }

    /**
     * Test the exception method.
     */
    public function testException(): void
    {
        ThrowableCaughtMiddlewareChangedFixture::resetCounter();
        ThrowableCaughtMiddlewareFixture::resetCounter();

        $exception = new ValkyrjaRuntimeExceptionFixture();

        $handler = new ThrowableCaughtHandlerFixture(
            $this->container,
            ThrowableCaughtMiddlewareFixture::class,
            ThrowableCaughtMiddlewareFixture::class
        );

        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, ThrowableCaughtMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, ThrowableCaughtMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->response, $exceptionResponse);
    }
}
