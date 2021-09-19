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

namespace Valkyrja\Client\Managers;

use Valkyrja\Client\Adapter;
use Valkyrja\Client\Client as Contract;
use Valkyrja\Client\Driver;
use Valkyrja\Client\GuzzleAdapter;
use Valkyrja\Client\LogAdapter;
use Valkyrja\Container\Container;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Support\Type\Cls;

/**
 * Class Client.
 *
 * @author Melech Mizrachi
 */
class Client implements Contract
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
     * The adapters.
     *
     * @var string
     */
    protected string $defaultAdapter;

    /**
     * The crypts.
     *
     * @var array
     */
    protected array $clients;

    /**
     * The drivers config.
     *
     * @var string
     */
    protected string $defaultDriver;

    /**
     * The default client.
     *
     * @var string
     */
    protected string $defaultClient;

    /**
     * Client constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container      = $container;
        $this->config         = $config;
        $this->clients        = $config['clients'];
        $this->defaultAdapter = $config['adapter'];
        $this->defaultDriver  = $config['driver'];
        $this->defaultClient  = $config['default'];
    }

    /**
     * @inheritDoc
     */
    public function useClient(string $name = null, string $adapter = null): Driver
    {
        // The client to use
        $name ??= $this->defaultClient;
        // The client config to use
        $config = $this->clients[$name];
        // The adapter to use
        $adapter ??= $config['adapter'] ?? $this->defaultAdapter;
        // Get the driver
        $driver = $config['driver'] ?? $this->defaultDriver;
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$drivers[$cacheKey]
            ?? self::$drivers[$cacheKey] = $this->createDriver($driver, $adapter, $config);
    }

    /**
     * @inheritDoc
     */
    public function request(Request $request): Response
    {
        return $this->useClient()->request($request);
    }

    /**
     * @inheritDoc
     */
    public function get(Request $request): Response
    {
        return $this->useClient()->get($request);
    }

    /**
     * @inheritDoc
     */
    public function post(Request $request): Response
    {
        return $this->useClient()->post($request);
    }

    /**
     * @inheritDoc
     */
    public function head(Request $request): Response
    {
        return $this->useClient()->head($request);
    }

    /**
     * @inheritDoc
     */
    public function put(Request $request): Response
    {
        return $this->useClient()->put($request);
    }

    /**
     * @inheritDoc
     */
    public function patch(Request $request): Response
    {
        return $this->useClient()->patch($request);
    }

    /**
     * @inheritDoc
     */
    public function delete(Request $request): Response
    {
        return $this->useClient()->delete($request);
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

        if (Cls::inherits($name, GuzzleAdapter::class)) {
            $defaultClass = GuzzleAdapter::class;
        } elseif (Cls::inherits($name, LogAdapter::class)) {
            $defaultClass = LogAdapter::class;
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
