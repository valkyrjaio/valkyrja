<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
