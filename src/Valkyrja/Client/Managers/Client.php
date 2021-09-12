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

use Valkyrja\Client\Client as Contract;
use Valkyrja\Client\Driver;
use Valkyrja\Container\Container;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

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
     * @var array
     */
    protected array $adapters;

    /**
     * The crypts.
     *
     * @var array
     */
    protected array $clients;

    /**
     * The drivers config.
     *
     * @var array
     */
    protected array $drivers;

    /**
     * The default client.
     *
     * @var string
     */
    protected string $default;

    /**
     * Client constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container = $container;
        $this->config    = $config;
        $this->clients   = $config['clients'];
        $this->adapters  = $config['adapters'];
        $this->drivers   = $config['drivers'];
        $this->default   = $config['default'];
    }

    /**
     * @inheritDoc
     */
    public function useClient(string $name = null, string $adapter = null): Driver
    {
        // The client to use
        $name ??= $this->default;
        // The client config to use
        $config = $this->clients[$name];
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
}
