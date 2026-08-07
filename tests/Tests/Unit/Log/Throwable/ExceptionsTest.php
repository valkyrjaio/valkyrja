<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Log\Throwable;

use ReflectionClass;
use Valkyrja\Log\Throwable\Contract\LogThrowable;
use Valkyrja\Log\Throwable\Exception\Abstract\LogInvalidArgumentException;
use Valkyrja\Log\Throwable\Exception\Abstract\LogRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(new ReflectionClass(LogThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(new ReflectionClass(LogInvalidArgumentException::class)->isSubclassOf(LogThrowable::class));
        self::assertTrue(new ReflectionClass(LogRuntimeException::class)->isSubclassOf(LogThrowable::class));
    }
}
