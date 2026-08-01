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

interface BroadcastPusherConfigContract
{
    /** @var non-empty-string */
    public string $pusherKey {
        get;
    }

    /** @var non-empty-string */
    public string $pusherSecret {
        get;
    }

    /** @var non-empty-string */
    public string $pusherId {
        get;
    }

    /** @var non-empty-string */
    public string $pusherCluster {
        get;
    }

    public bool $pusherUseTls {
        get;
    }
}
