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

use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Throwable;
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
use function get_resource_type;

final class StreamFactoryTest extends TestCase
{
    /**
     * @return array<string, array{int|false, class-string<Throwable>|null}>
     */
    public static function writeResultProvider(): array
    {
        return [
            'bytes written'   => [5, null],
            'nothing written' => [0, null],
            'write failed'    => [false, HttpStreamStreamWriteException::class],
        ];
    }

    /**
     * @return array<string, array{int, class-string<Throwable>|null}>
     */
    public static function seekResultProvider(): array
    {
        return [
            'seeked'      => [0, null],
            'seek failed' => [1, HttpStreamStreamSeekException::class],
        ];
    }

    /**
     * @return array<string, array{string|false, class-string<Throwable>|null}>
     */
    public static function readResultProvider(): array
    {
        return [
            'data read'    => ['data', null],
            'nothing read' => ['', null],
            'read failed'  => [false, HttpStreamStreamReadException::class],
        ];
    }

    /**
     * @return array<string, array{int|false, class-string<Throwable>|null}>
     */
    public static function tellResultProvider(): array
    {
        return [
            'position told' => [10, null],
            'at the start'  => [0, null],
            'tell failed'   => [false, HttpStreamStreamTellException::class],
        ];
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function invalidStreamProvider(): array
    {
        return [
            'string' => ['not-a-stream'],
            'int'    => [1],
            'null'   => [null],
            'array'  => [[]],
        ];
    }

    public function testGetResourceStreamReturnsValidStreamResource(): void
    {
        $resource = StreamFactory::getResourceStream(PhpWrapper::temp, Mode::WRITE_READ);

        self::assertSame('stream', get_resource_type($resource));
    }

    public function testGetResourceStreamAcceptsStringStream(): void
    {
        $resource = StreamFactory::getResourceStream('php://temp', Mode::WRITE_READ);

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
    }

    public function testVerifyWritableThrowsForUnwritableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isWritable')->willReturn(false);

        $this->expectException(HttpStreamUnwritableStreamException::class);

        StreamFactory::verifyWritable($stream);
    }

    /**
     * @param class-string<Throwable>|null $expectedException
     */
    #[DataProvider('writeResultProvider')]
    public function testVerifyWriteResult(int|false $result, string|null $expectedException): void
    {
        $this->expectGuardOutcome($expectedException);

        StreamFactory::verifyWriteResult($result);
    }

    public function testVerifySeekablePassesForSeekableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isSeekable')->willReturn(true);

        StreamFactory::verifySeekable($stream);
    }

    public function testVerifySeekableThrowsForUnseekableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isSeekable')->willReturn(false);

        $this->expectException(HttpStreamUnseekableStreamException::class);

        StreamFactory::verifySeekable($stream);
    }

    /**
     * @param class-string<Throwable>|null $expectedException
     */
    #[DataProvider('seekResultProvider')]
    public function testVerifySeekResult(int $result, string|null $expectedException): void
    {
        $this->expectGuardOutcome($expectedException);

        StreamFactory::verifySeekResult($result);
    }

    public function testVerifyReadablePassesForReadableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isReadable')->willReturn(true);

        StreamFactory::verifyReadable($stream);
    }

    public function testVerifyReadableThrowsForUnreadableStream(): void
    {
        $stream = $this->createMock(StreamContract::class);
        $stream->expects($this->once())->method('isReadable')->willReturn(false);

        $this->expectException(HttpStreamUnreadableStreamException::class);

        StreamFactory::verifyReadable($stream);
    }

    /**
     * @param class-string<Throwable>|null $expectedException
     */
    #[DataProvider('readResultProvider')]
    public function testVerifyReadResult(string|false $result, string|null $expectedException): void
    {
        $this->expectGuardOutcome($expectedException);

        StreamFactory::verifyReadResult($result);
    }

    /**
     * @param class-string<Throwable>|null $expectedException
     */
    #[DataProvider('tellResultProvider')]
    public function testVerifyTellResult(int|false $result, string|null $expectedException): void
    {
        $this->expectGuardOutcome($expectedException);

        StreamFactory::verifyTellResult($result);
    }

    public function testValidateStreamPassesForValidResource(): void
    {
        $resource = self::openStream('php://temp', 'w+');

        /** @psalm-suppress RedundantConditionGivenDocblockType The guard must accept a valid resource without throwing. */
        StreamFactory::validateStream($resource);

        $this->expectNotToPerformAssertions();

        fclose($resource);
    }

    #[DataProvider('invalidStreamProvider')]
    public function testValidateStreamThrowsForNonResource(mixed $resource): void
    {
        $this->expectException(HttpStreamInvalidStreamException::class);

        StreamFactory::validateStream($resource);
    }

    /**
     * Expect the guard under test to either throw or do nothing at all.
     *
     * @param class-string<Throwable>|null $expectedException
     */
    private function expectGuardOutcome(string|null $expectedException): void
    {
        if ($expectedException !== null) {
            $this->expectException($expectedException);

            return;
        }

        $this->expectNotToPerformAssertions();
    }
}
