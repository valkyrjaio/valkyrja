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

namespace Valkyrja\Http\Message\Uri\Factory;

use Psr\Http\Message\UriInterface;
use Valkyrja\Http\Message\Constant\Port;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException;
use Valkyrja\Http\Message\Uri\Uri;

use function explode;
use function ltrim;
use function preg_replace;
use function strtolower;

abstract class UriFactory
{
    /**
     * Get a Uri object from a PSR UriInterface object.
     *
     * @param UriInterface $psrUri The PSR uri
     */
    public static function fromPsr(UriInterface $psrUri): UriContract
    {
        $userInfo = $psrUri->getUserInfo();
        $password = null;

        if ($userInfo !== '' && str_contains($userInfo, ':')) {
            [$user, $password] = explode(':', $userInfo);
        } else {
            $user = $userInfo;
        }

        $uri = new Uri();

        return $uri
            ->withScheme(Scheme::from($psrUri->getScheme()))
            ->withUserInfo($user, $password)
            ->withHost($psrUri->getHost())
            ->withPort($psrUri->getPort())
            ->withPath($psrUri->getPath())
            ->withQuery($psrUri->getQuery())
            ->withFragment($psrUri->getFragment());
    }

    /**
     * Create a Uri instance from a parsed uri string.
     *
     * @param string $uri The uri to parse
     *
     * @return UriContract A new instance with the specified uri parsed to its parts
     */
    public static function fromString(string $uri): UriContract
    {
        if (
            $uri !== ''
            && ! str_starts_with($uri, '/')
            && ! str_starts_with($uri, Scheme::HTTP->value)
            && ! str_starts_with($uri, Scheme::HTTPS->value)
        ) {
            $uri = '//' . $uri;
        }

        $parts = parse_url($uri);

        if ($parts === false) {
            throw new InvalidArgumentException("Invalid uri `$uri` provided");
        }

        return new Uri(
            scheme: self::filterScheme($parts['scheme'] ?? ''),
            username: $parts['user'] ?? '',
            password: $parts['pass'] ?? '',
            host: $parts['host'] ?? '',
            port: $parts['port'] ?? null,
            path: $parts['path'] ?? '',
            query: $parts['query'] ?? '',
            fragment: $parts['fragment'] ?? ''
        );
    }

    /**
     * Convert a Uri instance to a string.
     */
    public static function toString(UriContract $uri): string
    {
        return self::getSchemeStringPart($uri)
            . self::getAuthorityStringPart($uri)
            . self::getPathStringPart($uri)
            . self::getQueryStringPart($uri)
            . self::getFragmentStringPart($uri);
    }

    /**
     * Filter a scheme.
     *
     * @param string $scheme The scheme
     */
    public static function filterScheme(string $scheme): Scheme
    {
        $scheme = strtolower($scheme);
        $scheme = (string) preg_replace('#:(//)?$#', '', $scheme);

        return Scheme::from($scheme);
    }

    /**
     * Validate a port.
     *
     * @param int|null $port The port
     *
     * @throws InvalidPortException
     */
    public static function validatePort(int|null $port = null): void
    {
        if (! Port::isValid($port)) {
            throw new InvalidPortException("Invalid port `%$port` specified; must be a valid TCP/UDP port");
        }
    }

    /**
     * Filter user info.
     */
    public static function filterUserInfo(string $userInfo): string
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
     */
    public static function filterPath(string $path): string
    {
        self::validatePath($path);

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
     */
    public static function validatePath(string $path): void
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
     */
    public static function filterQuery(string $query): string
    {
        self::validateQuery($query);

        // TODO: Filter query

        return ltrim($query, '?');
    }

    /**
     * Validate a query.
     *
     * @param string $query The query
     *
     * @throws InvalidQueryException
     */
    public static function validateQuery(string $query): void
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
     */
    public static function filterFragment(string $fragment): string
    {
        self::validateFragment($fragment);

        // TODO: Filter fragment

        return ltrim($fragment, '#');
    }

    /**
     * Validate a fragment.
     *
     * @param string $fragment The fragment
     */
    public static function validateFragment(string $fragment): void
    {
    }

    /**
     * Determine whether this uri is on a standard port for the scheme.
     */
    public static function isStandardPort(Scheme $scheme, string $host, int|null $port = null): bool
    {
        if ($scheme === Scheme::EMPTY) {
            return $host && $port === null;
        }

        if (! $host || $port === null) {
            return true;
        }

        return self::isStandardUnsecurePort($scheme, $port) || self::isStandardSecurePort($scheme, $port);
    }

    /**
     * Is standard HTTP port.
     */
    public static function isStandardUnsecurePort(Scheme $scheme, int|null $port = null): bool
    {
        return $scheme === Scheme::HTTP && $port === Port::HTTP;
    }

    /**
     * Is standard HTTPS port.
     */
    public static function isStandardSecurePort(Scheme $scheme, int|null $port = null): bool
    {
        return $scheme === Scheme::HTTPS && $port === Port::HTTPS;
    }

    /**
     * Add scheme to uri.
     */
    public static function getSchemeStringPart(UriContract $uri): string
    {
        $scheme = $uri->getScheme();

        if ($scheme !== Scheme::EMPTY) {
            return $scheme->value . ':';
        }

        return '';
    }

    /**
     * Add authority to uri.
     */
    public static function getAuthorityStringPart(UriContract $uri): string
    {
        $authority = $uri->getAuthority();

        if ($authority !== '') {
            return '//' . $authority;
        }

        return '';
    }

    /**
     * Add path to uri.
     */
    public static function getPathStringPart(UriContract $uri): string
    {
        $path = $uri->getPath();

        if ($path !== '') {
            if ($path[0] !== '/') {
                $path = '/' . $path;
            }

            return $path;
        }

        return '';
    }

    /**
     * Add query to uri.
     */
    public static function getQueryStringPart(UriContract $uri): string
    {
        $query = $uri->getQuery();

        if ($query !== '') {
            return '?' . $query;
        }

        return '';
    }

    /**
     * Add fragment to uri.
     */
    public static function getFragmentStringPart(UriContract $uri): string
    {
        $fragment = $uri->getFragment();

        if ($fragment !== '') {
            return '#' . $fragment;
        }

        return '';
    }
}
