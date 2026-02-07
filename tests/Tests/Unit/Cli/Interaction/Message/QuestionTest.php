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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Formatter\QuestionFormatter;
use Valkyrja\Cli\Interaction\Message\Answer;
use Valkyrja\Cli\Interaction\Message\Question;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Classes\Cli\Interaction\Message\QuestionClass;
use Valkyrja\Tests\Classes\Cli\Interaction\Message\QuestionEmptyFgetsClass;
use Valkyrja\Tests\Classes\Cli\Interaction\Message\QuestionFalseFgetsClass;
use Valkyrja\Tests\Classes\Cli\Interaction\Message\QuestionFalseFopenClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Question class.
 */
final class QuestionTest extends TestCase
{
    public function testInvalidCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $callable = [$this, 'invalid'];

        new Question(
            text: 'text',
            callable: $callable,
            answer: new Answer('defaultResponse')
        );
    }

    public function testText(): void
    {
        $text      = 'text';
        $formatter = new QuestionFormatter();
        $answer    = new Answer('defaultResponse');
        $callable  = [$this, 'questionCallable'];

        $question = new Question(
            text: $text,
            callable: $callable,
            answer: $answer
        );

        self::assertStringContainsString($text, $question->getText());
        self::assertStringContainsString($formatter->formatText($text), $question->getFormattedText());
    }

    public function testCallable(): void
    {
        $callable  = [$this, 'questionCallable'];
        $callable2 = [$this, 'questionCallable2'];

        $question = new Question(
            text: 'text',
            callable: $callable,
            answer: new Answer('defaultResponse')
        );

        self::assertSame($callable, $question->getCallable());

        $question2 = $question->withCallable($callable2);

        self::assertNotSame($question, $question2);
        self::assertSame($callable2, $question2->getCallable());
    }

    public function testAnswer(): void
    {
        $answer  = new Answer('defaultResponse');
        $answer2 = new Answer('defaultResponse2');

        $question = new Question(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        self::assertSame($answer, $question->getAnswer());

        $question2 = $question->withAnswer($answer2);

        self::assertNotSame($question, $question2);
        self::assertSame($answer2, $question2->getAnswer());
    }

    public function testAsk(): void
    {
        $answer = new Answer('defaultResponse');

        $question = new QuestionClass(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        $askedAnswer = $question->ask();

        self::assertNotSame($answer, $askedAnswer);
        self::assertSame('*', $askedAnswer->getUserResponse());
    }

    public function testAskFalseFopen(): void
    {
        $defaultResponse = 'defaultResponse';

        $answer = new Answer($defaultResponse);

        $question = new QuestionFalseFopenClass(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        $askedAnswer = $question->ask();

        self::assertSame($answer, $askedAnswer);
        self::assertSame($defaultResponse, $askedAnswer->getUserResponse());
    }

    public function testAskFalseFgets(): void
    {
        $defaultResponse = 'defaultResponse';

        $answer = new Answer($defaultResponse);

        $question = new QuestionFalseFgetsClass(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        $askedAnswer = $question->ask();

        self::assertSame($answer, $askedAnswer);
        self::assertSame($defaultResponse, $askedAnswer->getUserResponse());
    }

    public function testAskEmptyFgets(): void
    {
        $defaultResponse = 'defaultResponse';

        $answer = new Answer($defaultResponse);

        $question = new QuestionEmptyFgetsClass(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        $askedAnswer = $question->ask();

        self::assertSame($answer, $askedAnswer);
        self::assertSame($defaultResponse, $askedAnswer->getUserResponse());
    }

    public function questionCallable(OutputContract $output, Answer $answer): OutputContract
    {
        return $output;
    }

    public function questionCallable2(OutputContract $output, Answer $answer): OutputContract
    {
        return $output;
    }
}
