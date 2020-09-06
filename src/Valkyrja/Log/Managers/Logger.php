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

namespace Valkyrja\Log\Managers;

use Valkyrja\Container\Container;
use Valkyrja\Log\Driver;
use Valkyrja\Log\Logger as Contract;

/**
 * Class Logger.
 *
 * @author Melech Mizrachi
 */
class Logger implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $driversCache = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The adapters.
     *
     * @var string[]
     */
    protected array $adapters;

    /**
     * The drivers config.
     *
     * @var string[]
     */
    protected array $drivers;

    /**
     * The loggers.
     *
     * @var array[]
     */
    protected array $loggers;

    /**
     * The default logger.
     *
     * @var string
     */
    protected string $default;

    /**
     * The key
     *
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * Logger constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container = $container;
        $this->config    = $config;
        $this->adapters  = $config['adapters'];
        $this->drivers   = $config['drivers'];
        $this->loggers   = $config['loggers'];
        $this->default   = $config['default'];
    }

    /**
     * Use a logger by name.
     *
     * @param string|null $name    [optional] The logger name
     * @param string|null $adapter [optional] The adapter
     *
     * @return Driver
     */
    public function useLogger(string $name = null, string $adapter = null): Driver
    {
        // The logger to use
        $name ??= $this->default;
        // The config to use
        $config = $this->loggers[$name];
        // The adapter to use
        $adapter ??= $config['adapter'];
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$driversCache[$cacheKey]
            ?? self::$driversCache[$cacheKey] = $this->container->get(
                $this->drivers[$config['driver']],
                [
                    $config,
                    $this->adapters[$adapter],
                ]
            );
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
        $this->useLogger()->debug($message, $context);
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
        $this->useLogger()->info($message, $context);
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
        $this->useLogger()->notice($message, $context);
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
        $this->useLogger()->warning($message, $context);
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
        $this->useLogger()->error($message, $context);
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
        $this->useLogger()->critical($message, $context);
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
        $this->useLogger()->alert($message, $context);
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
        $this->useLogger()->emergency($message, $context);
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
        $this->useLogger()->log($level, $message, $context);
    }
}
