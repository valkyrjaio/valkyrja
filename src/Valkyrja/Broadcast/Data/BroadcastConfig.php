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
    ) {
    }
}
