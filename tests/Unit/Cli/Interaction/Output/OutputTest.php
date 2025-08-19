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

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Output class.
 *
 * @author Melech Mizrachi
 */
class OutputTest extends TestCase
{
    public function testDefaults(): void
    {
        $output = new Output();

        self::assertTrue($output->isInteractive());
        self::assertFalse($output->isQuiet());
        self::assertFalse($output->isSilent());
        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertFalse($output->hasWrittenMessage());
        self::assertFalse($output->hasUnwrittenMessage());
    }

    public function testInteractive(): void
    {
        $output = new Output();

        self::assertTrue($output->isInteractive());

        $output2 = $output->withIsInteractive(false);

        self::assertNotSame($output, $output2);
        self::assertFalse($output2->isInteractive());
    }

    public function testQuiet(): void
    {
        $output = new Output();

        self::assertFalse($output->isQuiet());

        $output2 = $output->withIsQuiet(true);

        self::assertNotSame($output, $output2);
        self::assertTrue($output2->isQuiet());
    }

    public function testSilent(): void
    {
        $output = new Output();

        self::assertFalse($output->isSilent());

        $output2 = $output->withIsSilent(true);

        self::assertNotSame($output, $output2);
        self::assertTrue($output2->isSilent());
    }

    public function testExitCode(): void
    {
        $output = new Output();

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());

        $output2 = $output->withExitCode(ExitCode::AUTO_EXIT);

        self::assertNotSame($output, $output2);
        self::assertSame(ExitCode::AUTO_EXIT, $output2->getExitCode());
    }

    public function testMessage(): void
    {
        $text    = 'text';
        $message = new Message($text);

        $output = new Output(isSilent: true);

        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertFalse($output->hasWrittenMessage());
        self::assertFalse($output->hasUnwrittenMessage());

        $output2 = $output->withAddedMessages($message);

        self::assertNotSame($output, $output2);
        self::assertSame([$message], $output2->getMessages());
        self::assertEmpty($output2->getWrittenMessages());
        self::assertSame([$message], $output2->getUnwrittenMessages());
        self::assertFalse($output2->hasWrittenMessage());
        self::assertTrue($output2->hasUnwrittenMessage());

        $output3 = $output->withAddedMessage($message);

        self::assertNotSame($output, $output3);
        self::assertSame([$message], $output3->getMessages());
        self::assertEmpty($output3->getWrittenMessages());
        self::assertSame([$message], $output3->getUnwrittenMessages());
        self::assertFalse($output3->hasWrittenMessage());
        self::assertTrue($output3->hasUnwrittenMessage());

        $output  = $output->writeMessages();
        $output2 = $output2->writeMessages();
        $output3 = $output3->writeMessages();

        self::assertEmpty($output->getMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertEmpty($output->getUnwrittenMessages());
        self::assertFalse($output->hasWrittenMessage());
        self::assertFalse($output->hasUnwrittenMessage());

        self::assertSame([$message], $output2->getMessages());
        self::assertCount(1, $output2->getWrittenMessages());
        self::assertEmpty($output2->getUnwrittenMessages());
        self::assertTrue($output2->hasWrittenMessage());
        self::assertFalse($output2->hasUnwrittenMessage());

        self::assertSame([$message], $output3->getMessages());
        self::assertCount(1, $output3->getWrittenMessages());
        self::assertEmpty($output3->getUnwrittenMessages());
        self::assertTrue($output3->hasWrittenMessage());
        self::assertFalse($output3->hasUnwrittenMessage());
    }
}
