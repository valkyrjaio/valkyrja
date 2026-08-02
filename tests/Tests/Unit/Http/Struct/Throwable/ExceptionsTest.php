<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Struct\Throwable;

use Throwable;
use Valkyrja\Http\Struct\Throwable\Contract\HttpStructThrowable;
use Valkyrja\Http\Struct\Throwable\Exception\Abstract\HttpStructInvalidArgumentException;
use Valkyrja\Http\Struct\Throwable\Exception\Abstract\HttpStructRuntimeException;
use Valkyrja\Http\Throwable\Contract\HttpThrowable;
use Valkyrja\Http\Throwable\Exception\Abstract\HttpInvalidArgumentException;
use Valkyrja\Http\Throwable\Exception\Abstract\HttpRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpStructThrowable::class);
        self::isA(HttpThrowable::class, HttpStructThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpStructThrowable::class, HttpStructInvalidArgumentException::class);
        self::isA(HttpInvalidArgumentException::class, HttpStructInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpStructThrowable::class, HttpStructRuntimeException::class);
        self::isA(HttpRuntimeException::class, HttpStructRuntimeException::class);
    }
}
