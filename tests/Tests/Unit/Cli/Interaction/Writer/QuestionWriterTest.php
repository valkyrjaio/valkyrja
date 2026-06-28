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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Writer;

use Valkyrja\Cli\Interaction\Message\Answer;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\Question;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\EmptyOutput;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionExpectedQuestionOutputException;
use Valkyrja\Cli\Interaction\Writer\QuestionWriter;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Message\QuestionAskManipulationClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class QuestionWriterTest extends TestCase
{
    public function testShouldWriteMessage(): void
    {
        $writer   = new QuestionWriter();
        $question = new Question(
            text: 'text',
            callable: static fn (OutputContract $output, Answer $answer): OutputContract => $output,
            answer: new Answer('default'),
        );

        self::assertTrue($writer->shouldWriteMessage($question));
        self::assertFalse($writer->shouldWriteMessage(new Message('text')));
    }

    public function testInvalidMessage(): void
    {
        $this->expectException(CliInteractionExpectedQuestionOutputException::class);

        $questionWriter = new QuestionWriter();

        $questionWriter->write(new Output(), new Message('text'));
    }

    public function testWritesQuestionAndInvokesCallableForNonInteractiveOutput(): void
    {
        $called   = false;
        $callable = static function (OutputContract $output, Answer $answer) use (&$called): OutputContract {
            $called = true;

            return $output;
        };
        $question = new Question(
            text: 'text',
            callable: $callable,
            answer: new Answer(defaultResponse: 'default', allowedResponses: ['default', 'other']),
        );

        $writer = new QuestionWriter();

        $result = $writer->write(new Output(isSilent: true), $question);

        self::assertTrue($called);
        self::assertInstanceOf(OutputContract::class, $result);
    }

    public function testReAsksQuestionUntilValidResponse(): void
    {
        $called   = false;
        $callable = static function (OutputContract $output, Answer $answer) use (&$called): OutputContract {
            $called = true;

            return $output;
        };
        $question = new QuestionAskManipulationClass(
            text: 'text',
            callable: $callable,
            answer: new Answer(defaultResponse: 'defaultResponse', allowedResponses: ['defaultResponse']),
        );

        $writer = new QuestionWriter();

        $result = $writer->write(new EmptyOutput(), $question);

        self::assertTrue($called);
        self::assertSame(2, $question->getTimesAsked());
        self::assertInstanceOf(OutputContract::class, $result);
    }
}
