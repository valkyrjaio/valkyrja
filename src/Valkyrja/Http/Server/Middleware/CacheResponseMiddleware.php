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

namespace Valkyrja\Http\Server\Middleware;

use Override;
use Throwable;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ResponseSentMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ResponseSentHandlerContract;
use Valkyrja\Support\Time\Time;

use function file_get_contents;
use function file_put_contents;
use function filemtime;
use function is_a;
use function is_array;
use function is_file;
use function is_string;
use function json_decode;
use function json_encode;
use function md5;
use function trim;
use function unlink;

use const JSON_THROW_ON_ERROR;

class CacheResponseMiddleware implements RequestReceivedMiddlewareContract, ResponseSentMiddlewareContract
{
    /**
     * @param non-empty-string $filePath The file path
     */
    public function __construct(
        protected string $filePath,
        protected bool $debug = false,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function requestReceived(ServerRequestContract $request, RequestReceivedHandlerContract $handler): ServerRequestContract|ResponseContract
    {
        $filePath = $this->getCachePathForRequest($request);

        if ($this->shouldLoadCachedResponse($filePath)) {
            if ($this->isCachedResponseFileExpired($filePath)) {
                @unlink($filePath);

                return $handler->requestReceived($request);
            }

            $response = $this->loadCachedResponse($filePath);

            // Ensure a valid response before returning it
            if ($response !== null && $this->isValidCachedResponse($response)) {
                return $response;
            }

            // Remove the bad cache
            @unlink($filePath);
        }

        return $handler->requestReceived($request);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function responseSent(ServerRequestContract $request, ResponseContract $response, ResponseSentHandlerContract $handler): void
    {
        if ($this->shouldNotCache($request, $response)) {
            return;
        }

        $filePath = $this->getCachePathForRequest($request);

        $this->cacheResponse($filePath, $response);

        $handler->responseSent($request, $response);
    }

    /**
     * Cache a response by serializing it to JSON.
     *
     * Storing the response as JSON (status code, headers, body — plus the uri for
     * redirects) keeps the cache language-agnostic and avoids executing a cached
     * PHP file on load.
     */
    protected function cacheResponse(string $filePath, ResponseContract $response): void
    {
        try {
            file_put_contents($filePath, $this->serializeResponse($response));
        } catch (Throwable) {
            // Ignore cache write failures and continue
        }
    }

    /**
     * Serialize a response to its JSON cache representation.
     */
    protected function serializeResponse(ResponseContract $response): string
    {
        $body = $response->getBody();
        $body->rewind();

        $headers = [];

        foreach ($response->getHeaders()->getAll() as $header) {
            $headers[] = [
                'name'  => $header->getName(),
                'value' => $header->getHeaderLine(),
            ];
        }

        $data = [
            'class'        => $response::class,
            'statusCode'   => $response->getStatusCode()->value,
            'reasonPhrase' => $response->getReasonPhrase(),
            'headers'      => $headers,
            'body'         => $body->getContents(),
        ];

        if ($response instanceof RedirectResponseContract) {
            $data['uri'] = $response->getUri()->__toString();
        }

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * Load and reconstruct a cached response from its JSON file.
     */
    protected function loadCachedResponse(string $filePath): ResponseContract|null
    {
        try {
            $json = file_get_contents($filePath);

            if ($json === false || $json === '') {
                return null;
            }

            return $this->deserializeResponse($json);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Reconstruct a response from its JSON cache representation.
     *
     * Mirrors how a response is built generically: instantiate with only the
     * headers (the one constructor argument shared by every response subclass)
     * and apply the status code, reason phrase and body via the immutable
     * `with*` methods.
     */
    protected function deserializeResponse(string $json): ResponseContract|null
    {
        /** @var mixed $data */
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        if (
            ! is_array($data)
            || ! isset($data['class'], $data['statusCode'], $data['reasonPhrase'], $data['headers'], $data['body'])
            || ! is_string($data['class'])
            || ! is_array($data['headers'])
            || ! is_a($data['class'], Response::class, true)
        ) {
            return null;
        }

        /** @var class-string<Response> $class */
        $class = $data['class'];

        $headers = [];

        /** @var mixed $header */
        foreach ($data['headers'] as $header) {
            if (
                is_array($header)
                && isset($header['name'], $header['value'])
                && is_string($header['name'])
                && $header['name'] !== ''
                && is_string($header['value'])
            ) {
                $headers[] = new Header($header['name'], $header['value']);
            }
        }

        $stream = new Stream();
        $stream->write((string) $data['body']);
        $stream->rewind();

        /** @psalm-suppress UnsafeInstantiation Every response subclass accepts a headers named argument */
        $response = new $class(headers: new HeaderCollection(...$headers))
            ->withStatusCode(StatusCode::from((int) $data['statusCode']))
            ->withReasonPhrase((string) $data['reasonPhrase'])
            ->withBody($stream);

        if (isset($data['uri']) && is_string($data['uri']) && $response instanceof RedirectResponseContract) {
            $response = $response->withUri(UriFactory::fromString($data['uri']));
        }

        return $response;
    }

    /**
     * Determine if a response should not be cached.
     */
    protected function shouldNotCache(ServerRequestContract $request, ResponseContract $response): bool
    {
        return $response->getStatusCode()->value >= StatusCode::INTERNAL_SERVER_ERROR->value
            || is_file($this->getCachePathForRequest($request));
    }

    /**
     * Determine if a cached response should be loaded.
     */
    protected function shouldLoadCachedResponse(string $filePath): bool
    {
        return ! $this->debug && is_file($filePath);
    }

    /**
     * Determine if a cached response file is expired.
     */
    protected function isCachedResponseFileExpired(string $filePath): bool
    {
        $timestamp = filemtime($filePath);

        return $timestamp !== false && Time::get() - $timestamp > $this->getTtl();
    }

    /**
     * Determine if a response is valid.
     *
     * @psalm-assert ResponseContract $response
     *
     * @phpstan-assert ResponseContract $response
     */
    protected function isValidCachedResponse(mixed $response): bool
    {
        return $response instanceof ResponseContract
            && $response->getStatusCode()->value < StatusCode::INTERNAL_SERVER_ERROR->value;
    }

    /**
     * Get the ttl.
     */
    protected function getTtl(): int
    {
        return 1800;
    }

    /**
     * Get a hashed version of the request path.
     */
    protected function getHashedPath(ServerRequestContract $request): string
    {
        return md5($request->getUri()->getPath() . $request->getMethod()->value);
    }

    /**
     * Get the cache path for a request.
     *
     * @return non-empty-string
     */
    protected function getCachePathForRequest(ServerRequestContract $request): string
    {
        return '/' . trim($this->filePath, '/') . '/' . $this->getHashedPath($request);
    }
}
