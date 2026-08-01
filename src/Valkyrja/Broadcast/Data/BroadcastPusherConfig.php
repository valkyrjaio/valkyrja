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

class BroadcastPusherConfig
{
    /**
     * @param non-empty-string $key     The Pusher app key
     * @param non-empty-string $secret  The Pusher app secret
     * @param non-empty-string $id      The Pusher app id
     * @param non-empty-string $cluster The Pusher cluster to connect to
     * @param bool             $useTls  Whether to connect over TLS
     */
    public function __construct(
        public readonly string $key = 'pusher-key',
        public readonly string $secret = 'pusher-secret',
        public readonly string $id = 'pusher-id',
        public readonly string $cluster = 'us1',
        public readonly bool $useTls = true,
    ) {
    }
}
