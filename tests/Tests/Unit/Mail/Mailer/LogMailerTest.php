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

use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Mail\Data\Message;
use Valkyrja\Mail\Data\Recipient;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Mail\Mailer\LogMailer;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LogMailerTest extends TestCase
{
    protected MockObject&LoggerContract $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerContract::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->logger->expects($this->never())->method('info');
        $mailer = new LogMailer($this->logger);

        self::assertInstanceOf(MailerContract::class, $mailer);
    }

    /**
     * @throws JsonException
     */
    public function testSendLogsMessageDetails(): void
    {
        $fromEmail = 'sender@example.com';
        $fromName  = 'Sender Name';
        $subject   = 'Test Subject';
        $body      = 'Test body content';

        $message = new Message(
            from: new Recipient($fromEmail, $fromName),
            subject: $subject,
            body: $body
        );

        $loggedMessages = [];

        $this->logger
            ->expects($this->exactly(23))
            ->method('info')
            ->willReturnCallback(static function (string $logMessage) use (&$loggedMessages): void {
                $loggedMessages[] = $logMessage;
            });

        $mailer = new LogMailer($this->logger);
        $mailer->send($message);

        self::assertCount(23, $loggedMessages);
        self::assertStringContainsString('Send', $loggedMessages[0]);
        self::assertSame('From Name:', $loggedMessages[1]);
        self::assertSame($fromName, $loggedMessages[2]);
        self::assertSame('From Email:', $loggedMessages[3]);
        self::assertSame($fromEmail, $loggedMessages[4]);
        self::assertSame('Subject:', $loggedMessages[15]);
        self::assertSame($subject, $loggedMessages[16]);
        self::assertSame('Body:', $loggedMessages[17]);
        self::assertSame($body, $loggedMessages[18]);
    }

    public function testSendLogsHtmlMessage(): void
    {
        $message = new Message(
            from: new Recipient('sender@example.com', 'Sender'),
            subject: 'Subject',
            body: '<p>HTML body</p>'
        )
            ->withIsHtml(true)
            ->withPlainBody('Plain text body');

        $loggedMessages = [];

        $this->logger
            ->expects($this->exactly(23))
            ->method('info')
            ->willReturnCallback(static function (string $logMessage) use (&$loggedMessages): void {
                $loggedMessages[] = $logMessage;
            });

        $mailer = new LogMailer($this->logger);
        $mailer->send($message);

        // Check that isHtml is logged as "1" (true)
        self::assertContains('1', $loggedMessages);
        // Check that plain body is logged
        self::assertContains('Plain text body', $loggedMessages);
    }
}
