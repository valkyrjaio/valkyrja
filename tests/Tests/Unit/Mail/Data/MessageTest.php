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

use Valkyrja\Mail\Data\Attachment;
use Valkyrja\Mail\Data\Contract\MessageContract;
use Valkyrja\Mail\Data\Message;
use Valkyrja\Mail\Data\Recipient;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class MessageTest extends TestCase
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
            from: new Recipient($fromEmail, $fromName),
            subject: $subject,
            body: $body
        );

        self::assertInstanceOf(MessageContract::class, $message);
        self::assertSame($fromEmail, $message->getFrom()->getEmail());
        self::assertSame($fromName, $message->getFrom()->getName());
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
            from: new Recipient($fromEmail, $fromName),
            subject: $subject,
            body: $body
        );
        $message2 = $message->withFrom(new Recipient($fromEmail2, $fromName2));

        self::assertNotSame($message, $message2);

        self::assertSame($fromEmail, $message->getFrom()->getEmail());
        self::assertSame($fromName, $message->getFrom()->getName());
        self::assertSame($subject, $message->getSubject());
        self::assertSame($body, $message->getBody());

        self::assertSame($fromEmail2, $message2->getFrom()->getEmail());
        self::assertSame($fromName2, $message2->getFrom()->getName());
        self::assertSame($subject, $message2->getSubject());
        self::assertSame($body, $message2->getBody());
    }

    public function testFromWithDefaultName(): void
    {
        $message = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );

        $message2 = $message->withFrom(new Recipient('new@example.com'));

        self::assertSame('new@example.com', $message2->getFrom()->getEmail());
        self::assertNull($message2->getFrom()->getName());
    }

    public function testSubject(): void
    {
        $subject  = self::SUBJECT;
        $subject2 = 'New Subject';

        $message  = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
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
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
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
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
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
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
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
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
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
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedRecipient(new Recipient($email1, $name1));
        $message3 = $message2->withAddedRecipient(new Recipient($email2, $name2));

        self::assertNotSame($message, $message2);
        self::assertNotSame($message2, $message3);

        self::assertEmpty($message->getRecipients());

        self::assertCount(1, $message2->getRecipients());
        self::assertSame($email1, $message2->getRecipients()[0]->getEmail());
        self::assertSame($name1, $message2->getRecipients()[0]->getName());

        self::assertCount(2, $message3->getRecipients());
        self::assertSame($email1, $message3->getRecipients()[0]->getEmail());
        self::assertSame($name1, $message3->getRecipients()[0]->getName());
        self::assertSame($email2, $message3->getRecipients()[1]->getEmail());
        self::assertSame($name2, $message3->getRecipients()[1]->getName());
    }

    public function testRecipientWithDefaultName(): void
    {
        $message = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );

        $message2 = $message->withAddedRecipient(new Recipient('test@example.com'));

        self::assertSame('test@example.com', $message2->getRecipients()[0]->getEmail());
        self::assertNull($message2->getRecipients()[0]->getName());
    }

    public function testReplyToRecipients(): void
    {
        $email = 'replyto@example.com';
        $name  = 'Reply To';

        $message  = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedReplyToRecipient(new Recipient($email, $name));

        self::assertNotSame($message, $message2);

        self::assertEmpty($message->getReplyToRecipients());
        self::assertCount(1, $message2->getReplyToRecipients());
        self::assertSame($email, $message2->getReplyToRecipients()[0]->getEmail());
        self::assertSame($name, $message2->getReplyToRecipients()[0]->getName());
    }

    public function testCopyRecipients(): void
    {
        $email = 'cc@example.com';
        $name  = 'CC Recipient';

        $message  = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedCopyRecipient(new Recipient($email, $name));

        self::assertNotSame($message, $message2);

        self::assertEmpty($message->getCopyRecipients());
        self::assertCount(1, $message2->getCopyRecipients());
        self::assertSame($email, $message2->getCopyRecipients()[0]->getEmail());
        self::assertSame($name, $message2->getCopyRecipients()[0]->getName());
    }

    public function testBlindCopyRecipients(): void
    {
        $email = 'bcc@example.com';
        $name  = 'BCC Recipient';

        $message  = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedBlindCopyRecipient(new Recipient($email, $name));

        self::assertNotSame($message, $message2);

        self::assertEmpty($message->getBlindCopyRecipients());
        self::assertCount(1, $message2->getBlindCopyRecipients());
        self::assertSame($email, $message2->getBlindCopyRecipients()[0]->getEmail());
        self::assertSame($name, $message2->getBlindCopyRecipients()[0]->getName());
    }

    public function testAttachments(): void
    {
        $path = '/path/to/file.pdf';
        $name = 'document.pdf';

        $message  = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );
        $message2 = $message->withAddedAttachment(new Attachment($path, $name));

        self::assertNotSame($message, $message2);

        self::assertEmpty($message->getAttachments());
        self::assertCount(1, $message2->getAttachments());
        self::assertSame($path, $message2->getAttachments()[0]->getPath());
        self::assertSame($name, $message2->getAttachments()[0]->getName());
    }

    public function testAttachmentWithDefaultName(): void
    {
        $message = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );

        $message2 = $message->withAddedAttachment(new Attachment('/path/to/file.pdf'));

        self::assertSame('/path/to/file.pdf', $message2->getAttachments()[0]->getPath());
        self::assertNull($message2->getAttachments()[0]->getName());
    }

    public function testChainedWith(): void
    {
        $message = new Message(
            from: new Recipient(self::FROM_EMAIL, self::FROM_NAME),
            subject: self::SUBJECT,
            body: self::BODY
        );

        $message2 = $message
            ->withFrom(new Recipient('new@example.com', 'New Name'))
            ->withSubject('New Subject')
            ->withBody('New Body')
            ->withIsHtml(true)
            ->withPlainBody('Plain text')
            ->withAddedRecipient(new Recipient('to@example.com', 'To'))
            ->withAddedReplyToRecipient(new Recipient('reply@example.com', 'Reply'))
            ->withAddedCopyRecipient(new Recipient('cc@example.com', 'CC'))
            ->withAddedBlindCopyRecipient(new Recipient('bcc@example.com', 'BCC'))
            ->withAddedAttachment(new Attachment('/path/to/file.pdf', 'file.pdf'));

        self::assertNotSame($message, $message2);

        // Original unchanged
        self::assertSame(self::FROM_EMAIL, $message->getFrom()->getEmail());
        self::assertSame(self::FROM_NAME, $message->getFrom()->getName());
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
        self::assertSame('new@example.com', $message2->getFrom()->getEmail());
        self::assertSame('New Name', $message2->getFrom()->getName());
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
