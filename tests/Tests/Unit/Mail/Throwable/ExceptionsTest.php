<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Mail\Throwable;

use ReflectionClass;
use Valkyrja\Mail\Throwable\Contract\MailThrowable;
use Valkyrja\Mail\Throwable\Exception\Abstract\MailInvalidArgumentException;
use Valkyrja\Mail\Throwable\Exception\Abstract\MailRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(new ReflectionClass(MailThrowable::class)->isSubclassOf(ValkyrjaThrowable::class));
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(new ReflectionClass(MailRuntimeException::class)->isSubclassOf(MailThrowable::class));
        self::assertTrue(new ReflectionClass(MailInvalidArgumentException::class)->isSubclassOf(MailThrowable::class));
    }
}
