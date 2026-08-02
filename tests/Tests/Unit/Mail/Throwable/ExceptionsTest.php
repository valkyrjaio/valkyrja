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

use Valkyrja\Mail\Throwable\Contract\MailThrowable;
use Valkyrja\Mail\Throwable\Exception\Abstract\MailInvalidArgumentException;
use Valkyrja\Mail\Throwable\Exception\Abstract\MailRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

use function is_a;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(MailThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(MailRuntimeException::class, MailThrowable::class, true));
        self::assertTrue(is_a(MailInvalidArgumentException::class, MailThrowable::class, true));
    }
}
