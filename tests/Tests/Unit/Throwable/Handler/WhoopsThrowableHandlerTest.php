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

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Handler\WhoopsThrowableHandler;

/**
 * Test the whoops throwable handler.
 */
final class WhoopsThrowableHandlerTest extends TestCase
{
    public function testEnable(): void
    {
        $originalExceptionHandler = set_exception_handler(null);
        $originalErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        WhoopsThrowableHandler::$enabled = false;

        WhoopsThrowableHandler::enable();

        $whoopsExceptionHandler = set_exception_handler(null);
        $whoopsErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        self::assertTrue(WhoopsThrowableHandler::$enabled);
        self::assertNotSame($originalExceptionHandler, $whoopsExceptionHandler);
        self::assertNotSame($originalErrorHandler, $whoopsErrorHandler);

        // Testing calling enable again, shouldn't remake the handler
        WhoopsThrowableHandler::enable();

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

        WhoopsThrowableHandler::$enabled = false;

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

        WhoopsThrowableHandler::enable();

        $whoopsExceptionHandler = set_exception_handler(null);
        $whoopsErrorHandler     = set_error_handler(null);
        restore_exception_handler();
        restore_error_handler();

        self::assertTrue(WhoopsThrowableHandler::$enabled);
        self::assertNotSame($originalExceptionHandler, $whoopsExceptionHandler);
        self::assertNotSame($originalErrorHandler, $whoopsErrorHandler);

        // Testing calling enable again, shouldn't remake the handler
        WhoopsThrowableHandler::enable();

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
