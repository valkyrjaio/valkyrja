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
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Log\Logger;
use Valkyrja\Support\Type\Obj;

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
     * @param Request $request The request
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function request(Request $request): Response
    {
        $optionsString = Obj::toString($request);

        $this->logger->info(
            static::class . " request: {$request->getMethod()}, uri {$request->getUri()->__toString()}, options ${$optionsString}"
        );

        return $this->responseFactory->createResponse();
    }

    /**
     * Make a get request.
     *
     * @param Request $request The request
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function get(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * Make a post request.
     *
     * @param Request $request The request
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function post(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::POST));
    }

    /**
     * Make a head request.
     *
     * @param Request $request The request
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function head(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::HEAD));
    }

    /**
     * Make a put request.
     *
     * @param Request $request The request
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function put(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::PUT));
    }

    /**
     * Make a patch request.
     *
     * @param Request $request The request
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function patch(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::PATCH));
    }

    /**
     * Make a delete request.
     *
     * @param Request $request The request
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function delete(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::DELETE));
    }
}
