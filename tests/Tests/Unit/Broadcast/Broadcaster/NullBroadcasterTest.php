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

use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Broadcaster\NullBroadcaster;
use Valkyrja\Broadcast\Data\Message;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class NullBroadcasterTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $broadcaster = new NullBroadcaster();

        self::assertInstanceOf(BroadcasterContract::class, $broadcaster);
    }

    public function testSendDoesNothing(): void
    {
        $broadcaster = new NullBroadcaster();
        $message     = new Message(
            channel: 'test-channel',
            event: 'test-event',
            message: 'Test message'
        );

        // Should not throw any exceptions
        $broadcaster->send($message);

        // If we get here, the test passes (no exceptions thrown)
        self::assertTrue(true);
    }
}
