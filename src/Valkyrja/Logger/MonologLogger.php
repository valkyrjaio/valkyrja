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
use Valkyrja\Logger\Enums\LogLevel;

/**
 * Class Logger.
 *
 * @author Melech Mizrachi
 */
class MonologLogger implements Logger
{
    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Logger constructor.
     *
     * @param LoggerInterface $logger The logger
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
     * @return Logger
     */
    public function debug(string $message, array $context = []): Logger
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
     * @return Logger
     */
    public function info(string $message, array $context = []): Logger
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
     * @return Logger
     */
    public function notice(string $message, array $context = []): Logger
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
     * @return Logger
     */
    public function warning(string $message, array $context = []): Logger
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
     * @return Logger
     */
    public function error(string $message, array $context = []): Logger
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
     * @return Logger
     */
    public function critical(string $message, array $context = []): Logger
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
     * @return Logger
     */
    public function alert(string $message, array $context = []): Logger
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
     * @return Logger
     */
    public function emergency(string $message, array $context = []): Logger
    {
        $this->logger->{LogLevel::EMERGENCY}($message, $context);

        return $this;
    }

    /**
     * Log a message.
     *
     * @param LogLevel $level   The log level
     * @param string   $message The message
     * @param array    $context [optional] The context
     *
     * @return Logger
     */
    public function log(LogLevel $level, string $message, array $context = []): Logger
    {
        $this->logger->{$level->getValue()}($message, $context);

        return $this;
    }
}
