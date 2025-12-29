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

namespace Valkyrja\Tests\Unit\Throwable\Handler;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Throwable\Exception\Exception;
use Valkyrja\Throwable\Handler\ThrowableHandler;

/**
 * Test the throwable handler.
 *
 * @author Melech Mizrachi
 */
class ThrowableHandlerTest extends TestCase
{
    public function testGetTraceCode(): void
    {
        $exception  = new Exception();
        $exception2 = new Exception();
        $exception3 = new Exception('Custom message');

        $traceCode  = ThrowableHandler::getTraceCode($exception);
        $traceCode2 = ThrowableHandler::getTraceCode($exception2);
        $traceCode3 = ThrowableHandler::getTraceCode($exception3);

        self::assertSame($traceCode, $traceCode2);
        self::assertSame($traceCode, $traceCode3);
        self::assertSame($traceCode2, $traceCode3);
    }

    public function testEnable(): void
    {
        $originalExceptionHandler = set_exception_handler(null);
        $originalErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        ThrowableHandler::$enabled = false;

        ThrowableHandler::enable();

        $whoopsExceptionHandler = set_exception_handler(null);
        $whoopsErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        self::assertTrue(ThrowableHandler::$enabled);
        self::assertNotSame($originalExceptionHandler, $whoopsExceptionHandler);
        self::assertNotSame($originalErrorHandler, $whoopsErrorHandler);

        // Testing calling enable again, shouldn't remake the handler
        ThrowableHandler::enable();

        $whoopsExceptionHandler2 = set_exception_handler(null);
        $whoopsErrorHandler2     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        self::assertSame($whoopsExceptionHandler, $whoopsExceptionHandler2);
        self::assertSame($whoopsErrorHandler, $whoopsErrorHandler2);

        restore_exception_handler();
        restore_error_handler();
    }

    public function testEnableJson(): void
    {
        $originalExceptionHandler = set_exception_handler(null);
        $originalErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        ThrowableHandler::$enabled = false;

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

        ThrowableHandler::enable();

        $whoopsExceptionHandler = set_exception_handler(null);
        $whoopsErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        self::assertTrue(ThrowableHandler::$enabled);
        self::assertNotSame($originalExceptionHandler, $whoopsExceptionHandler);
        self::assertNotSame($originalErrorHandler, $whoopsErrorHandler);

        // Testing calling enable again, shouldn't remake the handler
        ThrowableHandler::enable();

        $whoopsExceptionHandler2 = set_exception_handler(null);
        $whoopsErrorHandler2     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        self::assertSame($whoopsExceptionHandler, $whoopsExceptionHandler2);
        self::assertSame($whoopsErrorHandler, $whoopsErrorHandler2);

        restore_exception_handler();
        restore_error_handler();
    }
}
