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

use ReflectionClass;
use Valkyrja\Sms\Throwable\Contract\SmsThrowable;
use Valkyrja\Sms\Throwable\Exception\Abstract\SmsInvalidArgumentException;
use Valkyrja\Sms\Throwable\Exception\Abstract\SmsRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(new ReflectionClass(SmsThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(new ReflectionClass(SmsRuntimeException::class)->isSubclassOf(SmsThrowable::class));
        self::assertTrue(new ReflectionClass(SmsInvalidArgumentException::class)->isSubclassOf(SmsThrowable::class));
    }
}
