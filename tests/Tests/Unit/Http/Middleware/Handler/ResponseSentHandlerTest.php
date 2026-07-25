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

use Valkyrja\Tests\Fixtures\Http\Middleware\Handler\ResponseSentHandlerFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ResponseSentMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ResponseSentMiddlewareFixture;

/**
 * Test the response sent handler.
 */
final class ResponseSentHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $responseSentHandler = new ResponseSentHandlerFixture($this->container);

        $responseSentHandler->responseSent($this->request, $this->response);

        self::assertSame(1, $responseSentHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        ResponseSentMiddlewareChangedFixture::resetCounter();

        $handler = new ResponseSentHandlerFixture($this->container);

        $handler->add(ResponseSentMiddlewareChangedFixture::class);
        $handler->responseSent($this->request, $this->response);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, ResponseSentMiddlewareChangedFixture::getCounter());
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        ResponseSentMiddlewareChangedFixture::resetCounter();
        ResponseSentMiddlewareFixture::resetCounter();

        $handler = new ResponseSentHandlerFixture(
            $this->container,
            ResponseSentMiddlewareFixture::class
        );

        $handler->add(ResponseSentMiddlewareChangedFixture::class);
        $handler->responseSent($this->request, $this->response);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, ResponseSentMiddlewareChangedFixture::getCounter());
        self::assertSame(1, ResponseSentMiddlewareFixture::getCounter());
    }

    /**
     * Test the response sent method.
     */
    public function testResponseSent(): void
    {
        ResponseSentMiddlewareChangedFixture::resetCounter();
        ResponseSentMiddlewareFixture::resetCounter();

        $handler = new ResponseSentHandlerFixture(
            $this->container,
            ResponseSentMiddlewareFixture::class,
            ResponseSentMiddlewareFixture::class
        );

        $handler->responseSent($this->request, $this->response);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, ResponseSentMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, ResponseSentMiddlewareFixture::getAndResetCounter());
    }
}
