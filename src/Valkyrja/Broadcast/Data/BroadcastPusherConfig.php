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

namespace Valkyrja\Broadcast\Data;

use Valkyrja\Broadcast\Data\Contract\BroadcastPusherConfigContract;

class BroadcastPusherConfig implements BroadcastPusherConfigContract
{
    /**
     * @param non-empty-string $pusherKey     The Pusher app key
     * @param non-empty-string $pusherSecret  The Pusher app secret
     * @param non-empty-string $pusherId      The Pusher app id
     * @param non-empty-string $pusherCluster The Pusher cluster to connect to
     * @param bool             $pusherUseTls  Whether to connect over TLS
     */
    public function __construct(
        public readonly string $pusherKey = 'pusher-key',
        public readonly string $pusherSecret = 'pusher-secret',
        public readonly string $pusherId = 'pusher-id',
        public readonly string $pusherCluster = 'us1',
        public readonly bool $pusherUseTls = true,
    ) {
    }
}
