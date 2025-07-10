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

namespace Valkyrja\Http\Middleware\Cache;

use Throwable;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Filesystem\InMemoryFilesystem;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Support\Directory;
use Valkyrja\Support\Time;

use function base64_decode;
use function base64_encode;
use function md5;
use function serialize;
use function unserialize;

/**
 * Class CacheResponseMiddleware.
 *
 * @author Melech Mizrachi
 */
class CacheResponseMiddleware implements RequestReceivedMiddleware, TerminatedMiddleware
{
    public function __construct(
        protected Filesystem $filesystem = new InMemoryFilesystem(),
        protected bool $debug = false
    ) {
    }

    /**
     * @inheritDoc
     */
    public function requestReceived(ServerRequest $request, RequestReceivedHandler $handler): ServerRequest|Response
    {
        $filesystem = $this->filesystem;

        $filePath = $this->getCachePathForRequest($request);

        if (! $this->debug && $filesystem->exists($filePath)) {
            $timestamp = $filesystem->timestamp($filePath) ?? 0;

            if (Time::get() - $timestamp > $this->getTtl()) {
                $filesystem->delete($filePath);

                return $handler->requestReceived($request);
            }

            try {
                $cache        = $filesystem->read($filePath);
                $decodedCache = base64_decode($cache, true);

                if ($decodedCache === false) {
                    throw new RuntimeException('Failed to decode cache');
                }

                /** @var object $response */
                $response = unserialize(
                    $decodedCache,
                    [
                        'allowed_classes' => true,
                    ]
                );

                // Ensure a valid response before returning it
                if (
                    $response instanceof Response
                    && $response->getStatusCode()->value < StatusCode::INTERNAL_SERVER_ERROR->value
                ) {
                    return $response;
                }
            } catch (Throwable) {
            }

            // Remove the bad cache
            $filesystem->delete($filePath);
        }

        return $handler->requestReceived($request);
    }

    /**
     * @inheritDoc
     */
    public function terminated(ServerRequest $request, Response $response, TerminatedHandler $handler): void
    {
        $this->filesystem->write(
            $this->getCachePathForRequest($request),
            base64_encode(serialize($response))
        );

        $handler->terminated($request, $response);
    }

    /**
     * Get the ttl.
     *
     * @return int
     */
    protected function getTtl(): int
    {
        return 1800;
    }

    /**
     * Get a hashed version of the request path.
     *
     * @param ServerRequest $request
     *
     * @return string
     */
    protected function getHashedPath(ServerRequest $request): string
    {
        return md5($request->getUri()->getPath());
    }

    /**
     * Get the cache path for a request.
     */
    protected function getCachePathForRequest(ServerRequest $request): string
    {
        return Directory::cachePath('response/' . $this->getHashedPath($request));
    }
}
