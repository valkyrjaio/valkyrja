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

use Valkyrja\Log\Logger\Contract\LoggerContract;

class BroadcastLogConfig
{
    /**
     * @param class-string<LoggerContract> $logger The logger to write to
     */
    public function __construct(
        public readonly string $logger = LoggerContract::class,
    ) {
    }
}
