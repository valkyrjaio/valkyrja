<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
