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

namespace Valkyrja\JWT\Managers;

use Valkyrja\JWT\Driver;
use Valkyrja\JWT\JWT as Contract;
use Valkyrja\JWT\Loader;

/**
 * Class JWT.
 *
 * @author Melech Mizrachi
 */
class JWT implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $drivers = [];

    /**
     * The loader.
     *
     * @var Loader
     */
    protected Loader $loader;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The default algo.
     *
     * @var string
     */
    protected string $default;

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
     * The algorithms.
     *
     * @var array[]
     */
    protected array $algos;

    /**
     * JWT constructor.
     *
     * @param Loader $loader The loader
     * @param array  $config The config
     */
    public function __construct(Loader $loader, array $config)
    {
        $this->loader         = $loader;
        $this->config         = $config;
        $this->default        = $config['default'];
        $this->defaultAdapter = $config['adapter'];
        $this->defaultDriver  = $config['driver'];
        $this->algos          = $config['algos'];
    }

    /**
     * @inheritDoc
     */
    public function useAlgo(string $algo = null): Driver
    {
        // The algo to use
        $algo ??= $this->default;
        // The config to use
        $config = $this->algos[$algo];
        // The driver to use
        $driver = $config['driver'] ?? $this->defaultDriver;
        // The adapter to use
        $adapter = $config['adapter'] ?? $this->defaultAdapter;
        // The cache key to use
        $cacheKey = $algo . $driver . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->loader->createDriver($driver, $adapter, $config);
    }

    /**
     * @inheritDoc
     */
    public function encode(array $payload): string
    {
        return $this->useAlgo()->encode($payload);
    }

    /**
     * @inheritDoc
     */
    public function decode(string $jwt): array
    {
        return $this->useAlgo()->decode($jwt);
    }
}
