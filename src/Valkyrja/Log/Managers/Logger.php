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
use Valkyrja\Log\Adapter;
use Valkyrja\Log\Logger as Contract;

/**
 * Class Logger.
 *
 * @author Melech Mizrachi
 */
class Logger implements Contract
{
    /**
     * The adapters.
     *
     * @var Adapter[]
     */
    protected static array $adapters = [];

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
     * The default adapter.
     *
     * @var string
     */
    protected string $defaultAdapter;

    /**
     * The key
     *
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * Crypt constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container      = $container;
        $this->config         = $config;
        $this->defaultAdapter = $config['adapter'];
    }

    /**
     * Get an adapter by name.
     *
     * @param string|null $name The adapter name
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter
    {
        $name ??= $this->defaultAdapter;

        return self::$adapters[$name]
            ?? self::$adapters[$name] = $this->container->getSingleton(
                $this->config['adapters'][$name]
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
        $this->getAdapter()->debug($message, $context);
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
        $this->getAdapter()->info($message, $context);
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
        $this->getAdapter()->notice($message, $context);
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
        $this->getAdapter()->warning($message, $context);
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
        $this->getAdapter()->error($message, $context);
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
        $this->getAdapter()->critical($message, $context);
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
        $this->getAdapter()->alert($message, $context);
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
        $this->getAdapter()->emergency($message, $context);
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
        $this->getAdapter()->log($level, $message, $context);
    }
}
