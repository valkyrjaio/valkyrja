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

use Valkyrja\Broadcast\Data\BroadcastPusherConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class BroadcastPusherConfigTest extends TestCase
{
    public function testDefaults(): void
    {
        $config = new BroadcastPusherConfig();

        self::assertSame('pusher-key', $config->key);
        self::assertSame('pusher-secret', $config->secret);
        self::assertSame('pusher-id', $config->id);
        self::assertSame('us1', $config->cluster);
        self::assertTrue($config->useTls);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new BroadcastPusherConfig(
            key: 'test-key',
            secret: 'test-secret',
            id: 'test-id',
            cluster: 'eu',
            useTls: false,
        );

        self::assertSame('test-key', $config->key);
        self::assertSame('test-secret', $config->secret);
        self::assertSame('test-id', $config->id);
        self::assertSame('eu', $config->cluster);
        self::assertFalse($config->useTls);
    }
}
