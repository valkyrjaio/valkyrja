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

namespace Valkyrja\Tests\Unit\Http\Message\Stream\Factory;

use Valkyrja\Http\Message\Stream\Enum\Mode;
use Valkyrja\Http\Message\Stream\Enum\ModeTranslation;
use Valkyrja\Http\Message\Stream\Factory\StreamFactory;
use Valkyrja\Http\Message\Stream\Psr\Stream as PsrStream;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Tests\Classes\Http\Message\Stream\Psr\StreamEmptyModeClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class StreamFactoryTest extends TestCase
{
    public function testFromPsr(): void
    {
        $stream = new Stream(mode: Mode::READ_WRITE);
        $stream->write('test');
        $stream->rewind();

        $streamBinarySafe = new Stream(mode: Mode::READ_WRITE, modeTranslation: ModeTranslation::BINARY_SAFE);
        $streamBinarySafe->write('binarySafe');
        $streamBinarySafe->rewind();

        $streamNoTranslation = new Stream(mode: Mode::READ_WRITE, modeTranslation: ModeTranslation::NONE);
        $streamNoTranslation->write('noModeTranslation');
        $streamNoTranslation->rewind();

        $streamWindows = new Stream(mode: Mode::READ_WRITE, modeTranslation: ModeTranslation::WINDOWS);
        $streamWindows->write('windows');
        $streamWindows->rewind();

        $psrStream              = new PsrStream($stream);
        $psrStreamNullMode      = new StreamEmptyModeClass();
        $psrStreamBinarySafe    = new PsrStream($streamBinarySafe);
        $psrStreamNoTranslation = new PsrStream($streamNoTranslation);
        $psrStreamWindows       = new PsrStream($streamWindows);

        $streamFromPsr           = StreamFactory::fromPsr($psrStream);
        $streamFromNullMode      = StreamFactory::fromPsr($psrStreamNullMode);
        $streamFromBinarySafe    = StreamFactory::fromPsr($psrStreamBinarySafe);
        $streamFromNoTranslation = StreamFactory::fromPsr($psrStreamNoTranslation);
        $streamFromWindows       = StreamFactory::fromPsr($psrStreamWindows);

        // Doesn't matter what the original mode was because we're taking the content and writing it to a new temp stream
        self::assertSame('w+b', $streamFromPsr->getMetadata('mode'));
        self::assertSame('w+b', $streamFromNullMode->getMetadata('mode'));
        self::assertSame('w+b', $streamFromBinarySafe->getMetadata('mode'));
        self::assertSame('w+b', $streamFromNoTranslation->getMetadata('mode'));
        self::assertSame('w+b', $streamFromWindows->getMetadata('mode'));

        self::assertSame('test', $streamFromPsr->getContents());
        self::assertSame('', $streamFromNullMode->getContents());
        self::assertSame('binarySafe', $streamFromBinarySafe->getContents());
        self::assertSame('noModeTranslation', $streamFromNoTranslation->getContents());
        self::assertSame('windows', $streamFromWindows->getContents());
    }
}
