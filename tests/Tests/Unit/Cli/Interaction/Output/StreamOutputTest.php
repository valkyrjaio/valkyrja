<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Output;

use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\StreamOutput;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionStreamWriteException;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Output\StreamOutputFalseFwriteFixture;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Output\StreamOutputShortFwriteFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function fopen;
use function ob_get_clean;
use function ob_start;
use function rewind;
use function stream_get_contents;

/**
 * Test the StreamOutput class.
 */
final class StreamOutputTest extends TestCase
{
    public function testOutputMessage(): void
    {
        $text    = 'text';
        $message = new Message($text);
        $stream  = $this->createStream();

        $output = new StreamOutput($stream)
            ->withAddedMessage($message);

        ob_start();
        $outputWritten = $output->writeMessages();
        $contents      = ob_get_clean();

        rewind($stream);

        self::assertSame([$message], $outputWritten->getMessages());
        self::assertCount(1, $outputWritten->getWrittenMessages());
        self::assertEmpty($outputWritten->getUnwrittenMessages());
        self::assertTrue($outputWritten->hasWrittenMessage());
        self::assertFalse($outputWritten->hasUnwrittenMessage());
        self::assertEmpty($contents);
        self::assertSame($message->getFormattedText(), stream_get_contents($stream));
    }

    public function testOutputMessageAppends(): void
    {
        $first  = new Message('first');
        $second = new Message('second');
        $stream = $this->createStream();

        $output = new StreamOutput($stream)
            ->withAddedMessages($first, $second);

        ob_start();
        $output->writeMessages();
        ob_get_clean();

        rewind($stream);

        self::assertSame(
            $first->getFormattedText() . $second->getFormattedText(),
            stream_get_contents($stream)
        );
    }

    public function testOutputMessageWritesTheFormattedText(): void
    {
        $stream = $this->createStream();

        $output = new StreamOutput($stream)
            ->withAddedMessage(new SuccessMessage('text'));

        ob_start();
        $output->writeMessages();
        ob_get_clean();

        rewind($stream);

        self::assertSame("\e[97;42mtext\e[39;49m", stream_get_contents($stream));
    }

    public function testOutputMessageThrowsWhenTheWriteFails(): void
    {
        $output = new StreamOutputFalseFwriteFixture($this->createStream())
            ->withAddedMessage(new Message('text'));

        $this->expectException(CliInteractionStreamWriteException::class);
        $this->expectExceptionMessage('Unable to write to the stream');

        $output->writeMessages();
    }

    public function testOutputMessageThrowsWhenTheWriteIsShort(): void
    {
        $output = new StreamOutputShortFwriteFixture($this->createStream())
            ->withAddedMessage(new Message('text'));

        $this->expectException(CliInteractionStreamWriteException::class);
        $this->expectExceptionMessage('Unable to write to the stream');

        $output->writeMessages();
    }

    public function testStream(): void
    {
        $stream  = $this->createStream();
        $stream2 = $this->createStream();

        $output  = (new StreamOutput($stream));
        $output2 = $output->withStream($stream2);

        self::assertNotSame($output, $output2);
        self::assertSame($stream, $output->getStream());
        self::assertSame($stream2, $output2->getStream());
    }

    /**
     * @return resource
     */
    private function createStream()
    {
        $stream = fopen(filename: 'php://memory', mode: 'wb+');

        self::assertNotFalse($stream);

        return $stream;
    }
}
