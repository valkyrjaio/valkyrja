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

namespace Valkyrja\Tests\Unit\Throwable\Error;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Throwable\Error\TypeError;

/**
 * Test the type error class.
 *
 * @author Melech Mizrachi
 */
class TypeErrorTest extends TestCase
{
    public function testGetTraceCode(): void
    {
        $exception  = new TypeError();
        $exception2 = new TypeError();
        $exception3 = new TypeError('Custom message');

        $traceCode  = $exception->getTraceCode();
        $traceCode2 = $exception2->getTraceCode();
        $traceCode3 = $exception3->getTraceCode();

        self::assertSame($traceCode, $traceCode2);
        self::assertSame($traceCode, $traceCode3);
        self::assertSame($traceCode2, $traceCode3);
    }
}
