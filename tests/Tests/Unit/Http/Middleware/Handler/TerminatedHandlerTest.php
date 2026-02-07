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

use Valkyrja\Tests\Classes\Http\Middleware\Handler\TerminatedHandlerClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;

/**
 * Test the terminated handler.
 */
class TerminatedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $terminatedHandler = new TerminatedHandlerClass($this->container);

        $terminatedHandler->terminated($this->request, $this->response);

        self::assertSame(1, $terminatedHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        TerminatedMiddlewareChangedClass::resetCounter();

        $handler = new TerminatedHandlerClass($this->container);

        $handler->add(TerminatedMiddlewareChangedClass::class);
        $handler->terminated($this->request, $this->response);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, TerminatedMiddlewareChangedClass::getCounter());
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        TerminatedMiddlewareChangedClass::resetCounter();
        TerminatedMiddlewareClass::resetCounter();

        $handler = new TerminatedHandlerClass(
            $this->container,
            TerminatedMiddlewareClass::class
        );

        $handler->add(TerminatedMiddlewareChangedClass::class);
        $handler->terminated($this->request, $this->response);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, TerminatedMiddlewareChangedClass::getCounter());
        self::assertSame(1, TerminatedMiddlewareClass::getCounter());
    }

    /**
     * Test the terminated method.
     */
    public function testTerminated(): void
    {
        TerminatedMiddlewareChangedClass::resetCounter();
        TerminatedMiddlewareClass::resetCounter();

        $handler = new TerminatedHandlerClass(
            $this->container,
            TerminatedMiddlewareClass::class,
            TerminatedMiddlewareClass::class
        );

        $handler->terminated($this->request, $this->response);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, TerminatedMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, TerminatedMiddlewareClass::getAndResetCounter());
    }
}
