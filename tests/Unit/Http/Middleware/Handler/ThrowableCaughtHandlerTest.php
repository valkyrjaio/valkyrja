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

use Valkyrja\Exception\Exception;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Tests\Classes\Http\Middleware\Handler\ThrowableCaughtHandlerClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;

/**
 * Test the throwable caught handler.
 *
 * @author Melech Mizrachi
 */
class ThrowableCaughtHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $exception = new Exception();

        $handler = new ThrowableCaughtHandlerClass($this->container);

        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        self::assertSame($this->response, $exceptionResponse);

        self::assertSame(1, $handler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        ThrowableCaughtMiddlewareChangedClass::resetCounter();

        $exception = new Exception();

        $handler = new ThrowableCaughtHandlerClass($this->container);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        $handler->add(ThrowableCaughtMiddlewareChangedClass::class);
        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        self::assertSame(1, $handler->getCount());
        self::assertSame(1, ThrowableCaughtMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->response, $exceptionResponse);
        self::assertInstanceOf(Response::class, $exceptionResponse);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        ThrowableCaughtMiddlewareChangedClass::resetCounter();
        ThrowableCaughtMiddlewareClass::resetCounter();

        $exception = new Exception();

        $handler = new ThrowableCaughtHandlerClass(
            $this->container,
            ThrowableCaughtMiddlewareClass::class
        );

        $handler->add(ThrowableCaughtMiddlewareChangedClass::class);
        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, ThrowableCaughtMiddlewareChangedClass::getCounter());
        self::assertSame(1, ThrowableCaughtMiddlewareClass::getCounter());
        self::assertNotSame($this->response, $exceptionResponse);
        self::assertInstanceOf(Response::class, $exceptionResponse);
    }

    /**
     * Test the exception method.
     */
    public function testException(): void
    {
        ThrowableCaughtMiddlewareChangedClass::resetCounter();
        ThrowableCaughtMiddlewareClass::resetCounter();

        $exception = new Exception();

        $handler = new ThrowableCaughtHandlerClass(
            $this->container,
            ThrowableCaughtMiddlewareClass::class,
            ThrowableCaughtMiddlewareClass::class
        );

        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, ThrowableCaughtMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, ThrowableCaughtMiddlewareClass::getAndResetCounter());
        self::assertSame($this->response, $exceptionResponse);
    }
}
