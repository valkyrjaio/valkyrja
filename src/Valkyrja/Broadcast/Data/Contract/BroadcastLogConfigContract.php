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

use Valkyrja\Log\Logger\Contract\LoggerContract;

interface BroadcastLogConfigContract
{
    /** @var class-string<LoggerContract> */
    public string $logLogger {
        get;
    }
}
