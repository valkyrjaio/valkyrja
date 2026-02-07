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

use Valkyrja\Sms\Data\Message;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\NullMessenger;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NullMessengerTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $messenger = new NullMessenger();

        self::assertInstanceOf(MessengerContract::class, $messenger);
    }

    public function testSendDoesNothing(): void
    {
        $messenger = new NullMessenger();
        $message   = new Message(
            to: '+15551234567',
            from: '+15559876543',
            text: 'Test message'
        );

        // Should not throw any exception
        $messenger->send($message);

        self::assertTrue(true);
    }

    public function testSendMultipleMessages(): void
    {
        $messenger = new NullMessenger();

        $message1 = new Message(to: '+15551111111', from: '+15550000000', text: 'Message 1');
        $message2 = new Message(to: '+15552222222', from: '+15550000000', text: 'Message 2');
        $message3 = new Message(to: '+15553333333', from: '+15550000000', text: 'Message 3');

        // All should execute without error
        $messenger->send($message1);
        $messenger->send($message2);
        $messenger->send($message3);

        self::assertTrue(true);
    }
}
