<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Broadcast\Broadcaster;

use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Broadcaster\NullBroadcaster;
use Valkyrja\Broadcast\Data\Message;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NullBroadcasterTest extends TestCase
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
        $this->expectNotToPerformAssertions();
    }
}
