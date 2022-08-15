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

namespace Valkyrja\Support\Manager\Managers;

use Valkyrja\Support\Manager\Factory;
use Valkyrja\Support\Manager\Driver;
use Valkyrja\Support\Manager\Manager as Contract;

/**
 * Class Manager.
 *
 * @author Melech Mizrachi
 */
abstract class Manager implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $drivers = [];

    /**
     * The factory.
     *
     * @var Factory
     */
    protected Factory $factory;

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
     * The default configuration.
     *
     * @var string
     */
    protected string $defaultConfiguration;

    /**
     * The configurations.
     *
     * @var array
     */
    protected array $configurations;

    /**
     * Manager constructor.
     *
     * @param Factory $factory The factory
     * @param array   $config  The config
     */
    public function __construct(Factory $factory, array $config)
    {
        $this->factory              = $factory;
        $this->config               = $config;
        $this->defaultConfiguration = $config['default'];
        $this->defaultAdapter       = $config['adapter'];
        $this->defaultDriver        = $config['driver'];
        $this->configurations       = $config['configurations'] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function use(string $name = null): Driver
    {
        // The configuration name to use
        $name ??= $this->defaultConfiguration;
        // The config to use
        $config = $this->configurations[$name];
        // The driver to use
        $driver = $config['driver'] ?? $this->defaultDriver;
        // The adapter to use
        $adapter = $config['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->factory->createDriver($driver, $adapter, $config);
    }

    /**
     * Get the loader.
     *
     * @return Factory
     */
    public function getFactory(): Factory
    {
        return $this->factory;
    }
}
