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

use Valkyrja\Broadcast\Broadcaster\NullBroadcaster;
use Valkyrja\Broadcast\Broadcaster\PusherBroadcaster;
use Valkyrja\Broadcast\Data\BroadcastConfig;
use Valkyrja\Broadcast\Data\BroadcastLogConfig;
use Valkyrja\Broadcast\Data\BroadcastPusherConfig;
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
        $config = new BroadcastConfig();

        self::assertSame(PusherBroadcaster::class, $config->defaultBroadcaster);
        self::assertSame('pusher-key', $config->pusher->key);
        self::assertTrue($config->pusher->useTls);
    }

    public function testCustomValuesAreStored(): void
    {
        $pusher = new BroadcastPusherConfig(key: 'test-key');
        $log    = new BroadcastLogConfig();

        $config = new BroadcastConfig(
            defaultBroadcaster: NullBroadcaster::class,
            pusher: $pusher,
            log: $log,
        );

        self::assertSame(NullBroadcaster::class, $config->defaultBroadcaster);
        self::assertSame($pusher, $config->pusher);
        self::assertSame($log, $config->log);
    }
}
