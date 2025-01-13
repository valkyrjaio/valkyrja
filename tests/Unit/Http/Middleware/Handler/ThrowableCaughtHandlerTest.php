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
use Valkyrja\Tests\Classes\Http\Middleware\Handler\TestThrowableCaughtHandler;
use Valkyrja\Tests\Classes\Http\Middleware\TestThrowableCaughtMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestThrowableCaughtMiddlewareChanged;

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

        $handler = new TestThrowableCaughtHandler($this->container);

        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        self::assertSame($this->response, $exceptionResponse);

        self::assertSame(1, $handler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        TestThrowableCaughtMiddlewareChanged::resetCounter();

        $exception = new Exception();

        $handler = new TestThrowableCaughtHandler($this->container);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        $handler->add(TestThrowableCaughtMiddlewareChanged::class);
        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        self::assertSame(1, $handler->getCount());
        self::assertSame(1, TestThrowableCaughtMiddlewareChanged::getCounter());
        self::assertNotSame($this->response, $exceptionResponse);
        self::assertInstanceOf(Response::class, $exceptionResponse);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        TestThrowableCaughtMiddlewareChanged::resetCounter();
        TestThrowableCaughtMiddleware::resetCounter();

        $exception = new Exception();

        $handler = new TestThrowableCaughtHandler(
            $this->container,
            TestThrowableCaughtMiddleware::class
        );

        $handler->add(TestThrowableCaughtMiddlewareChanged::class);
        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, TestThrowableCaughtMiddlewareChanged::getCounter());
        self::assertSame(1, TestThrowableCaughtMiddleware::getCounter());
        self::assertNotSame($this->response, $exceptionResponse);
        self::assertInstanceOf(Response::class, $exceptionResponse);
    }

    /**
     * Test the exception method.
     */
    public function testException(): void
    {
        TestThrowableCaughtMiddlewareChanged::resetCounter();
        TestThrowableCaughtMiddleware::resetCounter();

        $exception = new Exception();

        $handler = new TestThrowableCaughtHandler(
            $this->container,
            TestThrowableCaughtMiddleware::class,
            TestThrowableCaughtMiddleware::class
        );

        $exceptionResponse = $handler->throwableCaught($this->request, $this->response, $exception);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, TestThrowableCaughtMiddlewareChanged::getAndResetCounter());
        self::assertSame(2, TestThrowableCaughtMiddleware::getAndResetCounter());
        self::assertSame($this->response, $exceptionResponse);
    }
}
