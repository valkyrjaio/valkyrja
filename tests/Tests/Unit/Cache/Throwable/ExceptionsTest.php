<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cache\Throwable;

use ReflectionClass;
use Valkyrja\Cache\Throwable\Contract\CacheThrowable;
use Valkyrja\Cache\Throwable\Exception\Abstract\CacheInvalidArgumentException;
use Valkyrja\Cache\Throwable\Exception\Abstract\CacheRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(new ReflectionClass(CacheThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(new ReflectionClass(CacheRuntimeException::class)->isSubclassOf(CacheThrowable::class));
        self::assertTrue(new ReflectionClass(CacheInvalidArgumentException::class)->isSubclassOf(CacheThrowable::class));
    }
}
