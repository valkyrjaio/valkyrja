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

namespace Valkyrja\Http\Uris;

use Valkyrja\Http\Exceptions\InvalidPath;
use Valkyrja\Http\Exceptions\InvalidPort;
use Valkyrja\Http\Exceptions\InvalidQuery;
use Valkyrja\Http\Exceptions\InvalidScheme;
use Valkyrja\Http\Uri as UriContract;

/**
 * Value object representing a URI.
 * This interface is meant to represent URIs according to RFC 3986 and to
 * provide methods for most common operations. Additional functionality for
 * working with URIs can be provided on top of the interface or externally.
 * Its primary use is for HTTP requests, but may also be used in other
 * contexts.
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 * Typically the Host header will be also be present in the request message.
 * For server-side requests, the scheme will typically be discoverable in the
 * server parameters.
 *
 * @link   http://tools.ietf.org/html/rfc3986 (the URI specification)
 *
 * @author Melech Mizrachi
 */
class Uri implements UriContract
{
    use UriHelpers;

    /**
     * The scheme.
     *
     * @var string
     */
    protected string $scheme;

    /**
     * The user info.
     *
     * @var string
     */
    protected string $userInfo;

    /**
     * The host.
     *
     * @var string
     */
    protected string $host;

    /**
     * The port.
     *
     * @var int|null
     */
    protected ?int $port = null;

    /**
     * The path.
     *
     * @var string
     */
    protected string $path;

    /**
     * The query string.
     *
     * @var string
     */
    protected string $query;

    /**
     * The fragment.
     *
     * @var string
     */
    protected string $fragment;

    /**
     * The URI as a string.
     *
     * @var string|null
     */
    private ?string $uriString = null;

    /**
     * UriImpl constructor.
     *
     * @param string|null $scheme   [optional] The scheme
     * @param string|null $userInfo [optional] The user info
     * @param string|null $host     [optional] The host
     * @param int|null    $port     [optional] The port
     * @param string|null $path     [optional] The path
     * @param string|null $query    [optional] The query
     * @param string|null $fragment [optional] The fragment
     *
     * @throws InvalidPath
     * @throws InvalidPort
     * @throws InvalidQuery
     * @throws InvalidScheme
     */
    public function __construct(
        string $scheme = null,
        string $userInfo = null,
        string $host = null,
        int $port = null,
        string $path = null,
        string $query = null,
        string $fragment = null
    ) {
        $this->scheme   = $this->validateScheme($scheme ?? '');
        $this->userInfo = $userInfo ?? '';
        $this->host     = $host ?? '';
        $this->port     = $port;
        $this->path     = $this->validatePath($path ?? '');
        $this->query    = $this->validateQuery($query ?? '');
        $this->fragment = $this->validateFragment($fragment ?? '');

        $this->validatePort($this->port);
    }

    /**
     * @inheritDoc
     */
    public function isSecure(): bool
    {
        return $this->scheme === 'https';
    }

    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        if (empty($this->host)) {
            return '';
        }

        $authority = $this->host;

        if (! empty($this->userInfo)) {
            $authority = $this->userInfo . '@' . $authority;
        }

        if (! $this->isStandardPort()) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): ?int
    {
        return $this->isStandardPort() ? null : $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getHostPort(): string
    {
        $host = $this->host;

        if ($host && $port = $this->port) {
            $host .= ':' . $port;
        }

        return $host;
    }

    /**
     * @inheritDoc
     */
    public function getSchemeHostPort(): string
    {
        $hostPort = $this->getHostPort();
        $scheme   = $this->scheme;

        return $hostPort && $scheme ? $scheme . '://' . $hostPort : $hostPort;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function withScheme(string $scheme): self
    {
        $scheme = $this->validateScheme($scheme);

        $new = clone $this;

        $new->scheme = $scheme;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo(string $user, string $password = null): self
    {
        $info = $user;

        if ($password) {
            $info .= ':' . $password;
        }

        $new = clone $this;

        $new->userInfo = $info;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withHost(string $host): self
    {
        $new = clone $this;

        $new->host = $host;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withPort(int $port = null): self
    {
        $this->validatePort($port);

        $new = clone $this;

        $new->port = $port;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): self
    {
        $path = $this->validatePath($path);

        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withQuery(string $query): self
    {
        $query = $this->validateQuery($query);

        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withFragment(string $fragment): self
    {
        $fragment = $this->validateFragment($fragment);

        $new = clone $this;

        $new->fragment = $fragment;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        if ($this->isValidUriString()) {
            return (string) $this->uriString;
        }

        $uri = '';

        $uri = $this->addSchemeToUri($uri);
        $uri = $this->addAuthorityToUri($uri);
        $uri = $this->addPathToUri($uri);
        $uri = $this->addQueryToUri($uri);
        $uri = $this->addFragmentToUri($uri);

        return $this->uriString = $uri;
    }
}
