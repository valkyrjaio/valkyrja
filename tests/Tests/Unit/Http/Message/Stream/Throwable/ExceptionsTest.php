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

use Throwable as PHPThrowable;
use Valkyrja\Http\Message\Stream\Throwable\Contract\Throwable;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\RuntimeException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamReadException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamSeekException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamTellException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\StreamWriteException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnreadableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnseekableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\UnwritableStreamException;
use Valkyrja\Http\Message\Throwable\Contract\Throwable as MessageThrowable;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException as MessageInvalidArgumentException;
use Valkyrja\Http\Message\Throwable\Exception\RuntimeException as MessageRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(MessageThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(MessageInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(MessageRuntimeException::class, RuntimeException::class);
    }

    public function testInvalidStreamException(): void
    {
        self::isA(InvalidArgumentException::class, InvalidStreamException::class);
    }

    public function testStreamReadException(): void
    {
        self::isA(RuntimeException::class, StreamReadException::class);
    }

    public function testStreamSeekException(): void
    {
        self::isA(RuntimeException::class, StreamSeekException::class);
    }

    public function testStreamTellException(): void
    {
        self::isA(RuntimeException::class, StreamTellException::class);
    }

    public function testStreamWriteException(): void
    {
        self::isA(RuntimeException::class, StreamWriteException::class);
    }

    public function testUnreadableStreamException(): void
    {
        self::isA(RuntimeException::class, UnreadableStreamException::class);
    }

    public function testUnseekableStreamException(): void
    {
        self::isA(RuntimeException::class, UnseekableStreamException::class);
    }

    public function testUnwritableStreamException(): void
    {
        self::isA(RuntimeException::class, UnwritableStreamException::class);
    }
}
