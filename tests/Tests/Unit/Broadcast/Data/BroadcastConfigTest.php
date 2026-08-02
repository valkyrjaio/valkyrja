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

use Valkyrja\Broadcast\Broadcaster\NullBroadcaster;
use Valkyrja\Broadcast\Broadcaster\PusherBroadcaster;
use Valkyrja\Broadcast\Data\BroadcastConfig;
use Valkyrja\Broadcast\Data\Contract\BroadcastConfigContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class BroadcastConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(BroadcastConfigContract::class, new BroadcastConfig());
    }

    public function testDefaults(): void
    {
        self::assertSame(PusherBroadcaster::class, new BroadcastConfig()->defaultBroadcaster);
    }

    public function testCustomValuesAreStored(): void
    {
        self::assertSame(
            NullBroadcaster::class,
            new BroadcastConfig(defaultBroadcaster: NullBroadcaster::class)->defaultBroadcaster
        );
    }
}
