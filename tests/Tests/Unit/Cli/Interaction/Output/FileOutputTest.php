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
use Valkyrja\Cli\Interaction\Output\FileOutput;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionUnwritableFileException;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Output\FileOutputFalseFilePutContentsFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function file_get_contents;
use function ob_get_clean;
use function ob_start;

/**
 * Test the FileOutput class.
 */
final class FileOutputTest extends TestCase
{
    public function testOutputMessage(): void
    {
        $text     = 'text';
        $message  = new Message($text);
        $filepath = $this->getFilepath();

        $output = new FileOutput($filepath)
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
        self::assertSame($message->getFormattedText(), file_get_contents($filepath));
    }

    public function testOutputMessageAppends(): void
    {
        $first    = new Message('first');
        $second   = new Message('second');
        $filepath = $this->getFilepath();

        $output = new FileOutput($filepath)
            ->withAddedMessages($first, $second);

        ob_start();
        $output->writeMessages();
        ob_get_clean();

        self::assertSame(
            $first->getFormattedText() . $second->getFormattedText(),
            file_get_contents($filepath)
        );
    }

    public function testOutputMessageThrowsWhenTheFileIsUnwritable(): void
    {
        $filepath = $this->getFilepath();

        $output = new FileOutputFalseFilePutContentsFixture($filepath)
            ->withAddedMessage(new Message('text'));

        $this->expectException(CliInteractionUnwritableFileException::class);
        $this->expectExceptionMessage("Unable to write to file $filepath");

        $output->writeMessages();
    }

    public function testFilePath(): void
    {
        $filepath  = $this->getFilepath();
        $filepath2 = Directory::storagePath('file-output-test2.txt');

        $output  = (new FileOutput($filepath));
        $output2 = $output->withFilepath($filepath2);

        self::assertNotSame($output, $output2);
        self::assertSame($filepath, $output->getFilepath());
        self::assertSame($filepath2, $output2->getFilepath());
    }

    /**
     * @return non-empty-string
     */
    private function getFilepath(): string
    {
        return Directory::storagePath('file-output-test.txt');
    }
}
