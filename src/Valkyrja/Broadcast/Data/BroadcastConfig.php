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

use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Broadcaster\PusherBroadcaster;
use Valkyrja\Broadcast\Data\Contract\BroadcastConfigContract;

class BroadcastConfig implements BroadcastConfigContract
{
    /**
     * @param class-string<BroadcasterContract> $defaultBroadcaster The broadcaster to use by default
     */
    public function __construct(
        public readonly string $defaultBroadcaster = PusherBroadcaster::class,
        public readonly BroadcastPusherConfig $pusher = new BroadcastPusherConfig(),
        public readonly BroadcastLogConfig $log = new BroadcastLogConfig(),
    ) {
    }
}
