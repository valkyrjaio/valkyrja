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

use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;

interface BroadcastConfigContract
{
    /** @var class-string<BroadcasterContract> */
    public string $defaultBroadcaster {
        get;
    }
}
