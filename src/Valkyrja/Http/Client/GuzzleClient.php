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

namespace Valkyrja\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Valkyrja\Http\Client\Contract\Client as Contract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\JsonServerRequest;
use Valkyrja\Http\Message\Request\Contract\Request;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Class GuzzleClient.
 *
 * @author Melech Mizrachi
 */
class GuzzleClient implements Contract
{
    public function __construct(
        protected Client $client,
        protected ResponseFactory $responseFactory,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function sendRequest(Request $request): Response
    {
        return $this->fromPsr7($this->getGuzzleResponse($request));
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
        /** @var array<string, mixed> $options */
        $options = [];

        $this->setGuzzleHeaders($request, $options);
        $this->setGuzzleBody($request, $options);

        if ($request instanceof ServerRequest) {
            $this->setGuzzleCookies($request, $options);
            $this->setGuzzleFormParams($request, $options);
        }

        return $this->client->request(
            $request->getMethod()->value,
            $request->getUri()->__toString(),
            $options
        );
    }

    /**
     * Set the Guzzle headers.
     *
     * @param Request               $request The request
     * @param array<string, mixed> &$options The options
     *
     * @return void
     */
    protected function setGuzzleHeaders(Request $request, array &$options): void
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
     * @param ServerRequest         $request The request
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
     * @param ServerRequest         $request The request
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
     * @param Request               $request The request
     * @param array<string, mixed> &$options The options
     *
     * @return void
     */
    protected function setGuzzleBody(Request $request, array &$options): void
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
