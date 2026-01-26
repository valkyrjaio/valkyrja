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

use Mailgun\Api\Message as MailgunMessageApi;
use Mailgun\Mailgun;
use Mailgun\Message\BatchMessage;
use Mailgun\Message\Exceptions\MissingRequiredParameter;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Mail\Data\Message;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Mail\Mailer\MailgunMailer;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class MailgunMailerTest extends TestCase
{
    protected MockObject&Mailgun $mailgunClient;

    protected MockObject&MailgunMessageApi $mailgunMessageApi;

    protected MockObject&BatchMessage $batchMessage;

    protected function setUp(): void
    {
        $this->mailgunClient     = $this->createMock(Mailgun::class);
        $this->mailgunMessageApi = $this->createMock(MailgunMessageApi::class);
        $this->batchMessage      = $this->createMock(BatchMessage::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->mailgunMessageApi->expects($this->never())->method('getBatchMessage');
        $this->mailgunClient->expects($this->never())->method('messages');
        $this->batchMessage->expects($this->never())->method('setSubject');
        $mailer = new MailgunMailer($this->mailgunClient, 'example.com');

        self::assertInstanceOf(MailerContract::class, $mailer);
    }

    /**
     * @throws MissingRequiredParameter
     */
    public function testSendSuccess(): void
    {
        $domain         = 'example.com';
        $fromEmail      = 'sender@example.com';
        $fromName       = 'Sender Name';
        $subject        = 'Test Subject';
        $body           = 'Test body content';
        $toEmail        = 'to@example.com';
        $toName         = 'To Name';
        $plainBody      = 'Plain body content';
        $attachmentPath = 'attachmentPath';
        $attachmentName = 'attachmentName';
        $count          = 0;

        $message = new Message(
            fromEmail: $fromEmail,
            fromName: $fromName,
            subject: $subject,
            body: $body
        )
            ->withAddedRecipient($toEmail, $toName)
            ->withAddedReplyToRecipient($toEmail, $toName)
            ->withPlainBody($plainBody)
            ->withAddedAttachment($attachmentPath, $attachmentName)
            ->withIsHtml(true);

        $this->mailgunClient
            ->expects($this->once())
            ->method('messages')
            ->willReturn($this->mailgunMessageApi);

        $this->mailgunMessageApi
            ->expects($this->once())
            ->method('getBatchMessage')
            ->with($domain)
            ->willReturn($this->batchMessage);

        $this->batchMessage
            ->expects($this->once())
            ->method('setSubject')
            ->with($subject);

        $this->batchMessage
            ->expects($this->exactly(2))
            ->method('setTextBody')
            ->with(
                self::callback(
                    static function (string $param) use ($body, $plainBody, &$count): bool {
                        if ($count === 0) {
                            $count++;

                            return $body === $param;
                        }

                        return $plainBody === $param;
                    }
                )
            );

        $this->batchMessage
            ->expects($this->once())
            ->method('setHtmlBody')
            ->with($body);

        $this->batchMessage
            ->expects($this->once())
            ->method('setFromAddress')
            ->with($fromEmail, ['full_name' => $fromName]);

        $this->batchMessage
            ->expects($this->once())
            ->method('addToRecipient')
            ->with($toEmail, ['full_name' => $toName]);

        $this->batchMessage
            ->expects($this->once())
            ->method('setReplyToAddress')
            ->with($toEmail, ['full_name' => $toName]);

        $this->batchMessage
            ->expects($this->once())
            ->method('addAttachment')
            ->with($attachmentPath, $attachmentName);

        $this->batchMessage
            ->expects($this->once())
            ->method('finalize');

        $mailer = new MailgunMailer($this->mailgunClient, $domain);
        $mailer->send($message);
    }

    public function testSendThrowsException(): void
    {
        $message = new Message(
            fromEmail: 'sender@example.com',
            fromName: 'Sender',
            subject: 'Subject',
            body: 'Body'
        );

        $this->mailgunClient
            ->expects($this->once())
            ->method('messages')
            ->willReturn($this->mailgunMessageApi);

        $this->mailgunMessageApi
            ->expects($this->once())
            ->method('getBatchMessage')
            ->willReturn($this->batchMessage);

        $this->batchMessage
            ->method('setSubject');

        $this->batchMessage
            ->method('setTextBody');

        $this->batchMessage
            ->expects($this->once())
            ->method('finalize')
            ->willThrowException(new MissingRequiredParameter('Missing required parameter'));

        $this->expectException(MissingRequiredParameter::class);
        $this->expectExceptionMessage('Missing required parameter');

        $mailer = new MailgunMailer($this->mailgunClient, 'example.com');
        $mailer->send($message);
    }
}
