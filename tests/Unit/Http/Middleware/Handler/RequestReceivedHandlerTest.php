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

use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Tests\Classes\Http\Middleware\Handler\RequestReceivedHandlerClass;
use Valkyrja\Tests\Classes\Http\Middleware\RequestReceivedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\RequestReceivedMiddlewareClass;

/**
 * Test the request received handler.
 */
class RequestReceivedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new RequestReceivedHandlerClass($this->container);

        $before = $beforeHandler->requestReceived($this->request);

        self::assertSame($this->request, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        RequestReceivedMiddlewareChangedClass::resetCounter();

        $handler = new RequestReceivedHandlerClass($this->container);

        $handler->add(RequestReceivedMiddlewareChangedClass::class);
        $before = $handler->requestReceived($this->request);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, RequestReceivedMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->request, $before);
        self::assertInstanceOf(Response::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        RequestReceivedMiddlewareChangedClass::resetCounter();
        RequestReceivedMiddlewareClass::resetCounter();

        $handler = new RequestReceivedHandlerClass(
            $this->container,
            RequestReceivedMiddlewareClass::class
        );

        $handler->add(RequestReceivedMiddlewareChangedClass::class);
        $before = $handler->requestReceived($this->request);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, RequestReceivedMiddlewareChangedClass::getCounter());
        self::assertSame(1, RequestReceivedMiddlewareClass::getCounter());
        self::assertNotSame($this->request, $before);
        self::assertInstanceOf(Response::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        RequestReceivedMiddlewareChangedClass::resetCounter();
        RequestReceivedMiddlewareClass::resetCounter();

        $handler = new RequestReceivedHandlerClass(
            $this->container,
            RequestReceivedMiddlewareClass::class,
            RequestReceivedMiddlewareClass::class
        );

        $before = $handler->requestReceived($this->request);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, RequestReceivedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, RequestReceivedMiddlewareClass::getAndResetCounter());
        self::assertSame($this->request, $before);
    }
}
