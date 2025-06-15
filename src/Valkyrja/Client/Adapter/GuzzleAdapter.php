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

namespace Valkyrja\Client\Adapter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Valkyrja\Client\Adapter\Contract\GuzzleAdapter as Contract;
use Valkyrja\Client\Config\GuzzleConfiguration;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\JsonServerRequest;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Class GuzzleAdapter.
 *
 * @author Melech Mizrachi
 */
class GuzzleAdapter implements Contract
{
    /**
     * GuzzleAdapter constructor.
     */
    public function __construct(
        protected ClientInterface $guzzle,
        protected ResponseFactory $responseFactory,
        protected GuzzleConfiguration $config
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function request(ServerRequest $request): Response
    {
        return $this->fromPsr7($this->getGuzzleResponse($request));
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function get(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function post(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function head(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function put(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function patch(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function delete(ServerRequest $request): Response
    {
        return $this->request($request->withMethod(RequestMethod::GET));
    }

    /**
     * Get a Guzzle response from a Valkyrja request.
     *
     * @param ServerRequest $request The request
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    protected function getGuzzleResponse(ServerRequest $request): ResponseInterface
    {
        $options = $this->config->options;

        /** @var array<string, mixed> $options */
        $this->setGuzzleHeaders($request, $options);
        $this->setGuzzleCookies($request, $options);
        $this->setGuzzleFormParams($request, $options);
        $this->setGuzzleBody($request, $options);

        return $this->guzzle->request($request->getMethod()->value, $request->getUri()->__toString(), $options);
    }

    /**
     * Set the Guzzle headers.
     *
     * @param ServerRequest        $request  The request
     * @param array<string, mixed> &$options The options
     *
     * @return void
     */
    protected function setGuzzleHeaders(ServerRequest $request, array &$options): void
    {
        if ($headers = $request->getHeaders()) {
            $options['headers'] = [];

            foreach ($headers as $header => $value) {
                $options['headers'][$header] = $value;
            }
        }
    }

    /**
     * Set the Guzzle cookies.
     *
     * @param ServerRequest        $request  The request
     * @param array<string, mixed> &$options The options
     *
     * @return void
     */
    protected function setGuzzleCookies(ServerRequest $request, array &$options): void
    {
        if ($cookies = $request->getCookieParams()) {
            $jar = new CookieJar();

            foreach ($cookies as $name => $value) {
                $guzzleCookie = new SetCookie();

                $guzzleCookie->setName($name);
                $guzzleCookie->setValue($value ?? '');

                $jar->setCookie($guzzleCookie);
            }

            $options['cookies'] = $jar;
        }
    }

    /**
     * Set the Guzzle form params.
     *
     * @param ServerRequest        $request  The request
     * @param array<string, mixed> &$options The options
     *
     * @return void
     */
    protected function setGuzzleFormParams(ServerRequest $request, array &$options): void
    {
        if (
            ($body = $request->getParsedBody())
            && ! ($request instanceof JsonServerRequest && $request->getParsedJson())
        ) {
            $options['form_params'] = $body;
        }
    }

    /**
     * Set the Guzzle body.
     *
     * @param ServerRequest        $request  The request
     * @param array<string, mixed> &$options The options
     *
     * @return void
     */
    protected function setGuzzleBody(ServerRequest $request, array &$options): void
    {
        $body = $request->getBody();
        $body->rewind();

        if ($contents = $body->getContents()) {
            $options['body'] = $contents;
        }
    }

    /**
     * Convert a Guzzle Response to Valkyrja Response.
     *
     * @param ResponseInterface $guzzleResponse The Guzzle Response
     *
     * @return Response
     *
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    protected function fromPsr7(ResponseInterface $guzzleResponse): Response
    {
        return $this->responseFactory->createResponse(
            $guzzleResponse->getBody()->getContents(),
            StatusCode::from($guzzleResponse->getStatusCode()),
            $guzzleResponse->getHeaders()
        );
    }
}
