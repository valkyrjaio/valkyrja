<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Log\Logger;

use Override;
use Psr\Log\LoggerInterface;
use Stringable;
use Throwable;
use Valkyrja\Log\Logger\Abstract\Logger;
use Valkyrja\Throwable\Factory\ThrowableFactory;

class PsrLogger extends Logger
{
    public function __construct(
        protected LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function error(string|Stringable $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwable(Throwable $throwable, string|Stringable $message, array $context = []): void
    {
        $traceCode  = ThrowableFactory::getTraceCode($throwable);
        $logMessage = "\nTrace Code: $traceCode"
            . "\nException Message: {$throwable->getMessage()}"
            . "\nMessage: $message"
            . "\nStack Trace:"
            . "\n=================================="
            . "\n{$throwable->getTraceAsString()}"
            . "\n==================================\n";

        $this->error($logMessage, $context);
    }
}
