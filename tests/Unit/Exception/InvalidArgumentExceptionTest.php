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

use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the invalid argument exception class.
 *
 * @author Melech Mizrachi
 */
class InvalidArgumentExceptionTest extends TestCase
{
    public function testGetTraceCode(): void
    {
        $exception  = new InvalidArgumentException();
        $exception2 = new InvalidArgumentException();
        $exception3 = new InvalidArgumentException('Custom message');

        $traceCode  = $exception->getTraceCode();
        $traceCode2 = $exception2->getTraceCode();
        $traceCode3 = $exception3->getTraceCode();

        self::assertSame($traceCode, $traceCode2);
        self::assertSame($traceCode, $traceCode3);
        self::assertSame($traceCode2, $traceCode3);
    }
}
