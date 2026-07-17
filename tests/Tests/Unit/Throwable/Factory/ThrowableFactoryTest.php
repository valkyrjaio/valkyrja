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

namespace Valkyrja\Tests\Unit\Throwable\Factory;

use Valkyrja\Tests\Fixtures\Throwable\Exception\ValkyrjaRuntimeExceptionFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Factory\ThrowableFactory;

final class ThrowableFactoryTest extends TestCase
{
    public function testGetTraceCode(): void
    {
        $exception  = new ValkyrjaRuntimeExceptionFixture();
        $exception2 = new ValkyrjaRuntimeExceptionFixture();
        $exception3 = new ValkyrjaRuntimeExceptionFixture('Custom message');

        $traceCode  = ThrowableFactory::getTraceCode($exception);
        $traceCode2 = ThrowableFactory::getTraceCode($exception2);
        $traceCode3 = ThrowableFactory::getTraceCode($exception3);

        self::assertSame($traceCode, $traceCode2);
        self::assertSame($traceCode, $traceCode3);
        self::assertSame($traceCode2, $traceCode3);
    }
}
