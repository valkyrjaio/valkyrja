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
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Server\Generator\ResponseFileGenerator;
use Valkyrja\Support\Time\Time;

use function md5;

class CacheResponseMiddleware implements RequestReceivedMiddlewareContract, TerminatedMiddlewareContract
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

            try {
                /** @psalm-suppress UnresolvableInclude */
                $response = require $filePath;

                $isValidResponse = $this->isValidCachedResponse($response);

                // Ensure a valid response before returning it
                if ($isValidResponse) {
                    return $response;
                }
            } catch (Throwable) {
                // Ignore errors and pass through to the next middleware
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
    public function terminated(ServerRequestContract $request, ResponseContract $response, TerminatedHandlerContract $handler): void
    {
        if ($this->shouldNotCache($request, $response)) {
            return;
        }

        $filePath = $this->getCachePathForRequest($request);

        new ResponseFileGenerator($response, $filePath)->generateFile();

        $handler->terminated($request, $response);
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
