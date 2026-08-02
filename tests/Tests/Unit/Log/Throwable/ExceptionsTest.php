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

use Valkyrja\Log\Throwable\Contract\LogThrowable;
use Valkyrja\Log\Throwable\Exception\Abstract\LogInvalidArgumentException;
use Valkyrja\Log\Throwable\Exception\Abstract\LogRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

use function is_a;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(LogThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(LogInvalidArgumentException::class, LogThrowable::class, true));
        self::assertTrue(is_a(LogRuntimeException::class, LogThrowable::class, true));
    }
}
