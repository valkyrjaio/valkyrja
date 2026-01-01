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

namespace Valkyrja\Http\Client\Manager;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use Override;
use Psr\Http\Message\ResponseInterface;
use Valkyrja\Http\Client\Manager\Contract\ClientContract as Contract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Request\Contract\JsonServerRequestContract;
use Valkyrja\Http\Message\Request\Contract\RequestContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

class GuzzleClient implements Contract
{
    public function __construct(
        protected Client $client,
        protected ResponseFactoryContract $responseFactory,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    #[Override]
    public function sendRequest(RequestContract $request): ResponseContract
    {
        return $this->fromPsr7($this->getGuzzleResponse($request));
    }

    /**
     * Get a Guzzle response from a Valkyrja request.
     *
     * @param RequestContract $request The request
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    protected function getGuzzleResponse(RequestContract $request): ResponseInterface
    {
        /** @var array<string, mixed> $options */
        $options = [];

        $this->setGuzzleHeaders($request, $options);
        $this->setGuzzleBody($request, $options);

        if ($request instanceof ServerRequestContract) {
            $this->setGuzzleCookies($request, $options);
            $this->setGuzzleFormParams($request, $options);
        }

        return $this->client->request(
            method: $request->getMethod()->value,
            uri: $request->getUri()->__toString(),
            options: $options
        );
    }

    /**
     * Set the Guzzle headers.
     *
     * @param RequestContract      $request  The request
     * @param array<string, mixed> &$options The options
     *
     * @return void
     */
    protected function setGuzzleHeaders(RequestContract $request, array &$options): void
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
     * @param ServerRequestContract $request  The request
     * @param array<string, mixed>  &$options The options
     *
     * @return void
     */
    protected function setGuzzleCookies(ServerRequestContract $request, array &$options): void
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
     * @param ServerRequestContract $request  The request
     * @param array<string, mixed>  &$options The options
     *
     * @return void
     */
    protected function setGuzzleFormParams(ServerRequestContract $request, array &$options): void
    {
        if (
            ($body = $request->getParsedBody())
            && ! ($request instanceof JsonServerRequestContract && $request->getParsedJson())
        ) {
            $options['form_params'] = $body;
        }
    }

    /**
     * Set the Guzzle body.
     *
     * @param RequestContract      $request  The request
     * @param array<string, mixed> &$options The options
     *
     * @return void
     */
    protected function setGuzzleBody(RequestContract $request, array &$options): void
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
     * @return ResponseContract
     *
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    protected function fromPsr7(ResponseInterface $guzzleResponse): ResponseContract
    {
        return $this->responseFactory->createResponse(
            content: $guzzleResponse->getBody()->getContents(),
            statusCode: StatusCode::from($guzzleResponse->getStatusCode()),
            headers: $guzzleResponse->getHeaders()
        );
    }
}
