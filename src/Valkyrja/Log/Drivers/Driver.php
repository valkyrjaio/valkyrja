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

namespace Valkyrja\Log\Drivers;

use Valkyrja\Log\Adapter;
use Valkyrja\Log\Driver as Contract;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
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
        $this->adapter->debug($message, $context);
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
        $this->adapter->info($message, $context);
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
        $this->adapter->notice($message, $context);
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
        $this->adapter->warning($message, $context);
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
        $this->adapter->error($message, $context);
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
        $this->adapter->critical($message, $context);
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
        $this->adapter->alert($message, $context);
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
        $this->adapter->emergency($message, $context);
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
        $this->adapter->log($level, $message, $context);
    }
}
