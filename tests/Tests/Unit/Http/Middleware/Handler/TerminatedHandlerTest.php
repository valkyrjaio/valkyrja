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

use Valkyrja\Tests\Fixtures\Http\Middleware\Handler\TerminatedHandlerFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\TerminatedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\TerminatedMiddlewareFixture;

/**
 * Test the terminated handler.
 */
final class TerminatedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $terminatedHandler = new TerminatedHandlerFixture($this->container);

        $terminatedHandler->terminated($this->request, $this->response);

        self::assertSame(1, $terminatedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        TerminatedMiddlewareChangedFixture::resetCounter();

        $handler = new TerminatedHandlerFixture($this->container);

        $handler->add(TerminatedMiddlewareChangedFixture::class);
        $handler->terminated($this->request, $this->response);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, TerminatedMiddlewareChangedFixture::getCounter());
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        TerminatedMiddlewareChangedFixture::resetCounter();
        TerminatedMiddlewareFixture::resetCounter();

        $handler = new TerminatedHandlerFixture(
            $this->container,
            TerminatedMiddlewareFixture::class
        );

        $handler->add(TerminatedMiddlewareChangedFixture::class);
        $handler->terminated($this->request, $this->response);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, TerminatedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, TerminatedMiddlewareFixture::getCounter());
    }

    /**
     * Test the terminated method.
     */
    public function testTerminated(): void
    {
        TerminatedMiddlewareChangedFixture::resetCounter();
        TerminatedMiddlewareFixture::resetCounter();

        $handler = new TerminatedHandlerFixture(
            $this->container,
            TerminatedMiddlewareFixture::class,
            TerminatedMiddlewareFixture::class
        );

        $handler->terminated($this->request, $this->response);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, TerminatedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, TerminatedMiddlewareFixture::getAndResetCounter());
    }
}
