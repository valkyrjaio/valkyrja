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

namespace Valkyrja\Manager;

use Valkyrja\Manager\Adapter\Contract\Adapter;
use Valkyrja\Manager\Contract\Manager as Contract;
use Valkyrja\Manager\Driver\Contract\Driver;
use Valkyrja\Manager\Factory\Contract\Factory;

/**
 * Class Manager.
 *
 * @template Adapter of Adapter
 * @template Driver of Driver
 * @template Factory of Factory
 *
 * @author Melech Mizrachi
 *
 * @implements Contract<Adapter, Driver, Factory>
 */
abstract class Manager implements Contract
{
    /**
     * The drivers.
     *
     * @var array<string, Driver>
     */
    protected array $drivers = [];

    /**
     * The default adapter.
     *
     * @var class-string<Adapter>
     */
    protected string $defaultAdapter;

    /**
     * The default driver.
     *
     * @var class-string<Driver>
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
     * @var array<string, array<string, mixed>>
     */
    protected array $configurations;

    /**
     * Manager constructor.
     *
     * @param Factory                     $factory The factory
     * @param Config|array<string, mixed> $config  The config
     */
    public function __construct(
        protected Factory $factory,
        protected Config|array $config
    ) {
        $this->defaultConfiguration = $config['default'];
        $this->defaultAdapter       = $config['adapter'];
        $this->defaultDriver        = $config['driver'];
        $this->configurations       = $config['configurations'] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function use(?string $name = null): Driver
    {
        // The configuration name to use
        $name ??= $this->defaultConfiguration;
        // The config to use
        $config = $this->configurations[$name];
        // The driver to use
        /** @var class-string<Driver> $driverClass */
        $driverClass = $config['driver'] ?? $this->defaultDriver;
        // The adapter to use
        /** @var class-string<Adapter> $adapterClass */
        $adapterClass = $config['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $name . $adapterClass;

        if (! isset($this->drivers[$cacheKey])) {
            /** @var Driver $driver */
            $driver = $this->factory->createDriver($driverClass, $adapterClass, $config);

            $this->drivers[$cacheKey] = $driver;
        }

        return $this->drivers[$cacheKey];
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
