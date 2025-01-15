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

namespace Valkyrja\Log\Adapter;

use Psr\Log\LoggerInterface;
use Throwable;
use Valkyrja\Log\Adapter\Contract\PsrAdapter as Contract;
use Valkyrja\Log\Enum\LogLevel;

/**
 * Class PsrAdapter.
 *
 * @author Melech Mizrachi
 */
class PsrAdapter implements Contract
{
    /**
     * PsrAdapter constructor.
     *
     * @param LoggerInterface      $logger The logger
     * @param array<string, mixed> $config The config
     */
    public function __construct(
        protected LoggerInterface $logger,
        protected array $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log(LogLevel $level, string $message, array $context = []): void
    {
        $this->logger->log($level->value, $message, $context);
    }

    /**
     * @inheritDoc
     */
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
        return md5(serialize($exception));
    }
}
