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

use Valkyrja\Cache\Throwable\Contract\CacheThrowable;
use Valkyrja\Cache\Throwable\Exception\Abstract\CacheInvalidArgumentException;
use Valkyrja\Cache\Throwable\Exception\Abstract\CacheRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

use function is_a;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(CacheThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(CacheRuntimeException::class, CacheThrowable::class, true));
        self::assertTrue(is_a(CacheInvalidArgumentException::class, CacheThrowable::class, true));
    }
}
