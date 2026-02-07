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

namespace Valkyrja\Tests\Unit\Sms\Data;

use Valkyrja\Sms\Data\Contract\MessageContract;
use Valkyrja\Sms\Data\Message;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class MessageTest extends TestCase
{
    /** @var non-empty-string */
    protected const string TO = '+15551234567';
    /** @var non-empty-string */
    protected const string FROM = '+15559876543';
    /** @var non-empty-string */
    protected const string TEXT = 'Hello, this is a test message';

    public function testDefaults(): void
    {
        $to   = self::TO;
        $from = self::FROM;
        $text = self::TEXT;

        $message = new Message(
            to: $to,
            from: $from,
            text: $text
        );

        self::assertInstanceOf(MessageContract::class, $message);
        self::assertSame($to, $message->getTo());
        self::assertSame($from, $message->getFrom());
        self::assertSame($text, $message->getText());
        self::assertTrue($message->isUnicode());
    }

    public function testDefaultsWithUnicodeFalse(): void
    {
        $to   = self::TO;
        $from = self::FROM;
        $text = self::TEXT;

        $message = new Message(
            to: $to,
            from: $from,
            text: $text,
            isUnicode: false
        );

        self::assertSame($to, $message->getTo());
        self::assertSame($from, $message->getFrom());
        self::assertSame($text, $message->getText());
        self::assertFalse($message->isUnicode());
    }

    public function testTo(): void
    {
        $to   = self::TO;
        $to2  = '+15550001111';
        $from = self::FROM;
        $text = self::TEXT;

        $message  = new Message(
            to: $to,
            from: $from,
            text: $text
        );
        $message2 = $message->withTo($to2);

        self::assertNotSame($message, $message2);

        self::assertSame($to, $message->getTo());
        self::assertSame($from, $message->getFrom());
        self::assertSame($text, $message->getText());
        self::assertTrue($message->isUnicode());

        self::assertSame($to2, $message2->getTo());
        self::assertSame($from, $message2->getFrom());
        self::assertSame($text, $message2->getText());
        self::assertTrue($message2->isUnicode());
    }

    public function testFrom(): void
    {
        $to    = self::TO;
        $from  = self::FROM;
        $from2 = '+15552223333';
        $text  = self::TEXT;

        $message  = new Message(
            to: $to,
            from: $from,
            text: $text
        );
        $message2 = $message->withFrom($from2);

        self::assertNotSame($message, $message2);

        self::assertSame($to, $message->getTo());
        self::assertSame($from, $message->getFrom());
        self::assertSame($text, $message->getText());
        self::assertTrue($message->isUnicode());

        self::assertSame($to, $message2->getTo());
        self::assertSame($from2, $message2->getFrom());
        self::assertSame($text, $message2->getText());
        self::assertTrue($message2->isUnicode());
    }

    public function testText(): void
    {
        $to    = self::TO;
        $from  = self::FROM;
        $text  = self::TEXT;
        $text2 = 'This is a different message';

        $message  = new Message(
            to: $to,
            from: $from,
            text: $text
        );
        $message2 = $message->withText($text2);

        self::assertNotSame($message, $message2);

        self::assertSame($to, $message->getTo());
        self::assertSame($from, $message->getFrom());
        self::assertSame($text, $message->getText());
        self::assertTrue($message->isUnicode());

        self::assertSame($to, $message2->getTo());
        self::assertSame($from, $message2->getFrom());
        self::assertSame($text2, $message2->getText());
        self::assertTrue($message2->isUnicode());
    }

    public function testIsUnicode(): void
    {
        $to   = self::TO;
        $from = self::FROM;
        $text = self::TEXT;

        $message  = new Message(
            to: $to,
            from: $from,
            text: $text,
            isUnicode: true
        );
        $message2 = $message->withIsUnicode(false);
        $message3 = $message2->withIsUnicode(true);

        self::assertNotSame($message, $message2);
        self::assertNotSame($message2, $message3);

        self::assertSame($to, $message->getTo());
        self::assertSame($from, $message->getFrom());
        self::assertSame($text, $message->getText());
        self::assertTrue($message->isUnicode());

        self::assertSame($to, $message2->getTo());
        self::assertSame($from, $message2->getFrom());
        self::assertSame($text, $message2->getText());
        self::assertFalse($message2->isUnicode());

        self::assertSame($to, $message3->getTo());
        self::assertSame($from, $message3->getFrom());
        self::assertSame($text, $message3->getText());
        self::assertTrue($message3->isUnicode());
    }

    public function testWithIsUnicodeDefaultsToTrue(): void
    {
        $message = new Message(
            to: self::TO,
            from: self::FROM,
            text: self::TEXT,
            isUnicode: false
        );

        self::assertFalse($message->isUnicode());

        $message2 = $message->withIsUnicode();

        self::assertTrue($message2->isUnicode());
    }

    public function testChainedWith(): void
    {
        $to    = self::TO;
        $from  = self::FROM;
        $text  = self::TEXT;
        $to2   = '+15550001111';
        $from2 = '+15552223333';
        $text2 = 'Different message';

        $message = new Message(
            to: $to,
            from: $from,
            text: $text
        );

        $message2 = $message
            ->withTo($to2)
            ->withFrom($from2)
            ->withText($text2)
            ->withIsUnicode(false);

        self::assertNotSame($message, $message2);

        // Original unchanged
        self::assertSame($to, $message->getTo());
        self::assertSame($from, $message->getFrom());
        self::assertSame($text, $message->getText());
        self::assertTrue($message->isUnicode());

        // New has all changes
        self::assertSame($to2, $message2->getTo());
        self::assertSame($from2, $message2->getFrom());
        self::assertSame($text2, $message2->getText());
        self::assertFalse($message2->isUnicode());
    }
}
