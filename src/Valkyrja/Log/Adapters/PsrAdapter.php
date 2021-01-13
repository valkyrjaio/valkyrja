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

namespace Valkyrja\Log\Adapters;

use Throwable;
use Psr\Log\LoggerInterface;
use Valkyrja\Log\Adapter as Contract;

/**
 * Class PsrAdapter.
 *
 * @author Melech Mizrachi
 */
class PsrAdapter implements Contract
{
    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * PsrAdapter constructor.
     *
     * @param LoggerInterface $logger The logger
     * @param array           $config The config
     */
    public function __construct(LoggerInterface $logger, array $config)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Log a debug message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    /**
     * Log an info message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * Log a notice message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function notice(string $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    /**
     * Log a warning message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * Log a error message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * Log a critical message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    /**
     * Log a alert message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function alert(string $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    /**
     * Log a emergency message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * Log a message.
     *
     * @param string $level   The log level
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }

    /**
     * Log an exception or throwable.
     *
     * @param Throwable $exception The exception
     * @param string    $message   The message
     * @param array     $context   [optional] The context
     *
     * @return void
     */
    public function exception(Throwable $exception, string $message, array $context = []): void
    {
        $traceCode  = $this->getExceptionTraceCode($exception);
        $logMessage = "\nTrace Code: {$traceCode}" .
            "\nException Message: {$exception->getMessage()}" .
            "\nMessage: {$message}" .
            "\nStack Trace:" .
            "\n==================================" .
            "\n{$exception->getTraceAsString()}" .
            "\n==================================\n";

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
