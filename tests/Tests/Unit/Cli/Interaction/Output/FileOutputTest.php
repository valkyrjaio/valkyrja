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
use Valkyrja\Cli\Interaction\Output\FileOutput;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_start;

/**
 * Test the FileOutput class.
 */
final class FileOutputTest extends TestCase
{
    public function testOutputMessage(): void
    {
        $text    = 'text';
        $message = new Message($text);

        $output = new FileOutput(__DIR__ . '/../../../../storage/file-output-test.txt')
            ->withAddedMessage($message);

        ob_start();
        $outputWritten = $output->writeMessages();
        $contents      = self::cleanOutputBuffer();

        self::assertSame([$message], $outputWritten->getMessages());
        self::assertCount(1, $outputWritten->getWrittenMessages());
        self::assertEmpty($outputWritten->getUnwrittenMessages());
        self::assertTrue($outputWritten->hasWrittenMessage());
        self::assertFalse($outputWritten->hasUnwrittenMessage());
        self::assertEmpty($contents);
    }

    public function testFilePath(): void
    {
        $filepath  = __DIR__ . '/../../../../storage/file-output-test.txt';
        $filepath2 = __DIR__ . '/../../../../storage/file-output-test2.txt';

        $output  = (new FileOutput($filepath));
        $output2 = $output->withFilepath($filepath2);

        self::assertNotSame($output, $output2);
        self::assertSame($filepath, $output->getFilepath());
        self::assertSame($filepath2, $output2->getFilepath());
    }
}
