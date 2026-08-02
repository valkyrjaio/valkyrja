<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Mail\Mailer;

use Valkyrja\Mail\Data\Message;
use Valkyrja\Mail\Data\Recipient;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Mail\Mailer\NullMailer;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NullMailerTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $mailer = new NullMailer();

        self::assertInstanceOf(MailerContract::class, $mailer);
    }

    public function testSendDoesNothing(): void
    {
        $mailer  = new NullMailer();
        $message = new Message(
            from: new Recipient('sender@example.com', 'Sender'),
            subject: 'Test Subject',
            body: 'Test body'
        );

        // Should not throw any exception
        $mailer->send($message);

        self::assertTrue(true);
    }

    public function testSendMultipleMessages(): void
    {
        $mailer = new NullMailer();

        $message1 = new Message(
            from: new Recipient('sender@example.com', 'Sender'),
            subject: 'Subject 1',
            body: 'Body 1'
        );
        $message2 = new Message(
            from: new Recipient('sender@example.com', 'Sender'),
            subject: 'Subject 2',
            body: 'Body 2'
        );
        $message3 = new Message(
            from: new Recipient('sender@example.com', 'Sender'),
            subject: 'Subject 3',
            body: 'Body 3'
        );

        // All should execute without error
        $mailer->send($message1);
        $mailer->send($message2);
        $mailer->send($message3);

        self::assertTrue(true);
    }
}
