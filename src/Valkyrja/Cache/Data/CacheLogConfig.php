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

namespace Valkyrja\Cache\Data;

use Valkyrja\Cache\Data\Contract\CacheLogConfigContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

class CacheLogConfig implements CacheLogConfigContract
{
    /**
     * @param class-string<LoggerContract> $logLogger The logger to write to
     * @param string                       $logPrefix The prefix to prepend to every key
     */
    public function __construct(
        public readonly string $logLogger = LoggerContract::class,
        public readonly string $logPrefix = '',
    ) {
    }
}
