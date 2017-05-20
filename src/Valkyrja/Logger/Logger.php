<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Logger;

use Psr\Log\LoggerInterface;
use Valkyrja\Contracts\Logger\Logger as LoggerContract;
use Valkyrja\Logger\Enums\LogLevel;

/**
 * Class Logger.
 *
 * @author Melech Mizrachi
 */
class Logger implements LoggerContract
{
    /**
     * The logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Logger constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger The logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log a debug message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function debug(string $message, array $context = []): LoggerContract
    {
        $this->logger->{LogLevel::DEBUG}($message, $context);

        return $this;
    }

    /**
     * Log an info message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function info(string $message, array $context = []): LoggerContract
    {
        $this->logger->{LogLevel::INFO}($message, $context);

        return $this;
    }

    /**
     * Log a notice message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function notice(string $message, array $context = []): LoggerContract
    {
        $this->logger->{LogLevel::NOTICE}($message, $context);

        return $this;
    }

    /**
     * Log a warning message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function warning(string $message, array $context = []): LoggerContract
    {
        $this->logger->{LogLevel::WARNING}($message, $context);

        return $this;
    }

    /**
     * Log a error message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function error(string $message, array $context = []): LoggerContract
    {
        $this->logger->{LogLevel::ERROR}($message, $context);

        return $this;
    }

    /**
     * Log a critical message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function critical(string $message, array $context = []): LoggerContract
    {
        $this->logger->{LogLevel::CRITICAL}($message, $context);

        return $this;
    }

    /**
     * Log a alert message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function alert(string $message, array $context = []): LoggerContract
    {
        $this->logger->{LogLevel::ALERT}($message, $context);

        return $this;
    }

    /**
     * Log a emergency message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function emergency(string $message, array $context = []): LoggerContract
    {
        $this->logger->{LogLevel::EMERGENCY}($message, $context);

        return $this;
    }

    /**
     * Log a message.
     *
     * @param \Valkyrja\Logger\Enums\LogLevel $level   The log level
     * @param string                          $message The message
     * @param array                           $context [optional] The context
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function log(LogLevel $level, string $message, array $context = []): LoggerContract
    {
        $this->logger->{(string) $level}($message, $context);

        return $this;
    }

    /**
     * Get the logger.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Set the logger.
     *
     * @param \Psr\Log\LoggerInterface $logger The logger
     *
     * @return \Valkyrja\Contracts\Logger\Logger
     */
    public function setLogger(LoggerInterface $logger): LoggerContract
    {
        $this->logger = $logger;

        return $this;
    }
}
