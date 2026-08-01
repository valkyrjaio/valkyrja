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

namespace Valkyrja\Broadcast\Data\Contract;

use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Data\BroadcastLogConfig;
use Valkyrja\Broadcast\Data\BroadcastPusherConfig;

interface BroadcastConfigContract
{
    /** @var class-string<BroadcasterContract> */
    public string $defaultBroadcaster {
        get;
    }

    public BroadcastPusherConfig $pusher {
        get;
    }

    public BroadcastLogConfig $log {
        get;
    }
}
