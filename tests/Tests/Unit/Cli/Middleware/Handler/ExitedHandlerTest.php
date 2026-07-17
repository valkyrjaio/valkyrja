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

namespace Valkyrja\Tests\Unit\Cli\Middleware\Handler;

use Valkyrja\Tests\Fixtures\Cli\Middleware\ExitedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ExitedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Handler\ExitedHandlerFixture;

/**
 * Test the exited handler.
 */
final class ExitedHandlerTest extends HandlerTestCase
{
    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new ExitedHandlerFixture($this->container);

        $beforeHandler->exited($this->input, $this->output);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        ExitedMiddlewareChangedFixture::resetCounter();

        $handler = new ExitedHandlerFixture($this->container);

        $handler->add(ExitedMiddlewareChangedFixture::class);
        $handler->exited($this->input, $this->output);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, ExitedMiddlewareChangedFixture::getCounter());
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        ExitedMiddlewareChangedFixture::resetCounter();
        ExitedMiddlewareFixture::resetCounter();

        $handler = new ExitedHandlerFixture(
            $this->container,
            ExitedMiddlewareFixture::class
        );

        $handler->add(ExitedMiddlewareChangedFixture::class);
        $handler->exited($this->input, $this->output);

        // Only once because the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, ExitedMiddlewareChangedFixture::getCounter());
        self::assertSame(1, ExitedMiddlewareFixture::getCounter());
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        ExitedMiddlewareChangedFixture::resetCounter();
        ExitedMiddlewareFixture::resetCounter();

        $handler = new ExitedHandlerFixture(
            $this->container,
            ExitedMiddlewareFixture::class,
            ExitedMiddlewareFixture::class
        );

        $handler->exited($this->input, $this->output);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, ExitedMiddlewareChangedFixture::getAndResetCounter());
        self::assertSame(2, ExitedMiddlewareFixture::getAndResetCounter());
    }
}
