<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Uris;

use Valkyrja\HttpMessage\Exceptions\InvalidPath;
use Valkyrja\HttpMessage\Exceptions\InvalidPort;
use Valkyrja\HttpMessage\Exceptions\InvalidQuery;
use Valkyrja\HttpMessage\Exceptions\InvalidScheme;
use Valkyrja\HttpMessage\Uri as UriContract;

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
    use Fragment;
    use Host;
    use Path;
    use Port;
    use Query;
    use Scheme;
    use UserInfo;

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
     * @param string $scheme   [optional] The scheme
     * @param string $userInfo [optional] The user info
     * @param string $host     [optional] The host
     * @param int    $port     [optional] The port
     * @param string $path     [optional] The path
     * @param string $query    [optional] The query
     * @param string $fragment [optional] The fragment
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
     * Retrieve the authority component of the URI.
     * If no authority information is present, this method MUST return an empty
     * string.
     * The authority syntax of the URI is:
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     *
     * @return string The URI authority, in "[user-info@]host[:port]" format.
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
     * Return the string representation as a URI reference.
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters:
     * - If a scheme is present, it MUST be suffixed by ":".
     * - If an authority is present, it MUST be prefixed by "//".
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     * - If a query is present, it MUST be prefixed by "?".
     * - If a fragment is present, it MUST be prefixed by "#".
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     *
     * @return string
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

    /**
     * Is valid uri string.
     *
     * @return bool
     */
    protected function isValidUriString(): bool
    {
        return null !== $this->uriString;
    }

    /**
     * Add scheme to uri.
     *
     * @param string $uri The uri
     *
     * @return string
     */
    protected function addSchemeToUri(string $uri): string
    {
        if ($scheme = $this->getScheme()) {
            $uri .= $scheme . ':';
        }

        return $uri;
    }

    /**
     * Add authority to uri.
     *
     * @param string $uri The uri
     *
     * @return string
     */
    protected function addAuthorityToUri(string $uri): string
    {
        if ($authority = $this->getAuthority()) {
            $uri .= '//' . $authority;
        }

        return $uri;
    }

    /**
     * Add path to uri.
     *
     * @param string $uri The uri
     *
     * @return string
     */
    protected function addPathToUri(string $uri): string
    {
        if ($path = $this->getPath()) {
            if ('/' !== $path[0]) {
                $path = '/' . $path;
            }

            $uri .= $path;
        }

        return $uri;
    }

    /**
     * Add query to uri.
     *
     * @param string $uri The uri
     *
     * @return string
     */
    protected function addQueryToUri(string $uri): string
    {
        if ($query = $this->getQuery()) {
            $uri .= '?' . $query;
        }

        return $uri;
    }

    /**
     * Add fragment to uri.
     *
     * @param string $uri The uri
     *
     * @return string
     */
    protected function addFragmentToUri(string $uri): string
    {
        if ($fragment = $this->getFragment()) {
            $uri .= '#' . $fragment;
        }

        return $uri;
    }
}
