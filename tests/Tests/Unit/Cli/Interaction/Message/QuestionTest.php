<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Formatter\QuestionFormatter;
use Valkyrja\Cli\Interaction\Message\Answer;
use Valkyrja\Cli\Interaction\Message\Question;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Message\QuestionEmptyFgetsFixture;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Message\QuestionFalseFgetsFixture;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Message\QuestionFalseFopenFixture;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Message\QuestionFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Question class.
 */
final class QuestionTest extends TestCase
{
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

        $question = new QuestionFixture(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        $askedAnswer = $question->ask();

        self::assertNotSame($answer, $askedAnswer);
        self::assertSame('*', $askedAnswer->getUserResponse());
        self::assertTrue($askedAnswer->hasBeenAnswered());
    }

    public function testAskFalseFopen(): void
    {
        $defaultResponse = 'defaultResponse';

        $answer = new Answer($defaultResponse);

        $question = new QuestionFalseFopenFixture(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        $askedAnswer = $question->ask();

        self::assertSame($answer, $askedAnswer);
        self::assertSame($defaultResponse, $askedAnswer->getUserResponse());
        self::assertFalse($askedAnswer->hasBeenAnswered());
    }

    public function testAskFalseFgets(): void
    {
        $defaultResponse = 'defaultResponse';

        $answer = new Answer($defaultResponse);

        $question = new QuestionFalseFgetsFixture(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        $askedAnswer = $question->ask();

        self::assertSame($answer, $askedAnswer);
        self::assertSame($defaultResponse, $askedAnswer->getUserResponse());
        self::assertFalse($askedAnswer->hasBeenAnswered());
    }

    public function testAskEmptyFgets(): void
    {
        $defaultResponse = 'defaultResponse';

        $answer = new Answer($defaultResponse);

        $question = new QuestionEmptyFgetsFixture(
            text: 'text',
            callable: [$this, 'questionCallable'],
            answer: $answer
        );

        $askedAnswer = $question->ask();

        self::assertSame($answer, $askedAnswer);
        self::assertSame($defaultResponse, $askedAnswer->getUserResponse());
        self::assertFalse($askedAnswer->hasBeenAnswered());
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
