<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Log\Logger\Abstract;

use Override;
use Stringable;
use Valkyrja\Log\Enum\LogLevel;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Throwable\Exception\LogInvalidLogLevelException;

abstract class Logger implements LoggerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function log($level, string|Stringable $message, array $context = []): void
    {
        if (! $level instanceof LogLevel) {
            throw new LogInvalidLogLevelException('Invalid log level passed. Expecting instance of' . LogLevel::class);
        }

        match ($level) {
            LogLevel::ALERT    => $this->alert($message, $context),
            LogLevel::DEBUG    => $this->debug($message, $context),
            LogLevel::INFO     => $this->info($message, $context),
            LogLevel::NOTICE   => $this->notice($message, $context),
            LogLevel::WARNING  => $this->warning($message, $context),
            LogLevel::ERROR    => $this->error($message, $context),
            LogLevel::CRITICAL => $this->critical($message, $context),
            default            => $this->emergency($message, $context),
        };
    }
}
