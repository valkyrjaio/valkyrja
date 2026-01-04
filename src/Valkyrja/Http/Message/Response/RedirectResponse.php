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

namespace Valkyrja\Http\Message\Response;

use Override;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract as Contract;
use Valkyrja\Http\Message\Stream\Throwable\Exception\InvalidStreamException;
use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectException;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Uri;

class RedirectResponse extends Response implements Contract
{
    /**
     * The default uri to use.
     *
     * @var string
     */
    protected const string DEFAULT_URI = '/';

    /**
     * @inheritDoc
     *
     * @var StatusCode
     */
    protected const StatusCode DEFAULT_STATUS_CODE = StatusCode::FOUND;

    /**
     * @param UriContract             $uri        [optional] The uri
     * @param StatusCode              $statusCode [optional] The status
     * @param array<string, string[]> $headers    [optional] The headers
     *
     * @throws InvalidArgumentException
     * @throws InvalidStreamException
     */
    public function __construct(
        protected UriContract $uri = new Uri(path: self::DEFAULT_URI),
        StatusCode $statusCode = self::DEFAULT_STATUS_CODE,
        array $headers = self::DEFAULT_HEADERS
    ) {
        if (! $statusCode->isRedirect()) {
            throw new InvalidArgumentException(
                "Invalid redirect status code $statusCode->value used."
            );
        }

        parent::__construct(
            statusCode: $statusCode,
            headers: $this->injectHeader(HeaderName::LOCATION, (string) $uri, $headers, true)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function createFromUri(
        UriContract|null $uri = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): static {
        return new static(
            $uri ?? new Uri(path: static::DEFAULT_URI),
            $statusCode ?? static::DEFAULT_STATUS_CODE,
            $headers ?? static::DEFAULT_HEADERS
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getUri(): UriContract
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withUri(UriContract $uri): static
    {
        // Set the location header for the redirect
        $new = $this->withHeader(HeaderName::LOCATION, (string) $uri);
        // Set the uri
        $new->uri = $uri;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function secure(string $path, ServerRequestContract $request): static
    {
        $uri = new Uri(
            scheme: Scheme::HTTPS,
            host: $request->getUri()->getHostPort(),
            path: $path
        );

        return $this->withUri($uri);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function back(ServerRequestContract $request): static
    {
        $refererHeaderLine = $request->getHeaderLine('Referer') ?: '/';

        $refererUri = Uri::fromString($refererHeaderLine);
        $refererUri = $this->isInternalUri($request, $refererUri)
            ? $refererUri
            : new Uri(path: '/');

        return $this->withUri($refererUri);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throw(): void
    {
        throw new HttpRedirectException($this->uri, $this->statusCode, $this->getHeaders(), $this);
    }

    /**
     * Determine if a uri is internal.
     */
    protected function isInternalUri(ServerRequestContract $request, UriContract $uri): bool
    {
        // Get the host of the uri
        $host = $uri->getHost();

        // If the host matches the current request uri's host
        if (! $host || $host === $request->getUri()->getHost()) {
            return true;
        }

        return false;
    }
}
