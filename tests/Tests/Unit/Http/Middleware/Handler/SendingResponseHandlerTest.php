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
use Valkyrja\Tests\Classes\Http\Middleware\Handler\SendingResponseHandlerClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;

/**
 * Test the sending response handler.
 */
class SendingResponseHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $sendingHandler = new SendingResponseHandlerClass($this->container);

        $sending = $sendingHandler->sendingResponse($this->request, $this->response);

        self::assertSame($this->response, $sending);

        self::assertSame(1, $sendingHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        SendingResponseMiddlewareChangedClass::resetCounter();

        $handler = new SendingResponseHandlerClass($this->container);

        $handler->add(SendingResponseMiddlewareChangedClass::class);
        $sending = $handler->sendingResponse($this->request, $this->response);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, SendingResponseMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->response, $sending);
        self::assertInstanceOf(Response::class, $sending);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        SendingResponseMiddlewareChangedClass::resetCounter();
        SendingResponseMiddlewareClass::resetCounter();

        $handler = new SendingResponseHandlerClass(
            $this->container,
            SendingResponseMiddlewareClass::class
        );

        $handler->add(SendingResponseMiddlewareChangedClass::class);
        $sending = $handler->sendingResponse($this->request, $this->response);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, SendingResponseMiddlewareChangedClass::getCounter());
        self::assertSame(1, SendingResponseMiddlewareClass::getCounter());
        self::assertNotSame($this->response, $sending);
        self::assertInstanceOf(Response::class, $sending);
    }

    /**
     * Test the sending method.
     */
    public function testSending(): void
    {
        SendingResponseMiddlewareChangedClass::resetCounter();
        SendingResponseMiddlewareClass::resetCounter();

        $handler = new SendingResponseHandlerClass(
            $this->container,
            SendingResponseMiddlewareClass::class,
            SendingResponseMiddlewareClass::class
        );

        $sending = $handler->sendingResponse($this->request, $this->response);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, SendingResponseMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, SendingResponseMiddlewareClass::getAndResetCounter());
        self::assertSame($this->response, $sending);
    }
}
