<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Throwable\Exception;

use Valkyrja\Tests\Fixtures\Throwable\Exception\ValkyrjaInvalidArgumentExceptionFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the invalid argument exception class.
 */
final class ValkyrjaInvalidArgumentExceptionTest extends TestCase
{
    public function testGetTraceCode(): void
    {
        $exception  = new ValkyrjaInvalidArgumentExceptionFixture();
        $exception2 = new ValkyrjaInvalidArgumentExceptionFixture();
        $exception3 = new ValkyrjaInvalidArgumentExceptionFixture('Custom message');

        $traceCode  = $exception->getTraceCode();
        $traceCode2 = $exception2->getTraceCode();
        $traceCode3 = $exception3->getTraceCode();

        self::assertSame($traceCode, $traceCode2);
        self::assertSame($traceCode, $traceCode3);
        self::assertSame($traceCode2, $traceCode3);
    }
}
