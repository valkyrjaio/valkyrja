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

use Override;
use Throwable;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\InMemoryFilesystem;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Routing\Constant\AllowedClasses;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Support\Time\Time;
use Valkyrja\Throwable\Exception\RuntimeException;

use function base64_decode;
use function base64_encode;
use function md5;
use function serialize;
use function unserialize;

class CacheResponseMiddleware implements RequestReceivedMiddlewareContract, TerminatedMiddlewareContract
{
    /**
     * @param class-string[] $allowedClasses [optional] The allowed classes to unserialize
     */
    public function __construct(
        protected FilesystemContract $filesystem = new InMemoryFilesystem(),
        protected bool $debug = false,
        protected array $allowedClasses = AllowedClasses::CACHE_RESPONSE_MIDDLEWARE
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function requestReceived(ServerRequestContract $request, RequestReceivedHandlerContract $handler): ServerRequestContract|ResponseContract
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
                    $response instanceof ResponseContract
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
    #[Override]
    public function terminated(ServerRequestContract $request, ResponseContract $response, TerminatedHandlerContract $handler): void
    {
        $this->filesystem->write(
            $this->getCachePathForRequest($request),
            base64_encode(serialize($response))
        );

        $handler->terminated($request, $response);
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
        return md5($request->getUri()->getPath());
    }

    /**
     * Get the cache path for a request.
     */
    protected function getCachePathForRequest(ServerRequestContract $request): string
    {
        return Directory::cachePath('response/' . $this->getHashedPath($request));
    }
}
