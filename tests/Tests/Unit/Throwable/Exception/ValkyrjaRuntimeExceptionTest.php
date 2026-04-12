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

namespace Valkyrja\Tests\Unit\Throwable\Exception;

use Valkyrja\Tests\Classes\Throwable\Exception\ValkyrjaRuntimeExceptionClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the runtime exception class.
 */
final class ValkyrjaRuntimeExceptionTest extends TestCase
{
    public function testGetTraceCode(): void
    {
        $exception  = new ValkyrjaRuntimeExceptionClass();
        $exception2 = new ValkyrjaRuntimeExceptionClass();
        $exception3 = new ValkyrjaRuntimeExceptionClass('Custom message');

        $traceCode  = $exception->getTraceCode();
        $traceCode2 = $exception2->getTraceCode();
        $traceCode3 = $exception3->getTraceCode();

        self::assertSame($traceCode, $traceCode2);
        self::assertSame($traceCode, $traceCode3);
        self::assertSame($traceCode2, $traceCode3);
    }
}
