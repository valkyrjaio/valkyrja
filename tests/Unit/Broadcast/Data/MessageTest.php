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

namespace Valkyrja\Tests\Unit\Broadcast\Data;

use Valkyrja\Broadcast\Data\Contract\MessageContract;
use Valkyrja\Broadcast\Data\Message;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class MessageTest extends TestCase
{
    protected string $channel = 'test-channel';
    protected string $event   = 'test-event';
    protected string $message = 'Test message';

    /** @var array<string, mixed> */
    protected array $data = ['key' => 'value'];

    public function testDefaults(): void
    {
        $message = new Message(
            channel: $this->channel,
            event: $this->event,
            message: $this->message
        );

        self::assertInstanceOf(MessageContract::class, $message);
        self::assertSame($this->channel, $message->getChannel());
        self::assertSame($this->event, $message->getEvent());
        self::assertSame($this->message, $message->getMessage());
        self::assertNull($message->getData());
    }

    public function testWithData(): void
    {
        $message = new Message(
            channel: $this->channel,
            event: $this->event,
            message: $this->message,
            data: $this->data
        );

        self::assertSame($this->data, $message->getData());
    }

    public function testChannel(): void
    {
        $message = new Message(
            channel: $this->channel,
            event: $this->event,
            message: $this->message
        );

        $newChannel = 'new-channel';
        $newMessage = $message->withChannel($newChannel);

        self::assertNotSame($message, $newMessage);
        self::assertSame($this->channel, $message->getChannel());
        self::assertSame($newChannel, $newMessage->getChannel());
        self::assertSame($this->event, $newMessage->getEvent());
        self::assertSame($this->message, $newMessage->getMessage());
        self::assertNull($newMessage->getData());
    }

    public function testEvent(): void
    {
        $message = new Message(
            channel: $this->channel,
            event: $this->event,
            message: $this->message
        );

        $newEvent   = 'new-event';
        $newMessage = $message->withEvent($newEvent);

        self::assertNotSame($message, $newMessage);
        self::assertSame($this->event, $message->getEvent());
        self::assertSame($newEvent, $newMessage->getEvent());
        self::assertSame($this->channel, $newMessage->getChannel());
        self::assertSame($this->message, $newMessage->getMessage());
        self::assertNull($newMessage->getData());
    }

    public function testMessage(): void
    {
        $message = new Message(
            channel: $this->channel,
            event: $this->event,
            message: $this->message
        );

        $newMessageText = 'New message text';
        $newMessage     = $message->withMessage($newMessageText);

        self::assertNotSame($message, $newMessage);
        self::assertSame($this->message, $message->getMessage());
        self::assertSame($newMessageText, $newMessage->getMessage());
        self::assertSame($this->channel, $newMessage->getChannel());
        self::assertSame($this->event, $newMessage->getEvent());
        self::assertNull($newMessage->getData());
    }

    public function testData(): void
    {
        $message = new Message(
            channel: $this->channel,
            event: $this->event,
            message: $this->message
        );

        $newData    = ['newKey' => 'newValue'];
        $newMessage = $message->withData($newData);

        self::assertNotSame($message, $newMessage);
        self::assertNull($message->getData());
        self::assertSame($newData, $newMessage->getData());
        self::assertSame($this->channel, $newMessage->getChannel());
        self::assertSame($this->event, $newMessage->getEvent());
        self::assertSame($this->message, $newMessage->getMessage());
    }

    public function testDataSetToNull(): void
    {
        $message = new Message(
            channel: $this->channel,
            event: $this->event,
            message: $this->message,
            data: $this->data
        );

        $newMessage = $message->withData(null);

        self::assertNotSame($message, $newMessage);
        self::assertSame($this->data, $message->getData());
        self::assertNull($newMessage->getData());
    }
}
