<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Uri\Throwable;

use Throwable;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable as MessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageRuntimeException;
use Valkyrja\Http\Message\Uri\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Uri\Throwable\Exception\Abstract\HttpUriInvalidArgumentException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\Abstract\HttpUriRuntimeException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidQueryException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpMessageThrowable::class);
        self::isA(MessageThrowable::class, HttpMessageThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpMessageThrowable::class, HttpUriInvalidArgumentException::class);
        self::isA(HttpMessageInvalidArgumentException::class, HttpUriInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpMessageThrowable::class, HttpUriRuntimeException::class);
        self::isA(HttpMessageRuntimeException::class, HttpUriRuntimeException::class);
    }

    public function testInvalidDirectoryException(): void
    {
        self::isA(HttpUriInvalidArgumentException::class, HttpUriInvalidPathException::class);
    }

    public function testInvalidPortException(): void
    {
        self::isA(HttpUriInvalidArgumentException::class, HttpUriInvalidPortException::class);
    }

    public function testInvalidQueryException(): void
    {
        self::isA(HttpUriInvalidArgumentException::class, HttpUriInvalidQueryException::class);
    }
}
