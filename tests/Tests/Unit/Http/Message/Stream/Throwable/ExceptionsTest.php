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

namespace Valkyrja\Tests\Unit\Http\Message\Stream\Throwable;

use Throwable;
use Valkyrja\Http\Message\Stream\Throwable\Contract\HttpStreamThrowable;
use Valkyrja\Http\Message\Stream\Throwable\Exception\Abstract\HttpStreamInvalidArgumentException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\Abstract\HttpStreamRuntimeException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamInvalidStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamStreamReadException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamStreamSeekException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamStreamTellException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamStreamWriteException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamUnreadableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamUnseekableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamUnwritableStreamException;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpStreamThrowable::class);
        self::isA(HttpMessageThrowable::class, HttpStreamThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpStreamThrowable::class, HttpStreamInvalidArgumentException::class);
        self::isA(HttpMessageInvalidArgumentException::class, HttpStreamInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpStreamThrowable::class, HttpStreamRuntimeException::class);
        self::isA(HttpMessageRuntimeException::class, HttpStreamRuntimeException::class);
    }

    public function testInvalidStreamException(): void
    {
        self::isA(HttpStreamInvalidArgumentException::class, HttpStreamInvalidStreamException::class);
    }

    public function testStreamReadException(): void
    {
        self::isA(HttpStreamRuntimeException::class, HttpStreamStreamReadException::class);
    }

    public function testStreamSeekException(): void
    {
        self::isA(HttpStreamRuntimeException::class, HttpStreamStreamSeekException::class);
    }

    public function testStreamTellException(): void
    {
        self::isA(HttpStreamRuntimeException::class, HttpStreamStreamTellException::class);
    }

    public function testStreamWriteException(): void
    {
        self::isA(HttpStreamRuntimeException::class, HttpStreamStreamWriteException::class);
    }

    public function testUnreadableStreamException(): void
    {
        self::isA(HttpStreamRuntimeException::class, HttpStreamUnreadableStreamException::class);
    }

    public function testUnseekableStreamException(): void
    {
        self::isA(HttpStreamRuntimeException::class, HttpStreamUnseekableStreamException::class);
    }

    public function testUnwritableStreamException(): void
    {
        self::isA(HttpStreamRuntimeException::class, HttpStreamUnwritableStreamException::class);
    }
}
