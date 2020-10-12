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
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Valkyrja\Client\Adapter as Contract;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Request;
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
     * The client config.
     *
     * @var array
     */
    protected array $config;

    /**
     * GuzzleAdapter constructor.
     *
     * @param ClientInterface $guzzle          The guzzle client
     * @param ResponseFactory $responseFactory The response factory
     * @param array           $config          The client config
     */
    public function __construct(ClientInterface $guzzle, ResponseFactory $responseFactory, array $config)
    {
        $this->guzzle          = $guzzle;
        $this->responseFactory = $responseFactory;
        $this->config          = $config;
    }

    /**
     * Make a request.
     *
     * @param Request $request The request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function request(Request $request): Response
    {
        return $this->fromPsr7($this->getGuzzleResponse($request));
    }

    /**
     * Make a get request.
     *
     * @param Request $request The request
     *
     * @throws GuzzleException
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
     * @throws GuzzleException
     *
     * @return Response
     */
    public function post(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * Make a head request.
     *
     * @param Request $request The request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function head(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * Make a put request.
     *
     * @param Request $request The request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function put(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * Make a patch request.
     *
     * @param Request $request The request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function patch(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * Make a delete request.
     *
     * @param Request $request The request
     *
     * @throws GuzzleException
     *
     * @return Response
     */
    public function delete(Request $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * Get a Guzzle response from a Valkyrja request.
     *
     * @param Request $request The request
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    protected function getGuzzleResponse(Request $request): ResponseInterface
    {
        $options = $this->config['options'] ?? [];

        $this->setGuzzleHeaders($request, $options);
        $this->setGuzzleCookies($request, $options);
        $this->setGuzzleFormParams($request, $options);

        return $this->guzzle->request($request->getMethod(), $request->getUri()->__toString(), $options);
    }

    /**
     * Set the Guzzle headers.
     *
     * @param Request  $request The request
     * @param array   &$options The options
     *
     * @return void
     */
    protected function setGuzzleHeaders(Request $request, array &$options): void
    {
        if ($headers = $request->getHeaders()) {
            $options['headers'] = [];

            foreach ($headers as $header) {
                $options['headers'][] = $header;
            }
        }
    }

    /**
     * Set the Guzzle cookies.
     *
     * @param Request  $request The request
     * @param array   &$options The options
     *
     * @return void
     */
    protected function setGuzzleCookies(Request $request, array &$options): void
    {
        if ($cookies = $request->getCookieParams()) {
            $jar = new CookieJar();

            foreach ($cookies as $name => $value) {
                $guzzleCookie = new SetCookie();

                $guzzleCookie->setName($name);
                $guzzleCookie->setValue($value);

                $jar->setCookie($guzzleCookie);
            }

            $options['cookies'] = $jar;
        }
    }

    /**
     * Set the Guzzle form params.
     *
     * @param Request  $request The request
     * @param array   &$options The options
     *
     * @return void
     */
    protected function setGuzzleFormParams(Request $request, array &$options): void
    {
        if ($body = $request->getParsedBody()) {
            $options['form_params'] = $body;
        }
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
