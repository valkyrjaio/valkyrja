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

namespace Valkyrja\Tests\Unit\Sms\Messenger;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Sms\Data\Message;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\LogMessenger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class LogMessengerTest extends TestCase
{
    protected MockObject&LoggerContract $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerContract::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->logger->expects($this->never())->method('info');
        $messenger = new LogMessenger($this->logger);

        self::assertInstanceOf(MessengerContract::class, $messenger);
    }

    public function testSendLogsMessageDetails(): void
    {
        $to   = '+15551234567';
        $from = '+15559876543';
        $text = 'Test message content';

        $message = new Message(
            to: $to,
            from: $from,
            text: $text
        );

        $this->logger
            ->expects($this->exactly(7))
            ->method('info')
            ->willReturnCallback(static function (string $logMessage) use ($from, $to, $text): void {
                static $callIndex = 0;

                $expectedMessages = [
                    LogMessenger::class . ' Send',
                    'From:',
                    $from,
                    'To:',
                    $to,
                    'Text:',
                    $text,
                ];

                self::assertSame($expectedMessages[$callIndex], $logMessage);
                $callIndex++;
            });

        $messenger = new LogMessenger($this->logger);
        $messenger->send($message);
    }

    public function testSendLogsCorrectOrder(): void
    {
        $message = new Message(
            to: '+15551111111',
            from: '+15552222222',
            text: 'Hello World'
        );

        $loggedMessages = [];

        $this->logger
            ->expects($this->exactly(7))
            ->method('info')
            ->willReturnCallback(static function (string $logMessage) use (&$loggedMessages): void {
                $loggedMessages[] = $logMessage;
            });

        $messenger = new LogMessenger($this->logger);
        $messenger->send($message);

        self::assertCount(7, $loggedMessages);
        self::assertStringContainsString('Send', $loggedMessages[0]);
        self::assertSame('From:', $loggedMessages[1]);
        self::assertSame('+15552222222', $loggedMessages[2]);
        self::assertSame('To:', $loggedMessages[3]);
        self::assertSame('+15551111111', $loggedMessages[4]);
        self::assertSame('Text:', $loggedMessages[5]);
        self::assertSame('Hello World', $loggedMessages[6]);
    }
}
