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

use Exception;
use Override;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Tests\Classes\Cli\Middleware\Handler\ThrowableCaughtHandlerClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ThrowableCaughtMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ThrowableCaughtMiddlewareClass;

/**
 * Test the throwable caught handler.
 */
class ThrowableCaughtHandlerTest extends HandlerTestCase
{
    protected Exception $exception;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->exception = new Exception('Test exception');
    }

    /**
     * Test with the default middleware (empty arrays).
     */
    public function testWithDefaults(): void
    {
        $beforeHandler = new ThrowableCaughtHandlerClass($this->container);

        $before = $beforeHandler->throwableCaught($this->input, $this->output, $this->exception);

        self::assertSame($this->output, $before);

        self::assertSame(1, $beforeHandler->getCount());
    }

    /**
     * Test the add method.
     */
    public function testAddWithDefault(): void
    {
        ThrowableCaughtMiddlewareChangedClass::resetCounter();

        $handler = new ThrowableCaughtHandlerClass($this->container);

        $handler->add(ThrowableCaughtMiddlewareChangedClass::class);
        $before = $handler->throwableCaught($this->input, $this->output, $this->exception);

        // Only once because the last iteration that checks for null nextMiddleware doesn't run because the middleware
        // exits early and doesn't call the handler
        self::assertSame(1, $handler->getCount());
        self::assertSame(1, ThrowableCaughtMiddlewareChangedClass::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the add method.
     */
    public function testAdd(): void
    {
        ThrowableCaughtMiddlewareChangedClass::resetCounter();
        ThrowableCaughtMiddlewareClass::resetCounter();

        $handler = new ThrowableCaughtHandlerClass(
            $this->container,
            ThrowableCaughtMiddlewareClass::class
        );

        $handler->add(ThrowableCaughtMiddlewareChangedClass::class);
        $before = $handler->throwableCaught($this->input, $this->output, $this->exception);

        // One time for each middleware and not once for the last iteration that checks for null nextMiddleware because
        // the last middleware exits early and doesn't call the handler
        self::assertSame(2, $handler->getCount());
        self::assertSame(1, ThrowableCaughtMiddlewareChangedClass::getCounter());
        self::assertSame(1, ThrowableCaughtMiddlewareClass::getCounter());
        self::assertNotSame($this->output, $before);
        self::assertInstanceOf(OutputContract::class, $before);
    }

    /**
     * Test the before method.
     */
    public function testBefore(): void
    {
        ThrowableCaughtMiddlewareChangedClass::resetCounter();
        ThrowableCaughtMiddlewareClass::resetCounter();

        $handler = new ThrowableCaughtHandlerClass(
            $this->container,
            ThrowableCaughtMiddlewareClass::class,
            ThrowableCaughtMiddlewareClass::class
        );

        $before = $handler->throwableCaught($this->input, $this->output, $this->exception);

        // One time for each middleware and once for the last iteration that checks for null nextMiddleware
        self::assertSame(3, $handler->getCount());
        self::assertSame(0, ThrowableCaughtMiddlewareChangedClass::getAndResetCounter());
        self::assertSame(2, ThrowableCaughtMiddlewareClass::getAndResetCounter());
        self::assertSame($this->output, $before);
    }
}
