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

use Valkyrja\Application\Directory\Directory;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\StreamOutput;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function fopen;
use function ob_get_clean;
use function ob_start;

/**
 * Test the FileOutput class.
 */
final class StreamOutputTest extends TestCase
{
    public function testOutputMessage(): void
    {
        $text    = 'text';
        $message = new Message($text);

        $output = new StreamOutput(fopen(filename: Directory::storagePath('stream-output-test.txt'), mode: 'wrb'))
            ->withAddedMessage($message);

        ob_start();
        $outputWritten = $output->writeMessages();
        $contents      = ob_get_clean();

        self::assertSame([$message], $outputWritten->getMessages());
        self::assertCount(1, $outputWritten->getWrittenMessages());
        self::assertEmpty($outputWritten->getUnwrittenMessages());
        self::assertTrue($outputWritten->hasWrittenMessage());
        self::assertFalse($outputWritten->hasUnwrittenMessage());
        self::assertEmpty($contents);
    }

    public function testFilePath(): void
    {
        $stream  = fopen(filename: Directory::storagePath('stream-output-test.txt'), mode: 'wrb');
        $stream2 = fopen(filename: Directory::storagePath('stream-output-test2.txt'), mode: 'wrb');

        $output  = (new StreamOutput($stream));
        $output2 = $output->withStream($stream2);

        self::assertNotSame($output, $output2);
        self::assertSame($stream, $output->getStream());
        self::assertSame($stream2, $output2->getStream());
    }
}
