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

namespace Valkyrja\Client\Drivers;

use Valkyrja\Client\Adapter;
use Valkyrja\Client\Driver as Contract;
use Valkyrja\Http\Response;

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
        return $this->adapter->request($method, $uri, $options);
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
        return $this->adapter->get($uri, $options);
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
        return $this->adapter->post($uri, $options);
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
        return $this->adapter->head($uri, $options);
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
        return $this->adapter->put($uri, $options);
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
        return $this->adapter->patch($uri, $options);
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
        return $this->adapter->delete($uri, $options);
    }
}
