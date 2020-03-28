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

use Valkyrja\Http\Enums\Port as PortEnum;
use Valkyrja\Http\Enums\Scheme as SchemeEnum;
use Valkyrja\Http\Exceptions\InvalidPath;
use Valkyrja\Http\Exceptions\InvalidPort;
use Valkyrja\Http\Exceptions\InvalidQuery;
use Valkyrja\Http\Exceptions\InvalidScheme;

use function ltrim;
use function preg_replace;
use function sprintf;
use function strpos;
use function strtolower;

/**
 * Trait UriHelpers.
 *
 * @author Melech Mizrachi
 *
 * @property string   $host
 * @property int|null $port
 * @property string   $scheme
 */
trait UriHelpers
{
    /**
     * Validate a port.
     *
     * @param int|null $port The port
     *
     * @throws InvalidPort
     *
     * @return void
     */
    protected function validatePort(int $port = null): void
    {
        if ($port !== null && ($port < 1 || $port > 65535)) {
            throw new InvalidPort(
                sprintf('Invalid port "%d" specified; must be a valid TCP/UDP port', $port)
            );
        }
    }

    /**
     * Validate a scheme.
     *
     * @param string $scheme The scheme
     *
     * @throws InvalidScheme
     *
     * @return string
     */
    protected function validateScheme(string $scheme): string
    {
        $scheme = strtolower($scheme);
        $scheme = (string) preg_replace('#:(//)?$#', '', $scheme);

        if (! $scheme) {
            return '';
        }

        if (SchemeEnum::HTTP !== $scheme && $scheme !== SchemeEnum::HTTPS) {
            throw new InvalidScheme(
                sprintf('Invalid scheme "%s" specified; must be either "http" or "https"', $scheme)
            );
        }

        return $scheme;
    }

    /**
     * Validate a path.
     *
     * @param string $path The path
     *
     * @throws InvalidPath
     *
     * @return string
     */
    protected function validatePath(string $path): string
    {
        if (strpos($path, '?') !== false) {
            throw new InvalidPath('Invalid path provided; must not contain a query string');
        }

        if (strpos($path, '#') !== false) {
            throw new InvalidPath('Invalid path provided; must not contain a URI fragment');
        }

        // TODO: Filter path

        return '/' . ltrim($path, '/');
    }

    /**
     * Validate a query.
     *
     * @param string $query The query
     *
     * @throws InvalidQuery
     *
     * @return string
     */
    protected function validateQuery(string $query): string
    {
        if (strpos($query, '#') !== false) {
            throw new InvalidQuery('Query string must not include a URI fragment');
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

    /**
     * Determine whether this uri is on a standard port for the scheme.
     *
     * @return bool
     */
    protected function isStandardPort(): bool
    {
        if (! $this->scheme) {
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
        return SchemeEnum::HTTP === $this->scheme && $this->port === PortEnum::HTTP;
    }

    /**
     * Is standard HTTPS port.
     *
     * @return bool
     */
    protected function isStandardSecurePort(): bool
    {
        return SchemeEnum::HTTPS === $this->scheme && $this->port === PortEnum::HTTPS;
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
        if ($scheme = $this->scheme) {
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
        if ($path = $this->path) {
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
    abstract public function getAuthority(): string;
}
