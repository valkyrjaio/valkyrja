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
 * @see http://tools.ietf.org/html/rfc3986 (the URI specification)
 */
interface UriContract extends Stringable
{
    /**
     * Get the scheme.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     */
    public function getScheme(): Scheme;

    /**
     * Determine whether the uri is secure.
     */
    public function isSecure(): bool;

    /**
     * Get the uri authority in "[user-info@]host[:port]" format.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     */
    public function getAuthority(): string;

    /**
     * Get the username.
     */
    public function getUsername(): string;

    /**
     * Get the user password.
     */
    public function getPassword(): string;

    /**
     * Get the user info in "username[:password]" format.
     */
    public function getUserInfo(): string;

    /**
     * Get the host.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     */
    public function getHost(): string;

    /**
     * Get the port.
     */
    public function getPort(): int|null;

    /**
     * Get the host and port in "[host][:port]" format.
     */
    public function getHostPort(): string;

    /**
     * Get the scheme, host, and port in "[scheme://][host][:port]" format.
     */
    public function getSchemeHostPort(): string;

    /**
     * Get the path.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     */
    public function getPath(): string;

    /**
     * Get the query string.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     */
    public function getQuery(): string;

    /**
     * Get the fragment.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     */
    public function getFragment(): string;

    /**
     * Create a new instance with the specified scheme.
     *
     * @throws InvalidArgumentException for invalid or unsupported schemes
     */
    public function withScheme(Scheme $scheme): static;

    /**
     * Create a new instance with the specified username.
     */
    public function withUsername(string $username): static;

    /**
     * Create a new instance with the specified password.
     */
    public function withPassword(string $password): static;

    /**
     * Create a new instance with the specified user information.
     */
    public function withUserInfo(string $user, string|null $password = null): static;

    /**
     * Create a new instance with the specified host.
     *
     * @throws InvalidArgumentException for invalid hostnames
     */
    public function withHost(string $host): static;

    /**
     * Create a new instance with the specified port.
     *
     * @throws InvalidArgumentException for invalid ports
     */
    public function withPort(int|null $port = null): static;

    /**
     * Create a new instance with the specified path.
     *
     * @throws InvalidArgumentException for invalid paths
     */
    public function withPath(string $path): static;

    /**
     * Create a new instance with the specified query string.
     *
     * @throws InvalidArgumentException for invalid query strings
     */
    public function withQuery(string $query): static;

    /**
     * Create a new instance with the specified fragment.
     *
     * @param string $fragment The fragment to use with the new instance
     */
    public function withFragment(string $fragment): static;

    /**
     * Get the uri as a string.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     */
    public function __toString(): string;
}
