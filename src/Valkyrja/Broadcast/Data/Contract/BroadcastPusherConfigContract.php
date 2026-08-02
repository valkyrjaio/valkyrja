<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
