<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Crypt\Throwable;

use ReflectionClass;
use Valkyrja\Crypt\Throwable\Contract\CryptThrowable;
use Valkyrja\Crypt\Throwable\Exception\Abstract\CryptInvalidArgumentException;
use Valkyrja\Crypt\Throwable\Exception\Abstract\CryptRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(new ReflectionClass(CryptThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(new ReflectionClass(CryptRuntimeException::class)->isSubclassOf(CryptThrowable::class));
        self::assertTrue(new ReflectionClass(CryptInvalidArgumentException::class)->isSubclassOf(CryptThrowable::class));
    }
}
