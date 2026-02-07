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

namespace Valkyrja\Tests\Unit\Mail\Mailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer as PHPMailerClient;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Mail\Data\Attachment;
use Valkyrja\Mail\Data\Message;
use Valkyrja\Mail\Data\Recipient;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Mail\Mailer\PhpMailer;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class PhpMailerTest extends TestCase
{
    protected MockObject&PHPMailerClient $phpMailerClient;

    protected function setUp(): void
    {
        $this->phpMailerClient = $this->createMock(PHPMailerClient::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->phpMailerClient->expects($this->never())->method('setFrom');
        $this->phpMailerClient->expects($this->never())->method('send');
        $mailer = new PhpMailer($this->phpMailerClient);

        self::assertInstanceOf(MailerContract::class, $mailer);
    }

    /**
     * @throws Exception
     */
    public function testSendSuccess(): void
    {
        $fromEmail      = 'sender@example.com';
        $fromName       = 'Sender Name';
        $subject        = 'Test Subject';
        $body           = 'Test body content';
        $toEmail        = 'to@example.com';
        $toName         = 'To Name';
        $plainBody      = 'Plain body content';
        $attachmentPath = 'attachmentPath';
        $attachmentName = 'attachmentName';

        $message = new Message(
            from: new Recipient($fromEmail, $fromName),
            subject: $subject,
            body: $body
        )
            ->withAddedRecipient(new Recipient($toEmail, $toName))
            ->withPlainBody($plainBody)
            ->withAddedAttachment(new Attachment($attachmentPath, $attachmentName))
            ->withIsHtml(true);

        $this->phpMailerClient
            ->expects($this->once())
            ->method('setFrom')
            ->with($fromEmail, $fromName);

        $this->phpMailerClient
            ->expects($this->once())
            ->method('addAddress')
            ->with($toEmail, $toName);

        $this->phpMailerClient
            ->expects($this->once())
            ->method('addAttachment')
            ->with($attachmentPath, $attachmentName);

        $this->phpMailerClient
            ->expects($this->once())
            ->method('isHTML')
            ->with(true);

        $this->phpMailerClient
            ->expects($this->once())
            ->method('send')
            ->willReturn(true);

        $mailer = new PhpMailer($this->phpMailerClient);
        $mailer->send($message);

        self::assertSame($subject, $this->phpMailerClient->Subject);
        self::assertSame($body, $this->phpMailerClient->Body);
        self::assertSame($plainBody, $this->phpMailerClient->AltBody);
    }

    public function testSendThrowsException(): void
    {
        $message = new Message(
            from: new Recipient('sender@example.com', 'Sender'),
            subject: 'Subject',
            body: 'Body'
        );

        $this->phpMailerClient
            ->method('setFrom');

        $this->phpMailerClient
            ->expects($this->once())
            ->method('send')
            ->willThrowException(new Exception('Failed to send email'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to send email');

        $mailer = new PhpMailer($this->phpMailerClient);
        $mailer->send($message);
    }
}
