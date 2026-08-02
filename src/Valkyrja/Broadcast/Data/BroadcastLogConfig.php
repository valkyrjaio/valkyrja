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

use Valkyrja\Broadcast\Data\Contract\BroadcastLogConfigContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

class BroadcastLogConfig implements BroadcastLogConfigContract
{
    /**
     * @param class-string<LoggerContract> $logLogger The logger to write to
     */
    public function __construct(
        public readonly string $logLogger = LoggerContract::class,
    ) {
    }
}
