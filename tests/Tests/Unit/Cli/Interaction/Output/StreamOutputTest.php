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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Output;

use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\StreamOutput;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the FileOutput class.
 */
class StreamOutputTest extends TestCase
{
    public function testOutputMessage(): void
    {
        $text    = 'text';
        $message = new Message($text);

        $output = new StreamOutput(fopen(filename: EnvClass::APP_DIR . '/storage/stream-output-test.txt', mode: 'wrb'))
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
        $stream  = fopen(filename: EnvClass::APP_DIR . '/storage/stream-output-test.txt', mode: 'wrb');
        $stream2 = fopen(filename: EnvClass::APP_DIR . '/storage/stream-output-test2.txt', mode: 'wrb');

        $output  = (new StreamOutput($stream));
        $output2 = $output->withStream($stream2);

        self::assertNotSame($output, $output2);
        self::assertSame($stream, $output->getStream());
        self::assertSame($stream2, $output2->getStream());
    }
}
