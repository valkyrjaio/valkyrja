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

/**
 * Interface Logger.
 *
 * @author Melech Mizrachi
 */
interface Logger
{
    /**
     * Log a debug message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function debug(string $message, array $context = []): self;

    /**
     * Log an info message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function info(string $message, array $context = []): self;

    /**
     * Log a notice message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function notice(string $message, array $context = []): self;

    /**
     * Log a warning message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function warning(string $message, array $context = []): self;

    /**
     * Log a error message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function error(string $message, array $context = []): self;

    /**
     * Log a critical message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function critical(string $message, array $context = []): self;

    /**
     * Log a alert message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function alert(string $message, array $context = []): self;

    /**
     * Log a emergency message.
     *
     * @param string $message The message
     * @param array  $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function emergency(string $message, array $context = []): self;

    /**
     * Log a message.
     *
     * @param LogLevel $level   The log level
     * @param string   $message The message
     * @param array    $context [optional] The context
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function log(LogLevel $level, string $message, array $context = []): self;

    /**
     * Get the logger.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface;

    /**
     * Set the logger.
     *
     * @param \Psr\Log\LoggerInterface $logger The logger
     *
     * @return \Valkyrja\Logger\Logger
     */
    public function setLogger(LoggerInterface $logger): self;
}
