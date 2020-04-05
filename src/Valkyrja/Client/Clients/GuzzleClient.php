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

namespace Valkyrja\Client\Clients;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Valkyrja\Client\Client as Contract;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provides;

/**
 * Class GuzzleClient.
 *
 * @author Melech Mizrachi
 */
class GuzzleClient implements Contract
{
    use Provides;

    /**
     * The guzzle client.
     *
     * @var ClientInterface
     */
    protected ClientInterface $guzzle;

    /**
     * Client constructor.
     *
     * @param ClientInterface $guzzle The guzzle client
     */
    public function __construct(ClientInterface $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $container->setSingleton(
            Contract::class,
            new static(new Guzzle())
        );
    }

    /**
     * Make a request.
     *
     * @param string $method  The request method
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
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
     * @return ResponseInterface
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
     * @return ResponseInterface
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
     * @return ResponseInterface
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
     * @return ResponseInterface
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
     * @return ResponseInterface
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
     * @return ResponseInterface
     */
    public function delete(string $uri, array $options = []): ResponseInterface
    {
        return $this->guzzle->delete($uri, $options);
    }
}
