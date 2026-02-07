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
use Valkyrja\Broadcast\Broadcaster\PusherBroadcaster;
use Valkyrja\Broadcast\Data\Message;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Array\Factory\ArrayFactory;

final class PusherBroadcasterTest extends TestCase
{
    protected MockObject&Pusher $pusher;

    protected function setUp(): void
    {
        $this->pusher = $this->createMock(Pusher::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->pusher->expects($this->never())->method('trigger');
        $broadcaster = new PusherBroadcaster($this->pusher);

        self::assertInstanceOf(BroadcasterContract::class, $broadcaster);
    }

    /**
     * @throws JsonException
     * @throws GuzzleException
     * @throws ApiErrorException
     * @throws PusherException
     */
    public function testSendSuccess(): void
    {
        $channel = 'test-channel';
        $event   = 'test-event';
        $message = 'Test message';

        $broadcastMessage = new Message(
            channel: $channel,
            event: $event,
            message: $message
        );

        $this->pusher
            ->expects($this->once())
            ->method('trigger')
            ->with($channel, $event, $message);

        $broadcaster = new PusherBroadcaster($this->pusher);
        $broadcaster->send($broadcastMessage);
    }

    /**
     * @throws JsonException
     * @throws GuzzleException
     * @throws ApiErrorException
     * @throws PusherException
     */
    public function testSendSuccessWithData(): void
    {
        $channel     = 'test-channel';
        $event       = 'test-event';
        $message     = 'Test message';
        $data        = ['key' => 'value'];
        $dataMessage = ArrayFactory::toString($data);

        $broadcastMessage = new Message(
            channel: $channel,
            event: $event,
            message: $message,
            data: $data
        );

        $this->pusher
            ->expects($this->once())
            ->method('trigger')
            ->with($channel, $event, $dataMessage);

        $broadcaster = new PusherBroadcaster($this->pusher);
        $broadcaster->send($broadcastMessage);
    }

    /**
     * @throws JsonException
     * @throws GuzzleException
     * @throws ApiErrorException
     * @throws PusherException
     */
    public function testSendThrowsException(): void
    {
        $broadcastMessage = new Message(
            channel: 'test-channel',
            event: 'test-event',
            message: 'Test message'
        );

        $this->pusher
            ->expects($this->once())
            ->method('trigger')
            ->willThrowException(new PusherException('Failed to broadcast'));

        $this->expectException(PusherException::class);
        $this->expectExceptionMessage('Failed to broadcast');

        $broadcaster = new PusherBroadcaster($this->pusher);
        $broadcaster->send($broadcastMessage);
    }
}
