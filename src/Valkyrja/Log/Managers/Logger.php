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

use Throwable;
use Valkyrja\Container\Container;
use Valkyrja\Log\Adapter;
use Valkyrja\Log\Driver;
use Valkyrja\Log\Logger as Contract;
use Valkyrja\Log\PsrAdapter;
use Valkyrja\Support\Type\Cls;

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
    protected static array $drivers = [];

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
     * The default driver.
     *
     * @var string
     */
    protected string $defaultDriver;

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
        $this->container      = $container;
        $this->config         = $config;
        $this->defaultAdapter = $config['adapter'];
        $this->defaultDriver  = $config['driver'];
        $this->loggers        = $config['loggers'];
        $this->default        = $config['default'];
    }

    /**
     * @inheritDoc
     */
    public function useLogger(string $name = null, string $adapter = null): Driver
    {
        // The logger to use
        $name ??= $this->default;
        // The config to use
        $config = $this->loggers[$name];
        // The driver to use
        $driver ??= $config['driver'] ?? $this->defaultDriver;
        // The adapter to use
        $adapter ??= $config['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->createDriver($driver, $adapter, $config);
    }

    /**
     * @inheritDoc
     */
    public function debug(string $message, array $context = []): void
    {
        $this->useLogger()->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message, array $context = []): void
    {
        $this->useLogger()->info($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $message, array $context = []): void
    {
        $this->useLogger()->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message, array $context = []): void
    {
        $this->useLogger()->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(string $message, array $context = []): void
    {
        $this->useLogger()->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $message, array $context = []): void
    {
        $this->useLogger()->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(string $message, array $context = []): void
    {
        $this->useLogger()->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->useLogger()->emergency($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log(string $level, string $message, array $context = []): void
    {
        $this->useLogger()->log($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function exception(Throwable $exception, string $message, array $context = []): void
    {
        $this->useLogger()->exception($exception, $message, $context);
    }

    /**
     * Get an driver by name.
     *
     * @param string $name    The driver
     * @param string $adapter The adapter
     * @param array  $config  The config
     *
     * @return Driver
     */
    protected function createDriver(string $name, string $adapter, array $config): Driver
    {
        return Cls::getDefaultableService(
            $this->container,
            $name,
            Driver::class,
            [
                $this->createAdapter($adapter, $config),
            ]
        );
    }

    /**
     * Get an adapter by name.
     *
     * @param string $name   The adapter
     * @param array  $config The config
     *
     * @return Adapter
     */
    protected function createAdapter(string $name, array $config): Adapter
    {
        $defaultClass = Adapter::class;

        if (Cls::inherits($name, PsrAdapter::class)) {
            $defaultClass = PsrAdapter::class;
        }

        return Cls::getDefaultableService(
            $this->container,
            $name,
            $defaultClass,
            [
                $config,
            ]
        );
    }
}
