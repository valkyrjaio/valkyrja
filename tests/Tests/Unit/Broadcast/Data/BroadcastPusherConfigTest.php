<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Broadcast\Data;

use Valkyrja\Broadcast\Data\BroadcastPusherConfig;
use Valkyrja\Broadcast\Data\Contract\BroadcastPusherConfigContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class BroadcastPusherConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(BroadcastPusherConfigContract::class, new BroadcastPusherConfig());
    }

    public function testDefaults(): void
    {
        $config = new BroadcastPusherConfig();

        self::assertSame('pusher-key', $config->pusherKey);
        self::assertSame('pusher-secret', $config->pusherSecret);
        self::assertSame('pusher-id', $config->pusherId);
        self::assertSame('us1', $config->pusherCluster);
        self::assertTrue($config->pusherUseTls);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new BroadcastPusherConfig(
            pusherKey: 'test-key',
            pusherSecret: 'test-secret',
            pusherId: 'test-id',
            pusherCluster: 'eu',
            pusherUseTls: false,
        );

        self::assertSame('test-key', $config->pusherKey);
        self::assertSame('test-secret', $config->pusherSecret);
        self::assertSame('test-id', $config->pusherId);
        self::assertSame('eu', $config->pusherCluster);
        self::assertFalse($config->pusherUseTls);
    }
}
