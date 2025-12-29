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

namespace Valkyrja\Tests\Unit\Exception;

use Valkyrja\Exception\Exception;
use Valkyrja\Exception\Handler\ExceptionHandler;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the exception handler.
 *
 * @author Melech Mizrachi
 */
class ExceptionHandlerTest extends TestCase
{
    public function testGetTraceCode(): void
    {
        $exception  = new Exception();
        $exception2 = new Exception();
        $exception3 = new Exception('Custom message');

        $traceCode  = ExceptionHandler::getTraceCode($exception);
        $traceCode2 = ExceptionHandler::getTraceCode($exception2);
        $traceCode3 = ExceptionHandler::getTraceCode($exception3);

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

        ExceptionHandler::$enabled = false;

        ExceptionHandler::enable();

        $whoopsExceptionHandler = set_exception_handler(null);
        $whoopsErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        self::assertTrue(ExceptionHandler::$enabled);
        self::assertNotSame($originalExceptionHandler, $whoopsExceptionHandler);
        self::assertNotSame($originalErrorHandler, $whoopsErrorHandler);

        // Testing calling enable again, shouldn't remake the handler
        ExceptionHandler::enable();

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

        ExceptionHandler::$enabled = false;

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

        ExceptionHandler::enable();

        $whoopsExceptionHandler = set_exception_handler(null);
        $whoopsErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        self::assertTrue(ExceptionHandler::$enabled);
        self::assertNotSame($originalExceptionHandler, $whoopsExceptionHandler);
        self::assertNotSame($originalErrorHandler, $whoopsErrorHandler);

        // Testing calling enable again, shouldn't remake the handler
        ExceptionHandler::enable();

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
