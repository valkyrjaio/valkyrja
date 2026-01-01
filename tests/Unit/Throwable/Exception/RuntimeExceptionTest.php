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

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Throwable\Exception\RuntimeException;

/**
 * Test the runtime exception class.
 */
class RuntimeExceptionTest extends TestCase
{
    public function testGetTraceCode(): void
    {
        $exception  = new RuntimeException();
        $exception2 = new RuntimeException();
        $exception3 = new RuntimeException('Custom message');

        $traceCode  = $exception->getTraceCode();
        $traceCode2 = $exception2->getTraceCode();
        $traceCode3 = $exception3->getTraceCode();

        self::assertSame($traceCode, $traceCode2);
        self::assertSame($traceCode, $traceCode3);
        self::assertSame($traceCode2, $traceCode3);
    }
}
