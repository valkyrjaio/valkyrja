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

namespace Valkyrja\Http\Message\Uri\Contract;

use InvalidArgumentException;
use Stringable;
use Valkyrja\Http\Message\Uri\Enum\Scheme;

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
 * @see http://tools.ietf.org/html/rfc3986 (the URI specification)
 */
interface Uri extends Stringable
{
    /**
     * Create a Uri instance from a parsed uri string.
     *
     * @param string $uri The uri to parse
     *
     * @return static A new instance with the specified uri parsed to its parts
     */
    public static function fromString(string $uri): static;

    /**
     * Retrieve the scheme component of the URI.
     * If no scheme is present, this method MUST return an empty string.
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     * The trailing ":" character is not part of the scheme and MUST NOT be
     * added.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     *
     * @return Scheme The URI scheme
     */
    public function getScheme(): Scheme;

    /**
     * Determine whether the URI is secure.
     * If a scheme is present, and the value matches 'https',
     * this method MUST return true.
     * If the scheme is present, and the value does not match
     * 'https', this method MUST return false.
     * If no scheme is present, this method MUST return false.
     *
     * @return bool
     */
    public function isSecure(): bool;

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
     * @return string The URI authority, in "[user-info@]host[:port]" format
     */
    public function getAuthority(): string;

    /**
     * Retrieve the username component of the URI.
     * If no username is present, this method MUST return an empty
     * string.
     * If a username is present in the URI, this will return that value.
     * The trailing "@" and ":" characters is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI username, in "username" format
     */
    public function getUsername(): string;

    /**
     * Retrieve the user password component of the URI.
     * If no user password is present, this method MUST return an empty
     * string.
     * If a user password is present in the URI, this will return that value.
     * The preceding ":" character is not part of the user information and MUST
     * NOT be added.
     *  The trailing "@" character is not part of the user information and MUST
     *  NOT be added.
     *
     * @return string The URI user password, in "password" format
     */
    public function getPassword(): string;

    /**
     * Retrieve the user information component of the URI.
     * If no user information is present, this method MUST return an empty
     * string.
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     * The trailing "@" character is not part of the user information and MUST
     * NOT be added.
     *
     * @return string The URI user information, in "username[:password]" format
     */
    public function getUserInfo(): string;

    /**
     * Retrieve the host component of the URI.
     * If no host is present, this method MUST return an empty string.
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     *
     * @return string The URI host
     */
    public function getHost(): string;

    /**
     * Retrieve the port component of the URI.
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard
     * port used with the current scheme, this method SHOULD return null.
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return int|null The URI port
     */
    public function getPort(): int|null;

    /**
     * Retrieve the host and port components of the URI.
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it, along with the host component, as
     * a string in a fashion akin to the following <host:port>.
     * If the port is the standard port used with the current scheme, this
     * method SHOULD return only the host component.
     * If no port is present, and no scheme is present, this method MUST return
     * only the host component.
     * If no port is present, no host is present, and no scheme is present,
     * this method MUST return an empty string.
     * If no port is resent, no host is present, and a scheme is present,
     * this method MUST return an empty string.
     * if no port is present, a host is present, and a scheme is present,
     * this method MUST return only the host component.
     *
     * @return string
     */
    public function getHostPort(): string;

    /**
     * Retrieve the scheme, host, and port components of the URI.
     * If a scheme is present, a host is present, and a port is present,
     * and the port is non-standard for the current scheme, this method
     * MUST return the scheme, followed by the host, followed by the
     * port in a fashion akin to the following <scheme://host:port>.
     * If a scheme is present, a host is present, and a port is present,
     * and the port is standard for the current scheme, this method
     * MUST return only the scheme followed by the host, in a
     * fashion akin to the following <scheme://host>.
     * If a scheme is present, a host is present, and a port is not
     * present, this method MUST return only the scheme followed by
     * the host, in a fashion akin to the following <scheme://host>.
     * If a scheme is not present, a host is present, and a port is
     * present, this method MUST return only the host, followed by
     * the port, in a fashion akin to the following <host:port>.
     * If a scheme is not present, a host is present, and a port is
     * not present, this method MUST return only the host.
     * If a scheme is not present, a host is not present, and a port
     * is not present, this method MUST return an empty string.
     * If a scheme is present, a host is not present, and a port
     * is either present or not, this method MUST return an
     * empty string.
     *
     * @return string
     */
    public function getSchemeHostPort(): string;

    /**
     * Retrieve the path component of the URI.
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     * Normally, the empty path "" and absolute path "/" are considered equal as
     * defined in RFC 7230 Section 2.7.3. But this method MUST NOT automatically
     * do this normalization because in contexts with a trimmed base path, e.g.
     * the front controller, this difference becomes significant. It's the task
     * of the user to handle both "" and "/".
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.3.
     * As an example, if the value should include a slash ("/") not intended as
     * delimiter between path segments, that value MUST be passed in encoded
     * form (e.g., "%2F") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @return string The URI path
     */
    public function getPath(): string;

    /**
     * Retrieve the query string of the URI.
     * If no query string is present, this method MUST return an empty string.
     * The leading "?" character is not part of the query and MUST NOT be
     * added.
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     * As an example, if a value in a key/value pair of the query string should
     * include an ampersand ("&") not intended as a delimiter between values,
     * that value MUST be passed in encoded form (e.g., "%26") to the instance.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @return string The URI query string
     */
    public function getQuery(): string;

    /**
     * Retrieve the fragment component of the URI.
     * If no fragment is present, this method MUST return an empty string.
     * The leading "#" character is not part of the fragment and MUST NOT be
     * added.
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     *
     * @return string The URI fragment
     */
    public function getFragment(): string;

    /**
     * Return an instance with the specified scheme.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param Scheme $scheme The scheme to use with the new instance
     *
     * @throws InvalidArgumentException for invalid or unsupported schemes
     *
     * @return static A new instance with the specified scheme
     */
    public function withScheme(Scheme $scheme): static;

    /**
     * Return an instance with the specified username.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified username.
     * An empty string for the username is equivalent to removing user
     * information.
     *
     * @param string $username The user name to use for authority
     *
     * @return static A new instance with the specified user information
     */
    public function withUsername(string $username): static;

    /**
     * Return an instance with the specified user password.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user password.
     *
     * @param string $password The password associated with $user
     *
     * @return static A new instance with the specified user information
     */
    public function withPassword(string $password): static;

    /**
     * Return an instance with the specified user information.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string      $user     The user name to use for authority
     * @param string|null $password The password associated with $user
     *
     * @return static A new instance with the specified user information
     */
    public function withUserInfo(string $user, string|null $password = null): static;

    /**
     * Return an instance with the specified host.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance
     *
     * @throws InvalidArgumentException for invalid hostnames
     *
     * @return static A new instance with the specified host
     */
    public function withHost(string $host): static;

    /**
     * Return an instance with the specified port.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param int|null $port The port to use with the new instance; a null value
     *                       removes the port information
     *
     * @throws InvalidArgumentException for invalid ports
     *
     * @return static A new instance with the specified port
     */
    public function withPort(int|null $port = null): static;

    /**
     * Return an instance with the specified path.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     * If the path is intended to be domain-relative rather than path relative
     * then it must begin with a slash ("/"). Paths not starting with a slash
     * ("/") are assumed to be relative to some base path known to the
     * application or consumer.
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance
     *
     * @throws InvalidArgumentException for invalid paths
     *
     * @return static A new instance with the specified path
     */
    public function withPath(string $path): static;

    /**
     * Return an instance with the specified query string.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance
     *
     * @throws InvalidArgumentException for invalid query strings
     *
     * @return static A new instance with the specified query string
     */
    public function withQuery(string $query): static;

    /**
     * Return an instance with the specified URI fragment.
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance
     *
     * @return static A new instance with the specified fragment
     */
    public function withFragment(string $fragment): static;

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
    public function __toString(): string;
}
