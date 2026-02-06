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
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Data\HostPortAccumulator;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException;
use Valkyrja\Http\Message\Uri\Uri;

use function array_change_key_case;
use function explode;
use function ltrim;
use function preg_match;
use function preg_replace;
use function strlen;
use function strpos;
use function strrpos;
use function strtolower;
use function substr;

abstract class UriFactory
{
    /**
     * Marshal the URI from the $_SERVER array and headers.
     *
     * @param array<string, string>                   $server  The server
     * @param array<lowercase-string, HeaderContract> $headers The headers
     *
     * @throws InvalidQueryException
     * @throws InvalidPortException
     * @throws InvalidPathException
     */
    public static function marshalUriFromServer(array $server, array $headers): UriContract
    {
        $uri = new Uri();

        // URI scheme
        $scheme = Scheme::HTTP;
        /** @var string|null $https */
        $https = $server['HTTPS'] ?? null;

        if (
            ($https !== null && $https !== 'off')
            || self::getHeader('x-forwarded-proto', $headers) === Scheme::HTTPS->value
        ) {
            $scheme = Scheme::HTTPS;
        }

        $uri = $uri->withScheme($scheme);

        // Set the host
        $accumulator = new HostPortAccumulator();

        self::marshalHostAndPortFromHeaders($accumulator, $server, $headers);

        /** @var string $host */
        $host = $accumulator->host;
        /** @var int|null $port */
        $port = $accumulator->port;

        if (! empty($host)) {
            $uri = $uri->withHost($host);

            if ($port !== null) {
                $uri = $uri->withPort($port);
            }
        }

        // URI path
        $path = self::marshalRequestUri($server);
        $path = self::stripQueryString($path);

        // URI query
        $query = '';

        if (isset($server['QUERY_STRING'])) {
            $query = ltrim($server['QUERY_STRING'], '?');
        }

        // URI fragment
        $fragment = '';

        if (str_contains($path, '#')) {
            [$path, $fragment] = explode('#', $path);
        }

        return $uri
            ->withPath($path)
            ->withQuery($query)
            ->withFragment($fragment);
    }

    /**
     * Search for a header value.
     * Does a case-insensitive search for a matching header.
     * If found, it is returned as a string, using comma concatenation.
     * If not, the $default is returned.
     *
     * @param array<lowercase-string, HeaderContract> $headers
     */
    public static function getHeader(string $headerName, array $headers, string|null $default = null): string
    {
        $headerName  = strtolower($headerName);
        $headers     = array_change_key_case($headers);

        $header = $headers[$headerName] ?? null;

        return $header?->getValuesAsString() ?? $default ?? '';
    }

    /**
     * Marshal the host and port from HTTP headers and/or the PHP environment.
     *
     * @param array<string, string>                   $server
     * @param array<lowercase-string, HeaderContract> $headers
     */
    public static function marshalHostAndPortFromHeaders(
        HostPortAccumulator $accumulator,
        array $server,
        array $headers
    ): void {
        if (self::getHeader('host', $headers) !== '') {
            self::marshalHostAndPortFromHeader($accumulator, self::getHeader('host', $headers));

            return;
        }

        if (! isset($server['SERVER_NAME'])) {
            return;
        }

        $accumulator->host = $server['SERVER_NAME'];

        if (isset($server['SERVER_PORT'])) {
            $accumulator->port = (int) $server['SERVER_PORT'];
        }

        if (! isset($server['SERVER_ADDR']) || ! preg_match('/^\[[0-9a-fA-F\:]+\]$/', $accumulator->host)) {
            return;
        }

        // Misinterpreted IPv6-Address
        // Reported for Safari on Windows
        self::marshalIpv6HostAndPort($accumulator, $server);
    }

    /**
     * Detect the base URI for the request.
     * Looks at a variety of criteria in order to attempt to autodetect a base
     * URI, including rewrite URIs, proxy URIs, etc.
     * From ZF2's Zend\Http\PhpEnvironment\Request class.
     *
     * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
     * @license   http://framework.zend.com/license/new-bsd New BSD License
     *
     * @param array<string, string> $server
     */
    public static function marshalRequestUri(array $server): string
    {
        // IIS7 with URL Rewrite: make sure we get the unencoded url
        // (double slash problem).
        /** @var string|null $iisUrlRewritten */
        $iisUrlRewritten = $server['IIS_WasUrlRewritten'] ?? null;
        /** @var string $unencodedUrl */
        $unencodedUrl = $server['UNENCODED_URL'] ?? '';

        if ($iisUrlRewritten === '1' && $unencodedUrl !== '') {
            return $unencodedUrl;
        }

        /** @var string|null $requestUri */
        $requestUri = $server['REQUEST_URI'] ?? null;

        // Check this first so IIS will catch.
        /** @var string|null $httpXRewriteUrl */
        $httpXRewriteUrl = $server['HTTP_X_REWRITE_URL'] ?? null;

        if ($httpXRewriteUrl !== null && $httpXRewriteUrl !== '') {
            $requestUri = $httpXRewriteUrl;
        }

        // Check for IIS 7.0 or later with ISAPI_Rewrite
        /** @var string|null $httpXOriginalUrl */
        $httpXOriginalUrl = $server['HTTP_X_ORIGINAL_URL'] ?? null;

        if ($httpXOriginalUrl !== null && $httpXOriginalUrl !== '') {
            $requestUri = $httpXOriginalUrl;
        }

        if ($requestUri !== null && $requestUri !== '') {
            return preg_replace('#^[^/:]+://[^/]+#', '', $requestUri) ?? $requestUri;
        }

        /** @var string|null $origPathInfo */
        $origPathInfo = $server['ORIG_PATH_INFO'] ?? null;

        if ($origPathInfo === null || $origPathInfo === '') {
            return '/';
        }

        return $origPathInfo;
    }

    /**
     * Strip the query string from a path.
     */
    public static function stripQueryString(string $path): string
    {
        if (($queryPos = strpos($path, '?')) !== false) {
            return substr($path, 0, $queryPos);
        }

        return $path;
    }

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

    /**
     * Marshal the host and port from the request header.
     */
    private static function marshalHostAndPortFromHeader(HostPortAccumulator $accumulator, string $host): void
    {
        $accumulator->host = $host;
        $accumulator->port = null;

        // Works for regname, IPv4 & IPv6
        if (preg_match('|\:(\d+)$|', $accumulator->host, $matches)) {
            $accumulator->host = substr($accumulator->host, 0, -1 * (strlen($matches[1]) + 1));
            $accumulator->port = (int) $matches[1];
        }
    }

    /**
     * Marshal host/port from misinterpreted IPv6 address.
     *
     * @param array<string, string> $server
     */
    private static function marshalIpv6HostAndPort(HostPortAccumulator $accumulator, array $server): void
    {
        $accumulator->host = '[' . $server['SERVER_ADDR'] . ']';
        $accumulator->port ??= 80;

        $portOffset = strrpos($accumulator->host, ':');

        if ($portOffset !== false && (((string) $accumulator->port) . ']') === substr($accumulator->host, $portOffset + 1)) {
            // The last digit of the IPv6-Address has been taken as port
            // Unset the port so the default port can be used
            $accumulator->port = null;
        }
    }
}
