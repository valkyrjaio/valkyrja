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

namespace Valkyrja\Tests\Unit\Http\Message\Stream;

use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Enum\PhpWrapper;
use Valkyrja\Http\Message\Stream\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Stream\Exception\NoStreamAvailableException;
use Valkyrja\Http\Message\Stream\Exception\StreamReadException;
use Valkyrja\Http\Message\Stream\Exception\StreamSeekException;
use Valkyrja\Http\Message\Stream\Exception\StreamTellException;
use Valkyrja\Http\Message\Stream\Exception\StreamWriteException;
use Valkyrja\Http\Message\Stream\Exception\UnreadableStreamException;
use Valkyrja\Http\Message\Stream\Exception\UnseekableStreamException;
use Valkyrja\Http\Message\Stream\Exception\UnwritableStreamException;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Tests\Classes\Http\Message\Stream\StreamReadExceptionClass;
use Valkyrja\Tests\Classes\Http\Message\Stream\StreamSeekExceptionClass;
use Valkyrja\Tests\Classes\Http\Message\Stream\StreamTellExceptionClass;
use Valkyrja\Tests\Classes\Http\Message\Stream\StreamWriteExceptionClass;
use Valkyrja\Tests\Classes\Http\Message\Stream\UnseekableStreamExceptionClass;
use Valkyrja\Tests\Unit\TestCase;

class StreamTest extends TestCase
{
    public function testIsSeekable(): void
    {
        $stream = new Stream();

        self::assertTrue($stream->isSeekable());

        $stream->detach();

        self::assertFalse($stream->isSeekable());
    }

    public function testSeek(): void
    {
        $stream = new Stream();
        $stream->write('pie');
        $stream->seek(0);

        self::assertSame('pie', $stream->getContents());

        $stream->seek(1);

        self::assertSame('ie', $stream->getContents());
    }

    public function testSeekFailure(): void
    {
        $this->expectException(StreamSeekException::class);

        $stream = new StreamSeekExceptionClass();
        $stream->seek(1);
    }

    public function testUnSeekFailure(): void
    {
        $this->expectException(UnseekableStreamException::class);

        $stream = new UnseekableStreamExceptionClass();
        $stream->seek(1);
    }

    public function testIsReadable(): void
    {
        $stream = new Stream();

        self::assertTrue($stream->isReadable());

        $stream->detach();

        self::assertFalse($stream->isReadable());
    }

    public function testRead(): void
    {
        $stream = new Stream();

        $result = $stream->read(4096);

        self::assertEmpty($result);
    }

    public function testReadFailure(): void
    {
        $this->expectException(StreamReadException::class);

        $stream = new StreamReadExceptionClass();
        $stream->read(4096);
    }

    public function testReadAfterDetach(): void
    {
        $this->expectException(NoStreamAvailableException::class);

        $stream = new Stream();
        $stream->detach();
        $stream->read(4096);
    }

    public function testReadUsingWritableOnlyMode(): void
    {
        $this->expectException(UnreadableStreamException::class);

        $stream = new Stream(PhpWrapper::output);
        $stream->read(4096);
    }

    public function testIsWritable(): void
    {
        $stream = new Stream();

        self::assertTrue($stream->isWritable());

        $stream->detach();

        self::assertFalse($stream->isWritable());
    }

    public function testWrite(): void
    {
        $stream = new Stream();

        $result = $stream->write('pie');

        self::assertGreaterThan(0, $result);
    }

    public function testWriteFailure(): void
    {
        $this->expectException(StreamWriteException::class);

        $stream = new StreamWriteExceptionClass();
        $stream->write('pie');
    }

    public function testWriteAfterDetach(): void
    {
        $this->expectException(NoStreamAvailableException::class);

        $stream = new Stream();
        $stream->detach();
        $stream->write('pie');
    }

    public function testWriteUsingReadableOnlyMode(): void
    {
        $this->expectException(UnwritableStreamException::class);

        $stream = new Stream(PhpWrapper::input);

        $stream->write('pie');
    }

    public function testToString(): void
    {
        $contents = 'pie';

        $stream = new Stream();
        $stream->write($contents);

        self::assertSame($contents, $stream->__toString());

        $stream->seek(2);

        // Regardless of what is read before __toString is called it should always return the whole content of the stream
        self::assertSame($contents, $stream->__toString());

        $stream->eof();

        // Regardless of what is read before __toString is called it should always return the whole content of the stream
        self::assertSame($contents, $stream->__toString());

        $stream->detach();

        // Non-readable stream should return an empty string
        self::assertEmpty($stream->__toString());
    }

    public function testToStringFailureEmptyString(): void
    {
        $contents = 'pie';

        $stream = new StreamReadExceptionClass();
        $stream->write($contents);

        self::assertNotSame($contents, $stream->__toString());
        self::assertEmpty($stream->__toString());
    }

    public function testClose(): void
    {
        $stream = new Stream();

        self::assertTrue($stream->isSeekable());

        $stream->close();

        self::assertFalse($stream->isSeekable());
    }

    public function testCloseInvalidStream(): void
    {
        $stream = new Stream();
        $stream->detach();

        self::assertFalse($stream->isSeekable());

        $stream->close();

        self::assertFalse($stream->isSeekable());
    }

    public function testDetach(): void
    {
        $stream = new Stream();

        self::assertTrue($stream->isSeekable());

        $resource = $stream->detach();

        self::assertFalse($stream->isSeekable());
        self::assertIsResource($resource);
    }

    public function testGetSize(): void
    {
        $stream = new Stream();
        $stream->write('pie');

        self::assertSame(3, $stream->getSize());

        $stream->write('test');

        self::assertSame(7, $stream->getSize());

        $stream->detach();

        self::assertNull($stream->getSize());
    }

    public function testTell(): void
    {
        $stream = new Stream();
        $stream->write('pie');

        self::assertSame(3, $stream->tell());

        $stream->seek(2);

        self::assertSame(2, $stream->tell());

        $stream->getContents();

        self::assertSame(3, $stream->tell());

        $stream->rewind();

        self::assertSame(0, $stream->tell());
    }

    public function testTellFailure(): void
    {
        $this->expectException(StreamTellException::class);

        $stream = new StreamTellExceptionClass();
        $stream->tell();
    }

    public function testTellInvalidStream(): void
    {
        $this->expectException(NoStreamAvailableException::class);

        $stream = new Stream();
        $stream->detach();
        $stream->tell();
    }

    public function testEof(): void
    {
        $contents = 'pie';

        $stream = new Stream();
        $stream->write($contents);

        // self::assertTrue($stream->eof());

        $stream->rewind();

        self::assertFalse($stream->eof());

        self::assertSame($contents, $stream->getContents());

        self::assertTrue($stream->eof());
        self::assertEmpty($stream->getContents());

        $stream->detach();

        self::assertTrue($stream->eof());
    }

    public function testGetContents(): void
    {
        $contents = 'pie';

        $stream = new Stream();
        $stream->write($contents);
        $stream->rewind();

        self::assertSame($contents, $stream->getContents());

        $stream->seek(2);

        self::assertSame('e', $stream->getContents());
    }

    public function testGetContentsFailure(): void
    {
        $this->expectException(StreamReadException::class);

        $stream = new StreamReadExceptionClass();
        $stream->getContents();
    }

    public function testGetContentsNonReadable(): void
    {
        $this->expectException(UnreadableStreamException::class);

        $stream = new Stream();
        $stream->detach();
        $stream->getContents();
    }

    public function testGetMetadata(): void
    {
        $stream = new Stream();

        self::assertIsArray($stream->getMetadata());
        self::assertTrue($stream->getMetadata('seekable'));
        self::assertNull($stream->getMetadata('nonexistent'));

        $stream->detach();

        self::assertNull($stream->getMetadata());
        self::assertNull($stream->getMetadata('seekable'));
    }

    public function testInvalidStream(): void
    {
        $this->expectException(InvalidStreamException::class);

        @new Stream('/non-existent', Mode::READ);
    }
}
