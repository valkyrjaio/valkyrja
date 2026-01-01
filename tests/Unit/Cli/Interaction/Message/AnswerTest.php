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

use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\Answer;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\TestCase;

use function str_contains;

/**
 * Test the ErrorMessage class.
 */
class AnswerTest extends TestCase
{
    public function testInvalidCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Answer(
            defaultResponse: 'text',
            validationCallable: [$this, 'invalid']
        );
    }

    public function testText(): void
    {
        $defaultResponse = 'text';
        $formatter       = new HighlightedTextFormatter();

        $message = new Answer(defaultResponse: $defaultResponse, formatter: $formatter);

        self::assertSame($defaultResponse, $message->getText());
        self::assertSame($formatter->formatText($defaultResponse), $message->getFormattedText());
    }

    public function testDefaultResponse(): void
    {
        $defaultResponse  = 'text';
        $defaultResponse2 = 'text2';
        $formatter        = new HighlightedTextFormatter();

        $message = new Answer(defaultResponse: $defaultResponse, formatter: $formatter);

        self::assertSame($defaultResponse, $message->getDefaultResponse());
        self::assertContains($defaultResponse, $message->getAllowedResponses());
        self::assertSame($defaultResponse, $message->getText());
        self::assertSame($formatter->formatText($defaultResponse), $message->getFormattedText());

        $message2 = $message->withDefaultResponse($defaultResponse2);

        self::assertNotSame($message, $message2);
        self::assertSame($defaultResponse2, $message2->getDefaultResponse());
        self::assertContains($defaultResponse2, $message2->getAllowedResponses());
        self::assertSame($defaultResponse2, $message2->getText());
        self::assertSame($formatter->formatText($defaultResponse2), $message2->getFormattedText());

        $message3 = $message->withHasBeenAnswered(true)->withDefaultResponse($defaultResponse2);

        self::assertNotSame($message, $message3);
        self::assertSame($defaultResponse2, $message3->getDefaultResponse());
        self::assertSame($defaultResponse, $message3->getText());
        self::assertSame($formatter->formatText($defaultResponse), $message3->getFormattedText());
    }

    public function testUserResponse(): void
    {
        $defaultResponse = 'text';
        $userResponse    = 'user text';
        $formatter       = new HighlightedTextFormatter();

        $message = new Answer(defaultResponse: $defaultResponse, formatter: $formatter);

        self::assertSame($defaultResponse, $message->getUserResponse());
        self::assertFalse($message->hasBeenAnswered());
        self::assertSame($defaultResponse, $message->getText());
        self::assertSame($formatter->formatText($defaultResponse), $message->getFormattedText());

        $message2 = $message->withUserResponse($userResponse);

        self::assertNotSame($message, $message2);
        self::assertSame($defaultResponse, $message2->getDefaultResponse());
        self::assertTrue($message2->hasBeenAnswered());
        self::assertSame($userResponse, $message2->getUserResponse());
        self::assertSame($userResponse, $message2->getText());
        self::assertSame($formatter->formatText($userResponse), $message2->getFormattedText());
    }

    public function testHasBeenAnswered(): void
    {
        $defaultResponse = 'text';
        $formatter       = new HighlightedTextFormatter();

        $message = new Answer(defaultResponse: $defaultResponse, formatter: $formatter);

        self::assertFalse($message->hasBeenAnswered());

        $message2 = $message->withHasBeenAnswered(true);

        self::assertNotSame($message, $message2);
        self::assertTrue($message2->hasBeenAnswered());
    }

    public function testValidationCallback(): void
    {
        $defaultResponse     = 'text';
        $validationCallback  = static fn (string $response): bool => str_contains($response, 'text');
        $validationCallback2 = static fn (string $response): bool => str_contains($response, 'test');

        $message = new Answer(defaultResponse: $defaultResponse, validationCallable: $validationCallback);

        self::assertSame($validationCallback, $message->getValidationCallable());
        self::assertTrue($message->isValidResponse());

        $message2 = $message->withValidationCallable($validationCallback2);

        self::assertNotSame($message, $message2);
        self::assertSame($validationCallback2, $message2->getValidationCallable());
        self::assertFalse($message2->withUserResponse('text2')->isValidResponse());
        self::assertTrue($message2->withUserResponse('test')->isValidResponse());
    }

    public function testAllowedResponses(): void
    {
        $defaultResponse   = 'text';
        $allowedResponses  = ['text2', 'text3'];
        $allowedResponses2 = ['text4', 'text5'];

        $message = new Answer(defaultResponse: $defaultResponse, allowedResponses: $allowedResponses);

        self::assertContains($defaultResponse, $message->getAllowedResponses());
        self::assertContains('text2', $message->getAllowedResponses());
        self::assertContains('text3', $message->getAllowedResponses());
        self::assertTrue($message->isValidResponse());
        self::assertTrue($message->withUserResponse('text2')->isValidResponse());
        self::assertTrue($message->withUserResponse('text3')->isValidResponse());
        self::assertFalse($message->withUserResponse('text4')->isValidResponse());

        $message2 = $message->withAllowedResponses(...$allowedResponses2);

        self::assertNotSame($message, $message2);
        self::assertContains($defaultResponse, $message2->getAllowedResponses());
        self::assertNotContains('text2', $message2->getAllowedResponses());
        self::assertNotContains('text3', $message2->getAllowedResponses());
        self::assertContains('text4', $message2->getAllowedResponses());
        self::assertContains('text5', $message2->getAllowedResponses());
        self::assertTrue($message2->isValidResponse());
        self::assertFalse($message2->withUserResponse('text2')->isValidResponse());
        self::assertFalse($message2->withUserResponse('text3')->isValidResponse());
        self::assertTrue($message2->withUserResponse('text4')->isValidResponse());
        self::assertTrue($message2->withUserResponse('text5')->isValidResponse());
    }
}
