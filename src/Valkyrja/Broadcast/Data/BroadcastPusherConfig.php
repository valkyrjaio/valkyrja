<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
