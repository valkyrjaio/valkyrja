<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage;

use Valkyrja\HttpMessage\Exceptions\InvalidPath;
use Valkyrja\HttpMessage\Exceptions\InvalidPort;
use Valkyrja\HttpMessage\Exceptions\InvalidQuery;
use Valkyrja\HttpMessage\Exceptions\InvalidScheme;

/**
 * Value object representing a URI.
 *
 * This interface is meant to represent URIs according to RFC 3986 and to
 * provide methods for most common operations. Additional functionality for
 * working with URIs can be provided on top of the interface or externally.
 * Its primary use is for HTTP requests, but may also be used in other
 * contexts.
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 *
 * Typically the Host header will be also be present in the request message.
 * For server-side requests, the scheme will typically be discoverable in the
 * server parameters.
 *
 * @link   http://tools.ietf.org/html/rfc3986 (the URI specification)
 *
 * @author Melech Mizrachi
 */
class NativeUri implements Uri
{
    protected const HTTP_PORT    = 80;
    protected const HTTPS_PORT   = 443;
    protected const HTTP_SCHEME  = 'http';
    protected const HTTPS_SCHEME = 'https';

    /**
     * The scheme.
     *
     * @var string
     */
    protected $scheme;

    /**
     * The user info.
     *
     * @var string
     */
    protected $userInfo;

    /**
     * The host.
     *
     * @var string
     */
    protected $host;

    /**
     * The port.
     *
     * @var int
     */
    protected $port;

    /**
     * The path.
     *
     * @var string
     */
    protected $path;

    /**
     * The query string.
     *
     * @var string
     */
    protected $query;

    /**
     * The fragment.
     *
     * @var string
     */
    protected $fragment;

    /**
     * The URI as a string.
     *
     * @var string
     */
    protected $uriString;

    /**
     * UriImpl constructor.
     *
     * @param string $scheme   [optional] The scheme
     * @param string $userInfo [optional] The user info
     * @param string $host     [optional] The host
     * @param string $port     [optional] The port
     * @param string $path     [optional] The path
     * @param string $query    [optional] The query
     * @param string $fragment [optional] The fragment
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPath
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPort
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidQuery
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidScheme
     */
    public function __construct(
        string $scheme = null,
        string $userInfo = null,
        string $host = null,
        string $port = null,
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
     * Retrieve the scheme component of the URI.
     *
     * If no scheme is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     *
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     *
     * @return string The URI scheme.
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Determine whether the URI is secure.
     *
     * If a scheme is present, and the value matches 'https',
     * this method MUST return true.
     *
     * If the scheme is present, and the value does not match
     * 'https', this method MUST return false.
     *
     * If no scheme is present, this method MUST return false.
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->getScheme() === 'https';
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * If no authority information is present, this method MUST return an empty
     * string.
     *
     * The authority syntax of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
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
     * Retrieve the user information component of the URI.
     *
     * If no user information is present, this method MUST return an empty
     * string.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * Retrieve the host component of the URI.
     *
     * If no host is present, this method MUST return an empty string.
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     *
     * @return string The URI host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard
     * port used with the current scheme, this method SHOULD return null.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     *
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return null|int The URI port.
     */
    public function getPort():? int
    {
        return $this->isStandardPort() ? null : $this->port;
    }

    /**
     * Retrieve the host and port component of the URI.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it, along with the host component, as
     * a string in a fashion akin to the following <host:port>.
     *
     * If the port is the standard port used with the current scheme, this
     * method SHOULD return only the host component.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * only the host component.
     *
     * If no port is present, no host is present, and no scheme is present,
     * this method MUST return an empty string.
     *
     * If no port is resent, no host is present, and a scheme is present,
     * this method MUST return an empty string.
     *
     * if no port is present, a host is present, and a scheme is present,
     * this method MUST return only the host component.
     *
     * @return string
     */
    public function getHostPort(): string
    {
        $host = $this->getHost();

        if ($host && $port = $this->getPort()) {
            $host .= ':' . $port;
        }

        return $host;
    }

    /**
     * Retrieve the scheme, host, and port components of the URI.
     *
     * If a scheme is present, a host is present, and a port is present,
     * and the port is non-standard for the current scheme, this method
     * MUST return the scheme, followed by the host, followed by the
     * port in a fashion akin to the following <scheme://host:port>.
     *
     * If a scheme is present, a host is present, and a port is present,
     * and the port is standard for the current scheme, this method
     * MUST return only the scheme followed by the host, in a
     * fashion akin to the following <scheme://host>.
     *
     * If a scheme is present, a host is present, and a port is not
     * present, this method MUST return only the scheme followed by
     * the host, in a fashion akin to the following <scheme://host>.
     *
     * If a scheme is not present, a host is present, and a port is
     * present, this method MUST return only the host, followed by
     * the port, in a fashion akin to the following <host:port>.
     *
     * If a scheme is not present, a host is present, and a port is
     * not present, this method MUST return only the host.
     *
     * If a scheme is not present, a host is not present, and a port
     * is not present, this method MUST return an empty string.
     *
     * If a scheme is present, a host is not present, and a port
     * is either present or not, this method MUST return an
     * empty string.
     *
     * @return string
     */
    public function getSchemeHostPort(): string
    {
        $hostPort = $this->getHostPort();
        $scheme   = $this->getScheme();

        return $hostPort && $scheme ? $scheme . '://' . $hostPort : $hostPort;
    }

    /**
     * Retrieve the path component of the URI.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * Normally, the empty path "" and absolute path "/" are considered equal as
     * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
     * do this normalization because in contexts with a trimmed base path, e.g.
     * the front controller, this difference becomes significant. It's the task
     * of the user to handle both "" and "/".
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     *
     * As an example, if the value should include a slash ("/") not intended as
     * delimiter between path segments, that value MUST be passed in encoded
     * form (e.g., "%2F") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @return string The URI path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * If no query string is present, this method MUST return an empty string.
     *
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     *
     * As an example, if a value in a key/value pair of the query string should
     * include an ampersand ("&") not intended as a delimiter between values,
     * that value MUST be passed in encoded form (e.g., "%26") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @return string The URI query string.
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Retrieve the fragment component of the URI.
     *
     * If no fragment is present, this method MUST return an empty string.
     *
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     *
     * @return string The URI fragment.
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Return an instance with the specified scheme.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     *
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidScheme for invalid or
     *          unsupported schemes.
     *
     * @return static A new instance with the specified scheme.
     */
    public function withScheme(string $scheme): Uri
    {
        if ($scheme === $this->scheme) {
            return clone $this;
        }

        $scheme = $this->validateScheme($scheme);

        $new = clone $this;

        $new->scheme = $scheme;

        return $new;
    }

    /**
     * Return an instance with the specified user information.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     *
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string      $user     The user name to use for authority.
     * @param null|string $password The password associated with $user.
     *
     * @return static A new instance with the specified user information.
     */
    public function withUserInfo(string $user, string $password = null): Uri
    {
        $info = $user;

        if ($password) {
            $info .= ':' . $password;
        }

        if ($info === $this->userInfo) {
            return clone $this;
        }

        $new = clone $this;

        $new->userInfo = $info;

        return $new;
    }

    /**
     * Return an instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     *
     * @throws \InvalidArgumentException for invalid hostnames.
     *
     * @return static A new instance with the specified host.
     */
    public function withHost(string $host): Uri
    {
        if ($host === $this->host) {
            return clone $this;
        }

        $new = clone $this;

        $new->host = $host;

        return $new;
    }

    /**
     * Return an instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     *
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *                       removes the port information.
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPort for invalid ports.
     *
     * @return static A new instance with the specified port.
     */
    public function withPort(int $port = null): Uri
    {
        if ($port === $this->port) {
            return clone $this;
        }

        $this->validatePort($port);

        $new = clone $this;

        $new->port = $port;

        return $new;
    }

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If the path is intended to be domain-relative rather than path relative
     * then it must begin with a slash ("/"). Paths not starting with a slash
     * ("/") are assumed to be relative to some base path known to the
     * application or consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPath for invalid paths.
     *
     * @return static A new instance with the specified path.
     */
    public function withPath(string $path): Uri
    {
        if ($path === $this->path) {
            return clone $this;
        }

        $path = $this->validatePath($path);

        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * Return an instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     *
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidQuery for invalid query
     *          strings.
     *
     * @return static A new instance with the specified query string.
     */
    public function withQuery(string $query): Uri
    {
        if ($query === $this->query) {
            return clone $this;
        }

        $query = $this->validateQuery($query);

        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     *
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     *
     * @return static A new instance with the specified fragment.
     */
    public function withFragment(string $fragment): Uri
    {
        if ($fragment === $this->fragment) {
            return clone $this;
        }

        $fragment = $this->validateFragment($fragment);

        $new = clone $this;

        $new->fragment = $fragment;

        return $new;
    }

    /**
     * Return the string representation as a URI reference.
     *
     * Depending on which components of the URI are present, the resulting
     * string is either a full URI or relative reference according to RFC 3986,
     * Section 4.1. The method concatenates the various components of the URI,
     * using the appropriate delimiters:
     *
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
        if (null !== $this->uriString) {
            return $this->uriString;
        }

        $uri = '';

        if ($scheme = $this->getScheme()) {
            $uri .= $scheme . ':';
        }

        if ($authority = $this->getAuthority()) {
            $uri .= '//' . $authority;
        }

        if ($path = $this->getPath()) {
            if ('/' !== $path[0]) {
                $path = '/' . $path;
            }

            $uri .= $path;
        }

        if ($query = $this->getQuery()) {
            $uri .= '?' . $query;
        }

        if ($fragment = $this->getFragment()) {
            $uri .= '#' . $fragment;
        }

        return $this->uriString = $uri;
    }

    /**
     * Determine whether this uri is on a standard port for the scheme.
     *
     * @return bool
     */
    protected function isStandardPort(): bool
    {
        if (! $this->scheme) {
            return $this->host && ! $this->port;
        }

        if (! $this->host || ! $this->port) {
            return true;
        }

        return (static::HTTP_SCHEME === $this->scheme && $this->port === static::HTTP_PORT)
            || (static::HTTPS_SCHEME === $this->scheme && $this->port === static::HTTPS_PORT);
    }

    /**
     * Validate a scheme.
     *
     * @param string $scheme The scheme
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidScheme
     *
     * @return string
     */
    protected function validateScheme(string $scheme): string
    {
        $scheme = strtolower($scheme);
        $scheme = preg_replace('#:(//)?$#', '', $scheme);

        if (! $scheme) {
            return '';
        }

        if (static::HTTP_SCHEME !== $scheme || $scheme !== static::HTTPS_SCHEME) {
            throw new InvalidScheme(
                sprintf(
                    'Invalid scheme "%s" specified; must be either "http" or "https"',
                    $scheme
                )
            );
        }

        return $scheme;
    }

    /**
     * Validate a port.
     *
     * @param int $port The port
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPort
     *
     * @return void
     */
    protected function validatePort(int $port = null): void
    {
        if ($port !== null && ($port < 1 || $port > 65535)) {
            throw new InvalidPort(
                sprintf(
                    'Invalid port "%d" specified; must be a valid TCP/UDP port',
                    $port
                )
            );
        }
    }

    /**
     * Validate a path.
     *
     * @param string $path The path
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidPath
     *
     * @return string
     */
    protected function validatePath(string $path): string
    {
        if (strpos($path, '?') !== false) {
            throw new InvalidPath(
                'Invalid path provided; must not contain a query string'
            );
        }

        if (strpos($path, '#') !== false) {
            throw new InvalidPath(
                'Invalid path provided; must not contain a URI fragment'
            );
        }

        // TODO: Filter path

        return '/' . ltrim($path, '/');
    }

    /**
     * Validate a query.
     *
     * @param string $query The query
     *
     * @throws \Valkyrja\HttpMessage\Exceptions\InvalidQuery
     *
     * @return string
     */
    protected function validateQuery(string $query): string
    {
        if (strpos($query, '#') !== false) {
            throw new InvalidQuery(
                'Query string must not include a URI fragment'
            );
        }

        // TODO: Filter query

        return $query;
    }

    /**
     * Validate a fragment.
     *
     * @param string $fragment The fragment
     *
     * @return string
     */
    protected function validateFragment(string $fragment): string
    {
        // TODO: Filter fragment

        return $fragment;
    }
}
