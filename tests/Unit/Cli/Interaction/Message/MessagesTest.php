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
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\Messages;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Messages class.
 */
class MessagesTest extends TestCase
{
    public function testEmptyTextException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $message = new Messages();

        $message->getText();
    }

    public function testEmptyFormattedTextException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $message = new Messages();

        $message->getFormattedText();
    }

    public function testText(): void
    {
        $message1 = new Message('The ');
        $message2 = new Message('yellow ');
        $message3 = new Message('fox', new HighlightedTextFormatter());
        $message4 = new Message(' jumped');

        $messages = new Messages(
            $message1,
            $message2,
            $message3,
            $message4,
        );

        self::assertSame(
            $message1->getText()
            . $message2->getText()
            . $message3->getText()
            . $message4->getText(),
            $messages->getText()
        );

        self::assertSame(
            $message1->getFormattedText()
            . $message2->getFormattedText()
            . $message3->getFormattedText()
            . $message4->getFormattedText(),
            $messages->getFormattedText()
        );
    }
}
