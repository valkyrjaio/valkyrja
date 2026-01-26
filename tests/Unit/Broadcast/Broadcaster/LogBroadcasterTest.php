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

use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Broadcaster\LogBroadcaster;
use Valkyrja\Broadcast\Data\Message;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class LogBroadcasterTest extends TestCase
{
    protected MockObject&LoggerContract $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerContract::class);
    }

    public function testInstanceOfContract(): void
    {
        $this->logger->expects($this->never())->method('info');
        $broadcaster = new LogBroadcaster($this->logger);

        self::assertInstanceOf(BroadcasterContract::class, $broadcaster);
    }

    /**
     * @throws JsonException
     */
    public function testSendLogsMessage(): void
    {
        $channel = 'test-channel';
        $event   = 'test-event';
        $message = 'Test message';
        $data    = ['key' => 'value'];

        $broadcastMessage = new Message(
            channel: $channel,
            event: $event,
            message: $message,
            data: $data
        );

        // Expect 9 info log calls
        $this->logger
            ->expects($this->exactly(9))
            ->method('info');

        $broadcaster = new LogBroadcaster($this->logger);
        $broadcaster->send($broadcastMessage);
    }

    /**
     * @throws JsonException
     */
    public function testSendLogsMessageWithNullData(): void
    {
        $channel = 'test-channel';
        $event   = 'test-event';
        $message = 'Test message';

        $broadcastMessage = new Message(
            channel: $channel,
            event: $event,
            message: $message
        );

        // Expect 9 info log calls even with null data
        $this->logger
            ->expects($this->exactly(9))
            ->method('info');

        $broadcaster = new LogBroadcaster($this->logger);
        $broadcaster->send($broadcastMessage);
    }
}
