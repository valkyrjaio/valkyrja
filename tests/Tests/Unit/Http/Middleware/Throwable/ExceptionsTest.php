<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Middleware\Throwable;

use Throwable;
use Valkyrja\Http\Middleware\Throwable\Contract\HttpMiddlewareThrowable;
use Valkyrja\Http\Middleware\Throwable\Exception\Abstract\HttpMiddlewareInvalidArgumentException;
use Valkyrja\Http\Middleware\Throwable\Exception\Abstract\HttpMiddlewareRuntimeException;
use Valkyrja\Http\Throwable\Contract\HttpThrowable;
use Valkyrja\Http\Throwable\Exception\Abstract\HttpRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpMiddlewareThrowable::class);
        self::isA(HttpThrowable::class, HttpMiddlewareThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpMiddlewareThrowable::class, HttpMiddlewareInvalidArgumentException::class);
        self::isA(HttpMiddlewareInvalidArgumentException::class, HttpMiddlewareInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpMiddlewareThrowable::class, HttpMiddlewareRuntimeException::class);
        self::isA(HttpRuntimeException::class, HttpMiddlewareRuntimeException::class);
    }
}
