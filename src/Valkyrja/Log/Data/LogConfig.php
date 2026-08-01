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

namespace Valkyrja\Log\Data;

use Valkyrja\Log\Data\Contract\LogConfigContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\PsrLogger;

class LogConfig implements LogConfigContract
{
    /**
     * @param class-string<LoggerContract> $defaultLogger The logger to use by default
     */
    public function __construct(
        public readonly string $defaultLogger = PsrLogger::class,
    ) {
    }
}
