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

namespace Valkyrja\Tests\Unit\Broadcast\Broadcaster;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Broadcaster\CryptPusherBroadcaster;
use Valkyrja\Broadcast\Data\Message;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Throwable\Exception\CryptException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CryptPusherBroadcasterTest extends TestCase
{
    protected MockObject&Pusher $pusher;

    protected MockObject&CryptContract $crypt;

    protected function setUp(): void
    {
        $this->pusher = $this->createMock(Pusher::class);
        $this->crypt  = $this->createMock(CryptContract::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->pusher->expects($this->never())->method('trigger');
        $this->crypt->expects($this->never())->method('encrypt');
        $broadcaster = new CryptPusherBroadcaster($this->pusher, $this->crypt);

        self::assertInstanceOf(BroadcasterContract::class, $broadcaster);
    }

    /**
     * @throws JsonException
     * @throws GuzzleException
     * @throws ApiErrorException
     * @throws PusherException
     * @throws CryptException
     */
    public function testSendSuccess(): void
    {
        $channel          = 'test-channel';
        $event            = 'test-event';
        $message          = 'Test message';
        $encryptedMessage = 'encrypted-message';

        $broadcastMessage = new Message(
            channel: $channel,
            event: $event,
            message: $message
        );

        $this->crypt
            ->expects($this->once())
            ->method('encrypt')
            ->with($message)
            ->willReturn($encryptedMessage);

        $this->pusher
            ->expects($this->once())
            ->method('trigger')
            ->with($channel, $event, $encryptedMessage);

        $broadcaster = new CryptPusherBroadcaster($this->pusher, $this->crypt);
        $broadcaster->send($broadcastMessage);
    }

    /**
     * @throws JsonException
     * @throws GuzzleException
     * @throws ApiErrorException
     * @throws PusherException
     * @throws CryptException
     */
    public function testSendThrowsExceptionOnCryptFailure(): void
    {
        $broadcastMessage = new Message(
            channel: 'test-channel',
            event: 'test-event',
            message: 'Test message'
        );

        $this->crypt
            ->expects($this->once())
            ->method('encrypt')
            ->willThrowException(new CryptException('Encryption failed'));

        $this->pusher
            ->expects($this->never())
            ->method('trigger');

        $this->expectException(CryptException::class);
        $this->expectExceptionMessage('Encryption failed');

        $broadcaster = new CryptPusherBroadcaster($this->pusher, $this->crypt);
        $broadcaster->send($broadcastMessage);
    }
}
