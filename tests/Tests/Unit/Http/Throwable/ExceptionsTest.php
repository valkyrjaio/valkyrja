<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Throwable;

use Throwable;
use Valkyrja\Http\Throwable\Contract\HttpThrowable;
use Valkyrja\Http\Throwable\Exception\Abstract\HttpInvalidArgumentException;
use Valkyrja\Http\Throwable\Exception\Abstract\HttpRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpThrowable::class);
        self::isA(ValkyrjaThrowable::class, HttpThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpThrowable::class, HttpInvalidArgumentException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, HttpInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpThrowable::class, HttpRuntimeException::class);
        self::isA(ValkyrjaRuntimeException::class, HttpRuntimeException::class);
    }
}
