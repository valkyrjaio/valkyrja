<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Broadcast\Throwable;

use ReflectionClass;
use Valkyrja\Broadcast\Throwable\Contract\BroadcastThrowable;
use Valkyrja\Broadcast\Throwable\Exception\Abstract\BroadcastInvalidArgumentException;
use Valkyrja\Broadcast\Throwable\Exception\Abstract\BroadcastRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(new ReflectionClass(BroadcastThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(new ReflectionClass(BroadcastRuntimeException::class)->isSubclassOf(BroadcastThrowable::class));
        self::assertTrue(new ReflectionClass(BroadcastInvalidArgumentException::class)->isSubclassOf(BroadcastThrowable::class));
    }
}
