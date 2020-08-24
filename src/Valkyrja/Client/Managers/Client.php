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
     * Use a client by name.
     *
     * @param string|null $name    [optional] The connection name
     * @param string|null $adapter [optional] The adapter
     *
     * @return Driver
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
     * Make a request.
     *
     * @param string $method  The request method
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return Response
     */
    public function request(string $method, string $uri, array $options = []): Response
    {
        return $this->useClient()->request($method, $uri, $options);
    }

    /**
     * Make a get request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return Response
     */
    public function get(string $uri, array $options = []): Response
    {
        return $this->useClient()->get($uri, $options);
    }

    /**
     * Make a post request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return Response
     */
    public function post(string $uri, array $options = []): Response
    {
        return $this->useClient()->post($uri, $options);
    }

    /**
     * Make a head request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return Response
     */
    public function head(string $uri, array $options = []): Response
    {
        return $this->useClient()->head($uri, $options);
    }

    /**
     * Make a put request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return Response
     */
    public function put(string $uri, array $options = []): Response
    {
        return $this->useClient()->put($uri, $options);
    }

    /**
     * Make a patch request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return Response
     */
    public function patch(string $uri, array $options = []): Response
    {
        return $this->useClient()->patch($uri, $options);
    }

    /**
     * Make a delete request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return Response
     */
    public function delete(string $uri, array $options = []): Response
    {
        return $this->useClient()->delete($uri, $options);
    }
}
