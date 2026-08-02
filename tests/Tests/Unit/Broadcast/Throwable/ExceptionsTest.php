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

use Valkyrja\Broadcast\Throwable\Contract\BroadcastThrowable;
use Valkyrja\Broadcast\Throwable\Exception\Abstract\BroadcastInvalidArgumentException;
use Valkyrja\Broadcast\Throwable\Exception\Abstract\BroadcastRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

use function is_a;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(BroadcastThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(BroadcastRuntimeException::class, BroadcastThrowable::class, true));
        self::assertTrue(is_a(BroadcastInvalidArgumentException::class, BroadcastThrowable::class, true));
    }
}
