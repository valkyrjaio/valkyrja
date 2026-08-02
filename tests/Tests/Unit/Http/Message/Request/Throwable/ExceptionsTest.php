<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Request\Throwable;

use Throwable;
use Valkyrja\Http\Message\Request\Throwable\Contract\HttpRequestThrowable;
use Valkyrja\Http\Message\Request\Throwable\Exception\Abstract\HttpRequestInvalidArgumentException;
use Valkyrja\Http\Message\Request\Throwable\Exception\Abstract\HttpRequestRuntimeException;
use Valkyrja\Http\Message\Request\Throwable\Exception\HttpRequestInvalidMethodException;
use Valkyrja\Http\Message\Request\Throwable\Exception\HttpRequestInvalidRequestTargetException;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpRequestThrowable::class);
        self::isA(HttpMessageThrowable::class, HttpRequestThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpRequestThrowable::class, HttpRequestInvalidArgumentException::class);
        self::isA(HttpMessageInvalidArgumentException::class, HttpRequestInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpRequestThrowable::class, HttpRequestRuntimeException::class);
        self::isA(HttpMessageRuntimeException::class, HttpRequestRuntimeException::class);
    }

    public function testInvalidRequestTargetException(): void
    {
        self::isA(HttpRequestInvalidArgumentException::class, HttpRequestInvalidRequestTargetException::class);
    }

    public function testInvalidMethodException(): void
    {
        self::isA(HttpRequestInvalidArgumentException::class, HttpRequestInvalidMethodException::class);
    }
}
