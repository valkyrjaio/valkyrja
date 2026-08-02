<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Message\Stream\Factory;

use RuntimeException;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Factory\StreamFactory;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamInvalidStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamStreamReadException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamStreamSeekException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamStreamTellException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamStreamWriteException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamUnreadableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamUnseekableStreamException;
use Valkyrja\Http\Message\Stream\Throwable\Exception\HttpStreamUnwritableStreamException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function fclose;
use function fopen;
use function get_resource_type;
use function is_resource;

final class StreamFactoryTest extends TestCase
{
    public function testGetResourceStreamReturnsValidStreamResource(): void
    {
        $resource = StreamFactory::getResourceStream(PhpWrapper::temp, Mode::WRITE_READ);

        self::assertTrue(is_resource($resource));
        self::assertSame('stream', get_resource_type($resource));
    }

    public function testGetResourceStreamAcceptsStringStream(): void
    {
        $resource = StreamFactory::getResourceStream('php://temp', Mode::WRITE_READ);

        self::assertTrue(is_resource($resource));
        self::assertSame('stream', get_resource_type($resource));
    }

    public function testIsModeWriteable(): void
    {
        self::assertTrue(StreamFactory::isModeWriteable('x'));
        self::assertTrue(StreamFactory::isModeWriteable('w'));
        self::assertTrue(StreamFactory::isModeWriteable('c'));
        self::assertTrue(StreamFactory::isModeWriteable('a'));
        self::assertTrue(StreamFactory::isModeWriteable('r+'));
        self::assertFalse(StreamFactory::isModeWriteable('r'));
    }

    public function testIsModeReadable(): void
    {
        self::assertTrue(StreamFactory::isModeReadable('r'));
        self::assertTrue(StreamFactory::isModeReadable('w+'));
        self::assertFalse(StreamFactory::isModeReadable('w'));
    }

    public function testToStringReturnsEmptyForUnreadableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isReadable')->willReturn(false);

        self::assertSame('', StreamFactory::toString($stream));
    }

    public function testToStringReturnsContentsForReadableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isReadable')->willReturn(true);
        $stream->expects($this->once())->method('rewind');
        $stream->expects($this->once())->method('getContents')->willReturn('contents');

        self::assertSame('contents', StreamFactory::toString($stream));
    }

    public function testToStringReturnsEmptyWhenReadingThrows(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isReadable')->willReturn(true);
        $stream->expects($this->once())->method('rewind')->willThrowException(new RuntimeException('boom'));

        self::assertSame('', StreamFactory::toString($stream));
    }

    public function testVerifyWritablePassesForWritableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isWritable')->willReturn(true);

        StreamFactory::verifyWritable($stream);

        self::assertTrue(true);
    }

    public function testVerifyWritableThrowsForUnwritableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isWritable')->willReturn(false);

        $this->expectException(HttpStreamUnwritableStreamException::class);

        StreamFactory::verifyWritable($stream);
    }

    public function testVerifyWriteResultPassesForInt(): void
    {
        StreamFactory::verifyWriteResult(5);

        self::assertTrue(true);
    }

    public function testVerifyWriteResultThrowsForFalse(): void
    {
        $this->expectException(HttpStreamStreamWriteException::class);

        StreamFactory::verifyWriteResult(false);
    }

    public function testVerifySeekablePassesForSeekableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isSeekable')->willReturn(true);

        StreamFactory::verifySeekable($stream);

        self::assertTrue(true);
    }

    public function testVerifySeekableThrowsForUnseekableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isSeekable')->willReturn(false);

        $this->expectException(HttpStreamUnseekableStreamException::class);

        StreamFactory::verifySeekable($stream);
    }

    public function testVerifySeekResultPassesForZero(): void
    {
        StreamFactory::verifySeekResult(0);

        self::assertTrue(true);
    }

    public function testVerifySeekResultThrowsForNonZero(): void
    {
        $this->expectException(HttpStreamStreamSeekException::class);

        StreamFactory::verifySeekResult(1);
    }

    public function testVerifyReadablePassesForReadableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isReadable')->willReturn(true);

        StreamFactory::verifyReadable($stream);

        self::assertTrue(true);
    }

    public function testVerifyReadableThrowsForUnreadableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isReadable')->willReturn(false);

        $this->expectException(HttpStreamUnreadableStreamException::class);

        StreamFactory::verifyReadable($stream);
    }

    public function testVerifyReadResultPassesForString(): void
    {
        StreamFactory::verifyReadResult('data');

        self::assertTrue(true);
    }

    public function testVerifyReadResultThrowsForFalse(): void
    {
        $this->expectException(HttpStreamStreamReadException::class);

        StreamFactory::verifyReadResult(false);
    }

    public function testVerifyTellResultPassesForInt(): void
    {
        StreamFactory::verifyTellResult(10);

        self::assertTrue(true);
    }

    public function testVerifyTellResultThrowsForFalse(): void
    {
        $this->expectException(HttpStreamStreamTellException::class);

        StreamFactory::verifyTellResult(false);
    }

    public function testValidateStreamPassesForValidResource(): void
    {
        $resource = fopen('php://temp', 'w+');

        StreamFactory::validateStream($resource);

        self::assertTrue(true);

        if (is_resource($resource)) {
            fclose($resource);
        }
    }

    public function testValidateStreamThrowsForNonResource(): void
    {
        $this->expectException(HttpStreamInvalidStreamException::class);

        StreamFactory::validateStream('not-a-stream');
    }
}
