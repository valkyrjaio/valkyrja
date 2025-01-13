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

namespace Valkyrja\Tests\Unit\Http\Message\Stream\Psr;

use Valkyrja\Http\Message\Stream;
use Valkyrja\Tests\Unit\TestCase;

class StreamTest extends TestCase
{
    public function testPsr(): void
    {
        $content   = 'pie';
        $psrStream = new Stream\Psr\Stream(
            $stream = new Stream\Stream()
        );

        $psrStream->write($content);

        self::assertSame($stream->__toString(), $psrStream->__toString());
        self::assertSame($stream->getSize(), $psrStream->getSize());
        self::assertSame($stream->tell(), $psrStream->tell());
        self::assertSame($stream->eof(), $psrStream->eof());
        self::assertSame($stream->isSeekable(), $psrStream->isSeekable());
        self::assertSame($stream->isWritable(), $psrStream->isWritable());
        self::assertSame($stream->isReadable(), $psrStream->isReadable());
        self::assertSame($stream->getMetadata(), $psrStream->getMetadata());
        self::assertSame($stream->read(2), $psrStream->read(2));

        $psrStream->rewind();
        $psrContents = $psrStream->getContents();
        $stream->rewind();
        $contents = $psrStream->getContents();

        self::assertSame($contents, $psrContents);

        $psrStream->seek(2);
        $psrContents = $psrStream->getContents();
        $stream->seek(2);
        $contents = $psrStream->getContents();

        self::assertSame($contents, $psrContents);

        $stream->write($content);

        self::assertSame($stream->__toString(), $psrStream->__toString());
        self::assertSame($stream->getSize(), $psrStream->getSize());
        self::assertSame($stream->tell(), $psrStream->tell());
        self::assertSame($stream->eof(), $psrStream->eof());
        self::assertSame($stream->isSeekable(), $psrStream->isSeekable());
        self::assertSame($stream->isWritable(), $psrStream->isWritable());
        self::assertSame($stream->isReadable(), $psrStream->isReadable());
        self::assertSame($stream->getMetadata(), $psrStream->getMetadata());
        self::assertSame($stream->read(2), $psrStream->read(2));

        $psrStream->close();
        $psrStream->detach();
    }
}
