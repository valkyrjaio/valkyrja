<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Header\Throwable;

use Throwable;
use Valkyrja\Http\Message\Header\Throwable\Contract\HttpHeaderThrowable;
use Valkyrja\Http\Message\Header\Throwable\Exception\Abstract\HttpHeaderInvalidArgumentException;
use Valkyrja\Http\Message\Header\Throwable\Exception\Abstract\HttpHeaderRuntimeException;
use Valkyrja\Http\Message\Header\Throwable\Exception\HttpHeaderInvalidNameException;
use Valkyrja\Http\Message\Header\Throwable\Exception\HttpHeaderInvalidValueException;
use Valkyrja\Http\Message\Header\Throwable\Exception\HttpHeaderUnsupportedMethodException;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpHeaderThrowable::class);
        self::isA(HttpMessageThrowable::class, HttpHeaderThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpHeaderThrowable::class, HttpHeaderInvalidArgumentException::class);
        self::isA(HttpMessageInvalidArgumentException::class, HttpHeaderInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpHeaderThrowable::class, HttpHeaderRuntimeException::class);
        self::isA(HttpMessageRuntimeException::class, HttpHeaderRuntimeException::class);
    }

    public function testUnsupportedMethodException(): void
    {
        self::isA(HttpHeaderRuntimeException::class, HttpHeaderUnsupportedMethodException::class);
    }

    public function testInvalidNameException(): void
    {
        self::isA(HttpHeaderInvalidArgumentException::class, HttpHeaderInvalidNameException::class);
    }

    public function testInvalidValueException(): void
    {
        self::isA(HttpHeaderInvalidArgumentException::class, HttpHeaderInvalidValueException::class);
    }
}
