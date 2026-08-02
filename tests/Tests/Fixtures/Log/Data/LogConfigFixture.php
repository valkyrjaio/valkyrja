<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Log\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Log\Data\Contract\LogConfigContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\NullLogger;

final class LogConfigFixture extends Config implements LogConfigContract
{
    /**
     * @param class-string<LoggerContract> $defaultLogger
     */
    public function __construct(
        public string $defaultLogger = NullLogger::class,
    ) {
        parent::__construct();
    }
}
