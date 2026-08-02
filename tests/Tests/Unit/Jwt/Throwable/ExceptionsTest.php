<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Jwt\Throwable;

use Valkyrja\Jwt\Throwable\Contract\JwtThrowable;
use Valkyrja\Jwt\Throwable\Exception\Abstract\JwtInvalidArgumentException;
use Valkyrja\Jwt\Throwable\Exception\Abstract\JwtRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

use function is_a;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(JwtThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(JwtRuntimeException::class, JwtThrowable::class, true));
        self::assertTrue(is_a(JwtInvalidArgumentException::class, JwtThrowable::class, true));
    }
}
