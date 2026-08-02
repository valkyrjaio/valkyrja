<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Sms\Throwable;

use Valkyrja\Sms\Throwable\Contract\SmsThrowable;
use Valkyrja\Sms\Throwable\Exception\Abstract\SmsInvalidArgumentException;
use Valkyrja\Sms\Throwable\Exception\Abstract\SmsRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

use function is_a;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(SmsThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(SmsRuntimeException::class, SmsThrowable::class, true));
        self::assertTrue(is_a(SmsInvalidArgumentException::class, SmsThrowable::class, true));
    }
}
