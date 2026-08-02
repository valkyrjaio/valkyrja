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
use Valkyrja\Tests\Fixtures\Http\Middleware\Handler\SendingResponseHandlerFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\SendingResponseMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\SendingResponseMiddlewareFixture;

/**
 * Test the sending response handler.
 */
final class SendingResponseHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $sendingHandler = new SendingResponseHandlerFixture($this->container);

        $sending = $sendingHandler->sendingResponse($this->request, $this->response);

        self::assertSame($this->response, $sending);

        self::assertSame(1, $sendingHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        SendingResponseMiddlewareChangedFixture::resetCounter();

        $handler = new SendingResponseHandlerFixture($this->container);

        $handler->add(SendingResponseMiddlewareChangedFixture::class);
        $sending = $handler->sendingResponse($this->request, $this->response);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, SendingResponseMiddlewareChangedFixture::getCounter());
        self::assertNotSame($this->response, $sending);
        self::assertInstanceOf(Response::class, $sending);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        SendingResponseMiddlewareChangedFixture::resetCounter();
        SendingResponseMiddlewareFixture::resetCounter();

        $handler = new SendingResponseHandlerFixture(
            $this->container,
            SendingResponseMiddlewareFixture::class
        );

        $handler->add(SendingResponseMiddlewareChangedFixture::class);
        $sending = $handler->sendingResponse($this->request, $this->response);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, SendingResponseMiddlewareChangedFixture::getCounter());
        self::assertSame(1, SendingResponseMiddlewareFixture::getCounter());
        self::assertNotSame($this->response, $sending);
        self::assertInstanceOf(Response::class, $sending);
    }

    /**
     * Test the sending method.
     */
    public function testSending(): void
    {
        SendingResponseMiddlewareChangedFixture::resetCounter();
        SendingResponseMiddlewareFixture::resetCounter();

        $handler = new SendingResponseHandlerFixture(
            $this->container,
            SendingResponseMiddlewareFixture::class,
            SendingResponseMiddlewareFixture::class
        );

        $sending = $handler->sendingResponse($this->request, $this->response);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, SendingResponseMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, SendingResponseMiddlewareFixture::getAndResetCounter());
        self::assertSame($this->response, $sending);
    }
}
