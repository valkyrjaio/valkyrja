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

use JsonException;
use Valkyrja\Client\Adapter as Contract;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Log\Logger;
use Valkyrja\Support\Type\Arr;

/**
 * Class LogAdapter.
 *
 * @author Melech Mizrachi
 */
class LogAdapter implements Contract
{
    /**
     * The logger.
     *
     * @var Logger
     */
    protected Logger $logger;

    /**
     * The response factory.
     *
     * @var ResponseFactory
     */
    protected ResponseFactory $responseFactory;

    /**
     * The client config.
     *
     * @var array
     */
    protected array $config;

    /**
     * LogAdapter constructor.
     *
     * @param Logger          $logger          The logger
     * @param ResponseFactory $responseFactory The response factory
     * @param array           $config          The config
     */
    public function __construct(Logger $logger, ResponseFactory $responseFactory, array $config)
    {
        $this->logger          = $logger;
        $this->responseFactory = $responseFactory;
        $this->config          = $config;
    }

    /**
     * Make a request.
     *
     * @param string $method  The request method
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function request(string $method, string $uri, array $options = []): Response
    {
        $optionsString = Arr::toString($options);

        $this->logger->info(static::class . " request: ${method}, uri ${uri}, options ${$optionsString}");

        return $this->responseFactory->createResponse();
    }

    /**
     * Make a get request.
     *
     * @param string $uri     The uri to request
     * @param array  $options [optional] Custom options for request
     *
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
     *
     * @return Response
     */
    public function delete(string $uri, array $options = []): Response
    {
        return $this->request('delete', $uri, $options);
    }
}
