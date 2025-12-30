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
use Valkyrja\Cli\Interaction\Message\Answer;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\Question;
use Valkyrja\Cli\Interaction\Output\Contract\Output as Contract;
use Valkyrja\Cli\Interaction\Output\EmptyOutput;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Tests\Classes\Cli\Interaction\Message\QuestionAskManipulationClass;
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

        $output = new Output();

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

        ob_start();
        $outputWritten  = $output->writeMessages();
        $outputContents = ob_get_clean();

        ob_start();
        $output2Written  = $output2->writeMessages();
        $output2Contents = ob_get_clean();

        ob_start();
        $output3Written  = $output3->writeMessages();
        $output3Contents = ob_get_clean();

        self::assertEmpty($outputWritten->getMessages());
        self::assertEmpty($outputWritten->getWrittenMessages());
        self::assertEmpty($outputWritten->getUnwrittenMessages());
        self::assertFalse($outputWritten->hasWrittenMessage());
        self::assertFalse($outputWritten->hasUnwrittenMessage());
        self::assertEmpty($outputContents);

        self::assertSame([$message], $output2Written->getMessages());
        self::assertCount(1, $output2Written->getWrittenMessages());
        self::assertEmpty($output2Written->getUnwrittenMessages());
        self::assertTrue($output2Written->hasWrittenMessage());
        self::assertFalse($output2Written->hasUnwrittenMessage());
        self::assertNotEmpty($output2Contents);

        self::assertSame([$message], $output3Written->getMessages());
        self::assertCount(1, $output3Written->getWrittenMessages());
        self::assertEmpty($output3Written->getUnwrittenMessages());
        self::assertTrue($output3Written->hasWrittenMessage());
        self::assertFalse($output3Written->hasUnwrittenMessage());
        self::assertNotEmpty($output3Contents);
    }

    public function testQuestion(): void
    {
        $callableCalled = false;
        $callable       = static function (Contract $output, Answer $answer) use (&$callableCalled): Contract {
            $callableCalled = true;

            return $output;
        };
        $question       = new Question(
            text: 'text',
            callable: $callable,
            answer: new Answer('defaultResponse')
        );

        $output = new Output(isSilent: true)
            ->withAddedMessages($question);

        self::assertSame([$question], $output->getMessages());
        self::assertEmpty($output->getWrittenMessages());
        self::assertSame([$question], $output->getUnwrittenMessages());
        self::assertFalse($output->hasWrittenMessage());
        self::assertTrue($output->hasUnwrittenMessage());

        ob_start();
        $outputWritten  = $output->writeMessages();
        $outputContents = ob_get_clean();

        self::assertTrue($callableCalled);
        self::assertNotEmpty($outputWritten->getWrittenMessages());
        self::assertTrue($outputWritten->hasWrittenMessage());
        self::assertFalse($outputWritten->hasUnwrittenMessage());
        self::assertEmpty($outputContents);
    }

    public function testReAskQuestionOnInvalidAnswer(): void
    {
        $callableCalled = false;
        $callable       = static function (Contract $output, Answer $answer) use (&$callableCalled): Contract {
            $callableCalled = true;

            return $output;
        };
        $question       = new QuestionAskManipulationClass(
            text: 'text',
            callable: $callable,
            answer: new Answer(
                defaultResponse: 'defaultResponse',
                allowedResponses: ['defaultResponse']
            )
        );

        $output = new EmptyOutput()
            ->withAddedMessages($question);

        $outputWritten = $output->writeMessages();

        self::assertTrue($callableCalled);
        self::assertSame(2, $question->getTimesAsked());
        self::assertNotEmpty($outputWritten->getWrittenMessages());
        self::assertTrue($outputWritten->hasWrittenMessage());
        self::assertFalse($outputWritten->hasUnwrittenMessage());
    }
}
