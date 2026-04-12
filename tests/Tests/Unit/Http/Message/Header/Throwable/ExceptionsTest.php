<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Header\Throwable;

use Throwable;
use Valkyrja\Http\Message\Header\Throwable\Contract\HttpHeaderThrowable;
use Valkyrja\Http\Message\Header\Throwable\Exception\HttpHeaderInvalidArgumentException;
use Valkyrja\Http\Message\Header\Throwable\Exception\HttpHeaderRuntimeException;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidNameException;
use Valkyrja\Http\Message\Header\Throwable\Exception\InvalidValueException;
use Valkyrja\Http\Message\Header\Throwable\Exception\UnsupportedMethodException;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageRuntimeException;
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
        self::isA(HttpHeaderRuntimeException::class, UnsupportedMethodException::class);
    }

    public function testInvalidNameException(): void
    {
        self::isA(HttpHeaderInvalidArgumentException::class, InvalidNameException::class);
    }

    public function testInvalidValueException(): void
    {
        self::isA(HttpHeaderInvalidArgumentException::class, InvalidValueException::class);
    }
}
