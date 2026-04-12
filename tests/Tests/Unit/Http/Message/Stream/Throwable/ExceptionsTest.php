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
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamInvalidArgumentException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamRuntimeException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamReadException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamSeekException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamTellException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamWriteException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnreadableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnseekableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnwritableStreamException;
use Valkyrja\Http\Message\Throwable\Contract\HttpMessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\HttpMessageRuntimeException;
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
        self::isA(HttpStreamInvalidArgumentException::class, InvalidStreamException::class);
    }

    public function testStreamReadException(): void
    {
        self::isA(HttpStreamRuntimeException::class, StreamReadException::class);
    }

    public function testStreamSeekException(): void
    {
        self::isA(HttpStreamRuntimeException::class, StreamSeekException::class);
    }

    public function testStreamTellException(): void
    {
        self::isA(HttpStreamRuntimeException::class, StreamTellException::class);
    }

    public function testStreamWriteException(): void
    {
        self::isA(HttpStreamRuntimeException::class, StreamWriteException::class);
    }

    public function testUnreadableStreamException(): void
    {
        self::isA(HttpStreamRuntimeException::class, UnreadableStreamException::class);
    }

    public function testUnseekableStreamException(): void
    {
        self::isA(HttpStreamRuntimeException::class, UnseekableStreamException::class);
    }

    public function testUnwritableStreamException(): void
    {
        self::isA(HttpStreamRuntimeException::class, UnwritableStreamException::class);
    }
}
