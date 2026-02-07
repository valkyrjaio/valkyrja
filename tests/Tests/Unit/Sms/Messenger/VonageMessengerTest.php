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
use Psr\Http\Client\ClientExceptionInterface;
use Valkyrja\Sms\Data\Message;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\VonageMessenger;
use Valkyrja\Tests\Classes\Vendor\Vonage\ClientClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Vonage\Client;
use Vonage\Client\Exception\Exception;
use Vonage\SMS\Client as SmsClient;
use Vonage\SMS\Message\SMS;

class VonageMessengerTest extends TestCase
{
    protected MockObject&Client $vonageClient;

    protected MockObject&SmsClient $smsClient;

    protected function setUp(): void
    {
        $this->vonageClient = $this->createMock(ClientClass::class);
        $this->smsClient    = $this->createMock(SmsClient::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->vonageClient->expects($this->never())->method('sms');
        $this->smsClient->expects($this->never())->method('send');
        $messenger = new VonageMessenger($this->vonageClient);

        self::assertInstanceOf(MessengerContract::class, $messenger);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testSendSuccess(): void
    {
        $to   = '+15551234567';
        $from = '+15559876543';
        $text = 'Test message';

        $message = new Message(
            to: $to,
            from: $from,
            text: $text,
            isUnicode: true
        );

        $this->vonageClient
            ->expects($this->once())
            ->method('sms')
            ->willReturn($this->smsClient);
        $this->smsClient
            ->expects($this->once())
            ->method('send')
            ->with(self::callback(static fn (SMS $sms): bool => $sms->getTo() === $to
                    && $sms->getFrom() === $from
                    && $sms->getMessage() === $text
                    && $sms->getType() === 'unicode'));

        $messenger = new VonageMessenger($this->vonageClient);
        $messenger->send($message);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testSendThrowsException(): void
    {
        $message = new Message(
            to: '+15551234567',
            from: '+15559876543',
            text: 'Test message'
        );

        $this->vonageClient
            ->expects($this->once())
            ->method('sms')
            ->willReturn($this->smsClient);
        $this->smsClient
            ->expects($this->once())
            ->method('send')
            ->willThrowException(new Exception('Failed to send SMS'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to send SMS');

        $messenger = new VonageMessenger($this->vonageClient);
        $messenger->send($message);
    }
}
