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

namespace Valkyrja\Client\Adapters;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Valkyrja\Client\Adapter as Contract;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;

/**
 * Class GuzzleAdapter.
 *
 * @author Melech Mizrachi
 */
class GuzzleAdapter implements Contract
{
    /**
     * The guzzle client.
     *
     * @var ClientInterface
     */
    protected ClientInterface $guzzle;

    /**
     * The response factory.
     *
     * @var ResponseFactory
     */
    protected ResponseFactory $responseFactory;

    /**
     * GuzzleAdapter constructor.
     *
     * @param ClientInterface $guzzle          The guzzle client
     * @param ResponseFactory $responseFactory The response factory
     */
    public function __construct(ClientInterface $guzzle, ResponseFactory $responseFactory)
    {
        $this->guzzle          = $guzzle;
        $this->responseFactory = $responseFactory;
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
     * @return Response
     */
    public function request(string $method, string $uri, array $options = []): Response
    {
        return $this->fromPsr7($this->guzzle->request($method, $uri, $options));
    }

    /**
     * Make a get request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function get(string $uri, array $options = []): Response
    {
        return $this->request('get', $uri, $options);
    }

    /**
     * Make a post request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function post(string $uri, array $options = []): Response
    {
        return $this->request('post', $uri, $options);
    }

    /**
     * Make a head request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function head(string $uri, array $options = []): Response
    {
        return $this->request('head', $uri, $options);
    }

    /**
     * Make a put request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function put(string $uri, array $options = []): Response
    {
        return $this->request('put', $uri, $options);
    }

    /**
     * Make a patch request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function patch(string $uri, array $options = []): Response
    {
        return $this->request('patch', $uri, $options);
    }

    /**
     * Make a delete request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function delete(string $uri, array $options = []): Response
    {
        return $this->request('delete', $uri, $options);
    }

    /**
     * Convert a Guzzle Response to Valkyrja Response.
     *
     * @param ResponseInterface $guzzleResponse The Guzzle Response
     *
     * @return Response
     */
    protected function fromPsr7(ResponseInterface $guzzleResponse): Response
    {
        return $this->responseFactory->createResponse(
            $guzzleResponse->getBody()->getContents(),
            $guzzleResponse->getStatusCode(),
            $guzzleResponse->getHeaders()
        );
    }
}