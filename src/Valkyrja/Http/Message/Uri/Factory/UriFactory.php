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

use Valkyrja\Http\Message\Constant\Port;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidFromStringException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\HttpUriInvalidQueryException;
use Valkyrja\Http\Message\Uri\Uri;

use function ltrim;
use function parse_url;
use function preg_replace;
use function preg_replace_callback;
use function rawurlencode;
use function str_contains;
use function str_ends_with;
use function str_starts_with;
use function strtolower;
use function strtoupper;

abstract class UriFactory
{
    /**
     * The unreserved characters, which every uri component allows unencoded.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2.3
     */
    protected const string CHAR_UNRESERVED = 'a-zA-Z0-9_\-\.~';

    /**
     * The sub-delimiters, which every uri component this factory filters allows unencoded.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2.2
     */
    protected const string CHAR_SUB_DELIMS = '!\$&\'\(\)\*\+,;=';

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
            throw new HttpUriInvalidFromStringException("Invalid uri `$uri` provided");
        }

        return new Uri(
            scheme: self::filterScheme($parts['scheme'] ?? ''),
            username: $parts['user'] ?? '',
            password: $parts['pass'] ?? '',
            host: $parts['host'] ?? '',
            port: $parts['port'] ?? 0,
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
     * @param int $port The port
     *
     * @throws HttpUriInvalidPortException
     */
    public static function validatePort(int $port): void
    {
        if (! Port::isValid($port)) {
            throw new HttpUriInvalidPortException("Invalid port `%$port` specified; must be a valid TCP/UDP port");
        }
    }

    /**
     * Filter user info.
     *
     * The user info allows the unreserved characters, the sub-delimiters, and a colon. The colon
     * separates the username from the password, and a password can contain one.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.1
     */
    public static function filterUserInfo(string $userInfo): string
    {
        return self::encode($userInfo, ':');
    }

    /**
     * Filter a host.
     *
     * A host is either an IP literal or a reg-name. An IP literal is in brackets and holds
     * characters that a reg-name does not allow, so this method does not encode one.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.2
     *
     * @param string $host The host
     */
    public static function filterHost(string $host): string
    {
        $host = strtolower($host);

        if (str_starts_with($host, '[') && str_ends_with($host, ']')) {
            return $host;
        }

        return self::encode($host);
    }

    /**
     * Filter a path.
     *
     * The path allows the unreserved characters, the sub-delimiters, a colon, an at sign, and a
     * forward slash.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @param string $path The path
     *
     * @throws HttpUriInvalidPathException
     */
    public static function filterPath(string $path): string
    {
        self::validatePath($path);

        $path = self::encode($path, ':@\/');

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
     * @throws HttpUriInvalidPathException
     */
    public static function validatePath(string $path): void
    {
        if (str_contains($path, '?')) {
            throw new HttpUriInvalidPathException("Invalid path of `$path` provided; must not contain a query string");
        }

        if (str_contains($path, '#')) {
            throw new HttpUriInvalidPathException("Invalid path of `$path` provided; must not contain a URI fragment");
        }
    }

    /**
     * Filter a query.
     *
     * The query allows the unreserved characters, the sub-delimiters, a colon, an at sign, a
     * forward slash, and a question mark.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @param string $query The query
     *
     * @throws HttpUriInvalidQueryException
     */
    public static function filterQuery(string $query): string
    {
        self::validateQuery($query);

        return self::encode(ltrim($query, '?'), ':@\/\?');
    }

    /**
     * Validate a query.
     *
     * @param string $query The query
     *
     * @throws HttpUriInvalidQueryException
     */
    public static function validateQuery(string $query): void
    {
        if (str_contains($query, '#')) {
            throw new HttpUriInvalidQueryException(
                "Invalid query string of `$query` provided; must not contain a URI fragment"
            );
        }
    }

    /**
     * Filter a fragment.
     *
     * The fragment allows the same characters as the query.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     *
     * @param string $fragment The fragment
     */
    public static function filterFragment(string $fragment): string
    {
        self::validateFragment($fragment);

        return self::encode(ltrim($fragment, '#'), ':@\/\?');
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
    public static function isStandardPort(Scheme $scheme, string $host, int $port): bool
    {
        if ($scheme === Scheme::EMPTY) {
            return $host !== '' && $port <= 0;
        }

        if ($host === '' || $port <= 0) {
            return true;
        }

        return self::isStandardUnsecurePort($scheme, $port) || self::isStandardSecurePort($scheme, $port);
    }

    /**
     * Is standard HTTP port.
     */
    public static function isStandardUnsecurePort(Scheme $scheme, int $port): bool
    {
        return $scheme === Scheme::HTTP && $port === Port::HTTP;
    }

    /**
     * Is standard HTTPS port.
     */
    public static function isStandardSecurePort(Scheme $scheme, int $port): bool
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

    /**
     * Percent-encode the characters that a uri component does not allow unencoded.
     *
     * A character that is already part of a valid percent-encoded triplet is not encoded a second
     * time; the triplet keeps its meaning and its hexadecimal digits become uppercase. A percent
     * sign that does not begin a valid triplet is a literal percent sign, so this method encodes
     * it.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2.1
     *
     * @param string $value        The component value
     * @param string $extraAllowed [optional] The character class atoms the component also allows
     */
    protected static function encode(string $value, string $extraAllowed = ''): string
    {
        $allowed = self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . $extraAllowed;

        return (string) preg_replace_callback(
            '/(%[A-Fa-f0-9]{2})|[^' . $allowed . ']+/',
            /**
             * @param array<array-key, string> $matches
             */
            static function (array $matches): string {
                $triplet = $matches[1] ?? '';

                if ($triplet !== '') {
                    return strtoupper($triplet);
                }

                return rawurlencode($matches[0]);
            },
            $value
        );
    }
}
