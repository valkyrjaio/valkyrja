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

use ReflectionClass;
use Valkyrja\Jwt\Throwable\Contract\JwtThrowable;
use Valkyrja\Jwt\Throwable\Exception\Abstract\JwtInvalidArgumentException;
use Valkyrja\Jwt\Throwable\Exception\Abstract\JwtRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(new ReflectionClass(JwtThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(new ReflectionClass(JwtRuntimeException::class)->isSubclassOf(JwtThrowable::class));
        self::assertTrue(new ReflectionClass(JwtInvalidArgumentException::class)->isSubclassOf(JwtThrowable::class));
    }
}
