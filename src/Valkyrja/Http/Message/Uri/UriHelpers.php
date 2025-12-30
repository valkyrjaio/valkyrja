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

namespace Valkyrja\Http\Message\Uri;

use Valkyrja\Http\Message\Constant\Port;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException;

use function ltrim;
use function preg_replace;
use function str_starts_with;
use function strtolower;

/**
 * Trait UriHelpers.
 *
 * @author Melech Mizrachi
 *
 * @property string   $host
 * @property int|null $port
 * @property Scheme   $scheme
 */
trait UriHelpers
{
    /**
     * Filter a scheme.
     *
     * @param string $scheme The scheme
     *
     * @return Scheme
     */
    protected static function filterScheme(string $scheme): Scheme
    {
        $scheme = strtolower($scheme);
        $scheme = (string) preg_replace('#:(//)?$#', '', $scheme);

        return Scheme::from($scheme);
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
     * @return string The URI authority, in "[user-info@]host[:port]" format
     */
    abstract public function getAuthority(): string;

    /**
     * Validate a port.
     *
     * @param int|null $port The port
     *
     * @throws InvalidPortException
     *
     * @return void
     */
    protected function validatePort(int|null $port = null): void
    {
        if (! Port::isValid($port)) {
            throw new InvalidPortException("Invalid port `%$port` specified; must be a valid TCP/UDP port");
        }
    }

    /**
     * Filter user info.
     */
    protected function filterUserInfo(string $userInfo): string
    {
        // TODO: Filter user info

        return $userInfo;
    }

    /**
     * Filter a path.
     *
     * @param string $path The path
     *
     * @throws InvalidPathException
     *
     * @return string
     */
    protected function filterPath(string $path): string
    {
        $this->validatePath($path);

        // TODO: Filter path

        if (str_starts_with($path, '/')) {
            return '/' . ltrim($path, '/');
        }

        return $path;
    }

    /**
     * Validate a path.
     *
     * @param string $path The path
     *
     * @throws InvalidPathException
     *
     * @return void
     */
    protected function validatePath(string $path): void
    {
        if (str_contains($path, '?')) {
            throw new InvalidPathException("Invalid path of `$path` provided; must not contain a query string");
        }

        if (str_contains($path, '#')) {
            throw new InvalidPathException("Invalid path of `$path` provided; must not contain a URI fragment");
        }
    }

    /**
     * Filter a query.
     *
     * @param string $query The query
     *
     * @throws InvalidQueryException
     *
     * @return string
     */
    protected function filterQuery(string $query): string
    {
        $this->validateQuery($query);

        // TODO: Filter query

        return ltrim($query, '?');
    }

    /**
     * Validate a query.
     *
     * @param string $query The query
     *
     * @throws InvalidQueryException
     *
     * @return void
     */
    protected function validateQuery(string $query): void
    {
        if (str_contains($query, '#')) {
            throw new InvalidQueryException(
                "Invalid query string of `$query` provided; must not contain a URI fragment"
            );
        }
    }

    /**
     * Filter a fragment.
     *
     * @param string $fragment The fragment
     *
     * @return string
     */
    protected function filterFragment(string $fragment): string
    {
        $this->validateFragment($fragment);

        // TODO: Filter fragment

        return ltrim($fragment, '#');
    }

    /**
     * Validate a fragment.
     *
     * @param string $fragment The fragment
     *
     * @return void
     */
    protected function validateFragment(string $fragment): void
    {
    }

    /**
     * Determine whether this uri is on a standard port for the scheme.
     *
     * @return bool
     */
    protected function isStandardPort(): bool
    {
        if ($this->scheme === Scheme::EMPTY) {
            return $this->host && $this->port === null;
        }

        if (! $this->host || $this->port === null) {
            return true;
        }

        return $this->isStandardUnsecurePort() || $this->isStandardSecurePort();
    }

    /**
     * Is standard HTTP port.
     *
     * @return bool
     */
    protected function isStandardUnsecurePort(): bool
    {
        return $this->scheme === Scheme::HTTP && $this->port === Port::HTTP;
    }

    /**
     * Is standard HTTPS port.
     *
     * @return bool
     */
    protected function isStandardSecurePort(): bool
    {
        return $this->scheme === Scheme::HTTPS && $this->port === Port::HTTPS;
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
        if (($scheme = $this->scheme) !== Scheme::EMPTY) {
            $uri .= $scheme->value . ':';
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
        if ($path = $this->path) {
            if ($path[0] !== '/') {
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
        if ($query = $this->query) {
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
        if ($fragment = $this->fragment) {
            $uri .= '#' . $fragment;
        }

        return $uri;
    }
}
