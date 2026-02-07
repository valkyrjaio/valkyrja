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

namespace Valkyrja\Tests\Unit\Throwable\Handler\Abstract;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\Exception;
use Valkyrja\Throwable\Handler\Abstract\ThrowableHandler;

/**
 * Test the abstract throwable handler.
 */
final class ThrowableHandlerTest extends TestCase
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
}
