<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Client;

use GuzzleHttp\Client as Guzzle;
use Psr\Http\Message\ResponseInterface;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Support\Provides;

/**
 * Class Client.
 *
 * @author Melech Mizrachi
 */
class GuzzleClient implements Client
{
    use Provides;

    /**
     * The guzzle client.
     *
     * @var \Guzzle\Http\Client
     */
    protected $guzzle;

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client $guzzle The guzzle client
     */
    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Make a request.
     *
     * @param string $method  The request method
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->guzzle->request($method, $uri, $options);
    }

    /**
     * Make a get request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(string $uri, array $options = []): ResponseInterface
    {
        return $this->guzzle->get($uri, $options);
    }

    /**
     * Make a post request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post(string $uri, array $options = []): ResponseInterface
    {
        return $this->guzzle->post($uri, $options);
    }

    /**
     * Make a head request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function head(string $uri, array $options = []): ResponseInterface
    {
        return $this->guzzle->head($uri, $options);
    }

    /**
     * Make a put request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function put(string $uri, array $options = []): ResponseInterface
    {
        return $this->guzzle->put($uri, $options);
    }

    /**
     * Make a patch request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function patch(string $uri, array $options = []): ResponseInterface
    {
        return $this->guzzle->patch($uri, $options);
    }

    /**
     * Make a delete request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(string $uri, array $options = []): ResponseInterface
    {
        return $this->guzzle->delete($uri, $options);
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::CLIENT,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::CLIENT,
            new static(new Guzzle())
        );
    }
}
