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

namespace Valkyrja\Log;

use Override;
use Psr\Log\LoggerInterface;
use Throwable;
use Valkyrja\Log\Contract\Logger as Contract;
use Valkyrja\Log\Enum\LogLevel;

/**
 * Class PsrLogger.
 *
 * @author Melech Mizrachi
 */
class PsrLogger implements Contract
{
    /**
     * PsrAdapter constructor.
     */
    public function __construct(
        protected LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function notice(string $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function critical(string $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function alert(string $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function emergency(string $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function log(LogLevel $level, string $message, array $context = []): void
    {
        $this->logger->log($level->value, $message, $context);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function exception(Throwable $exception, string $message, array $context = []): void
    {
        $traceCode  = $this->getExceptionTraceCode($exception);
        $logMessage = "\nTrace Code: $traceCode"
            . "\nException Message: {$exception->getMessage()}"
            . "\nMessage: $message"
            . "\nStack Trace:"
            . "\n=================================="
            . "\n{$exception->getTraceAsString()}"
            . "\n==================================\n";

        $this->error($logMessage, $context);
    }

    /**
     * Get exception trace code.
     *
     * @param Throwable $exception The exception
     *
     * @return string
     */
    protected function getExceptionTraceCode(Throwable $exception): string
    {
        return md5($exception::class . $exception->getTraceAsString());
    }
}
