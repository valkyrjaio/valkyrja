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

namespace Valkyrja\Tests\Unit\Mail\Data;

use Valkyrja\Mail\Data\Contract\MessageContract;
use Valkyrja\Mail\Data\Message;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class MessageTest extends TestCase
{
    /** @var non-empty-string */
    protected const string FROM_EMAIL = 'sender@example.com';
    /** @var non-empty-string */
    protected const string FROM_NAME = 'Sender Name';
    /** @var non-empty-string */
    protected const string SUBJECT = 'Test Subject';
    /** @var non-empty-string */
    protected const string BODY = 'Test body content';

    public function testDefaults(): void
    {
        $fromEmail = self::FROM_EMAIL;
        $fromName  = self::FROM_NAME;
        $subject   = self::SUBJECT;
        $body      = self::BODY;

        $message = new Message(
            fromEmail: $fromEmail,
            fromName: $fromName,
            subject: $subject,
            body: $body
        );

        self::assertInstanceOf(MessageContract::class, $message);
        self::assertSame($fromEmail, $message->getFromEmail());
        self::assertSame($fromName, $message->getFromName());
        self::assertSame($subject, $message->getSubject());
        self::assertSame($body, $message->getBody());
        self::assertEmpty($message->getRecipients());
        self::assertEmpty($message->getReplyToRecipients());
        self::assertEmpty($message->getCopyRecipients());
        self::assertEmpty($message->getBlindCopyRecipients());
        self::assertEmpty($message->getAttachments());
        self::assertFalse($message->isHtml());
        self::assertNull($message->getPlainBody());
    }

    public function testFrom(): void
    {
        $fromEmail  = self::FROM_EMAIL;
        $fromName   = self::FROM_NAME;
        $fromEmail2 = 'new-sender@example.com';
        $fromName2  = 'New Sender';
        $subject    = self::SUBJECT;
        $body       = self::BODY;

        $message  = new Message(
            fromEmail: $fromEmail,
            fromName: $fromName,
            subject: $subject,
            body: $body
        );
        $message2 = $message->withFrom($fromEmail2, $fromName2);

        self::assertNotSame($message, $message2);

        self::assertSame($fromEmail, $message->getFromEmail());
        self::assertSame($fromName, $message->getFromName());
        self::assertSame($subject, $message->getSubject());
        self::assertSame($body, $message->getBody());

        self::assertSame($fromEmail2, $message2->getFromEmail());
        self::assertSame($fromName2, $message2->getFromName());
        self::assertSame($subject, $message2->getSubject());
        self::assertSame($body, $message2->getBody());
    }

    public function testFromWithDefaultName(): void
    {
        $message = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );

        $message2 = $message->withFrom('new@example.com');

        self::assertSame('new@example.com', $message2->getFromEmail());
        self::assertSame('', $message2->getFromName());
    }

    public function testSubject(): void
    {
        $subject  = self::SUBJECT;
        $subject2 = 'New Subject';

        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: $subject,
            body: self::BODY
        );
        $message2 = $message->withSubject($subject2);

        self::assertNotSame($message, $message2);

        self::assertSame($subject, $message->getSubject());
        self::assertSame($subject2, $message2->getSubject());
    }

    public function testBody(): void
    {
        $body  = self::BODY;
        $body2 = 'New body content';

        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: $body
        );
        $message2 = $message->withBody($body2);

        self::assertNotSame($message, $message2);

        self::assertSame($body, $message->getBody());
        self::assertSame($body2, $message2->getBody());
    }

    public function testIsHtml(): void
    {
        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withIsHtml(true);
        $message3 = $message2->withIsHtml(false);

        self::assertNotSame($message, $message2);
        self::assertNotSame($message2, $message3);

        self::assertFalse($message->isHtml());
        self::assertTrue($message2->isHtml());
        self::assertFalse($message3->isHtml());
    }

    public function testWithIsHtmlDefaultsToTrue(): void
    {
        $message = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );

        self::assertFalse($message->isHtml());

        $message2 = $message->withIsHtml();

        self::assertTrue($message2->isHtml());
    }

    public function testPlainBody(): void
    {
        $plainBody = 'Plain text version';

        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withPlainBody($plainBody);
        $message3 = $message2->withPlainBody(null);

        self::assertNotSame($message, $message2);
        self::assertNotSame($message2, $message3);

        self::assertNull($message->getPlainBody());
        self::assertSame($plainBody, $message2->getPlainBody());
        self::assertNull($message3->getPlainBody());
    }

    public function testRecipients(): void
    {
        $email1 = 'recipient1@example.com';
        $name1  = 'Recipient One';
        $email2 = 'recipient2@example.com';
        $name2  = 'Recipient Two';

        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedRecipient($email1, $name1);
        $message3 = $message2->withAddedRecipient($email2, $name2);

        self::assertNotSame($message, $message2);
        self::assertNotSame($message2, $message3);

        self::assertEmpty($message->getRecipients());

        self::assertCount(1, $message2->getRecipients());
        self::assertSame(['email' => $email1, 'name' => $name1], $message2->getRecipients()[0]);

        self::assertCount(2, $message3->getRecipients());
        self::assertSame(['email' => $email1, 'name' => $name1], $message3->getRecipients()[0]);
        self::assertSame(['email' => $email2, 'name' => $name2], $message3->getRecipients()[1]);
    }

    public function testRecipientWithDefaultName(): void
    {
        $message = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );

        $message2 = $message->withAddedRecipient('test@example.com');

        self::assertSame(['email' => 'test@example.com', 'name' => ''], $message2->getRecipients()[0]);
    }

    public function testReplyToRecipients(): void
    {
        $email = 'replyto@example.com';
        $name  = 'Reply To';

        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedReplyToRecipient($email, $name);

        self::assertNotSame($message, $message2);

        self::assertEmpty($message->getReplyToRecipients());
        self::assertCount(1, $message2->getReplyToRecipients());
        self::assertSame(['email' => $email, 'name' => $name], $message2->getReplyToRecipients()[0]);
    }

    public function testCopyRecipients(): void
    {
        $email = 'cc@example.com';
        $name  = 'CC Recipient';

        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedCopyRecipient($email, $name);

        self::assertNotSame($message, $message2);

        self::assertEmpty($message->getCopyRecipients());
        self::assertCount(1, $message2->getCopyRecipients());
        self::assertSame(['email' => $email, 'name' => $name], $message2->getCopyRecipients()[0]);
    }

    public function testBlindCopyRecipients(): void
    {
        $email = 'bcc@example.com';
        $name  = 'BCC Recipient';

        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedBlindCopyRecipient($email, $name);

        self::assertNotSame($message, $message2);

        self::assertEmpty($message->getBlindCopyRecipients());
        self::assertCount(1, $message2->getBlindCopyRecipients());
        self::assertSame(['email' => $email, 'name' => $name], $message2->getBlindCopyRecipients()[0]);
    }

    public function testAttachments(): void
    {
        $path = '/path/to/file.pdf';
        $name = 'document.pdf';

        $message  = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedAttachment($path, $name);

        self::assertNotSame($message, $message2);

        self::assertEmpty($message->getAttachments());
        self::assertCount(1, $message2->getAttachments());
        self::assertSame(['path' => $path, 'name' => $name], $message2->getAttachments()[0]);
    }

    public function testAttachmentWithDefaultName(): void
    {
        $message = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );

        $message2 = $message->withAddedAttachment('/path/to/file.pdf');

        self::assertSame(['path' => '/path/to/file.pdf', 'name' => ''], $message2->getAttachments()[0]);
    }

    public function testChainedWith(): void
    {
        $message = new Message(
            fromEmail: self::FROM_EMAIL,
            fromName: self::FROM_NAME,
            subject: self::SUBJECT,
            body: self::BODY
        );

        $message2 = $message
            ->withFrom('new@example.com', 'New Name')
            ->withSubject('New Subject')
            ->withBody('New Body')
            ->withIsHtml(true)
            ->withPlainBody('Plain text')
            ->withAddedRecipient('to@example.com', 'To')
            ->withAddedReplyToRecipient('reply@example.com', 'Reply')
            ->withAddedCopyRecipient('cc@example.com', 'CC')
            ->withAddedBlindCopyRecipient('bcc@example.com', 'BCC')
            ->withAddedAttachment('/path/to/file.pdf', 'file.pdf');

        self::assertNotSame($message, $message2);

        // Original unchanged
        self::assertSame(self::FROM_EMAIL, $message->getFromEmail());
        self::assertSame(self::FROM_NAME, $message->getFromName());
        self::assertSame(self::SUBJECT, $message->getSubject());
        self::assertSame(self::BODY, $message->getBody());
        self::assertFalse($message->isHtml());
        self::assertNull($message->getPlainBody());
        self::assertEmpty($message->getRecipients());
        self::assertEmpty($message->getReplyToRecipients());
        self::assertEmpty($message->getCopyRecipients());
        self::assertEmpty($message->getBlindCopyRecipients());
        self::assertEmpty($message->getAttachments());

        // New has all changes
        self::assertSame('new@example.com', $message2->getFromEmail());
        self::assertSame('New Name', $message2->getFromName());
        self::assertSame('New Subject', $message2->getSubject());
        self::assertSame('New Body', $message2->getBody());
        self::assertTrue($message2->isHtml());
        self::assertSame('Plain text', $message2->getPlainBody());
        self::assertCount(1, $message2->getRecipients());
        self::assertCount(1, $message2->getReplyToRecipients());
        self::assertCount(1, $message2->getCopyRecipients());
        self::assertCount(1, $message2->getBlindCopyRecipients());
        self::assertCount(1, $message2->getAttachments());
    }
}
