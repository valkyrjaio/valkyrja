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

use Valkyrja\Cli\Interaction\Formatter\Formatter;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Message class.
 */
class MessageTest extends TestCase
{
    public function testDefaults(): void
    {
        $text = 'text';

        $message = new Message(text: $text);

        self::assertSame($text, $message->getText());
        self::assertSame($text, $message->getFormattedText());
        self::assertNull($message->getFormatter());
    }

    public function testConstructor(): void
    {
        $text      = 'text';
        $formatter = new HighlightedTextFormatter();

        $message = new Message(...[
            'text'      => $text,
            'formatter' => $formatter,
        ]);

        self::assertSame($text, $message->getText());
        self::assertSame($formatter, $message->getFormatter());
    }

    public function testText(): void
    {
        $text    = 'text';
        $newText = 'text2';

        $message = new Message(text: $text);

        self::assertSame($text, $message->getText());
        self::assertSame($text, $message->getFormattedText());

        $message2 = $message->withText($newText);

        self::assertNotSame($message, $message2);
        self::assertSame($newText, $message2->getText());
        self::assertSame($newText, $message2->getFormattedText());
    }

    public function testFormatter(): void
    {
        $text = 'text';

        $formatter    = new Formatter();
        $newFormatter = new HighlightedTextFormatter();

        $message = new Message(text: $text, formatter: $formatter);

        self::assertSame($text, $message->getText());
        self::assertSame($formatter->formatText($text), $message->getFormattedText());
        self::assertSame($formatter, $message->getFormatter());

        $message2 = $message->withFormatter($newFormatter);

        self::assertNotSame($message, $message2);
        self::assertSame($text, $message2->getText());
        self::assertSame($newFormatter->formatText($text), $message2->getFormattedText());
        self::assertSame($newFormatter, $message2->getFormatter());
    }
}
