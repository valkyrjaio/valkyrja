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

use Valkyrja\Http\Message\Header\Contract\HeaderContract;
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

abstract class MarshalUriFactory
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

        $uri = self::addSchemeToUri($uri, $server, $headers);
        // Set the host and port
        $uri = self::addHostAndPortToUri($uri, $server, $headers);

        return self::addPathQueryAndFragmentToUri($uri, $server);
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
        $unencodedUrl = static::marshalRequestUriFromUnencodedUrl($server);

        if ($unencodedUrl !== null) {
            return $unencodedUrl;
        }

        /** @var string|null $requestUri */
        $requestUri = static::marshalRequestUriFromHttpXRewriteUrl($server)
            ?? static::marshalRequestUriFromHttpXOriginalUrl($server)
            ?? $server['REQUEST_URI']
            ?? null;

        $isRequestUriValid = static::isValidRequestUri($requestUri);

        if ($isRequestUriValid) {
            return preg_replace('#^[^/:]+://[^/]+#', '', $requestUri) ?? $requestUri;
        }

        return static::marshalRequestUriFromOrigPathInfo($server)
            ?? '/';
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
     * Marshal the request URI from the UNENCODED_URL server variable.
     *
     * @param array<string, string> $server
     */
    protected static function marshalRequestUriFromUnencodedUrl(array $server): string|null
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

        return null;
    }

    /**
     * Marshal the request URI from the HTTP_X_REWRITE_URL server variable.
     *
     * @param array<string, string> $server
     */
    protected static function marshalRequestUriFromHttpXRewriteUrl(array $server): string|null
    {
        // Check this first so IIS will catch.
        /** @var string|null $httpXRewriteUrl */
        $httpXRewriteUrl        = $server['HTTP_X_REWRITE_URL'] ?? null;
        $isHttpXRewriteUrlValid = static::isValidRequestUri($httpXRewriteUrl);

        if ($isHttpXRewriteUrlValid) {
            return $httpXRewriteUrl;
        }

        return null;
    }

    /**
     * Marshal the request URI from the HTTP_X_ORIGINAL_URL server variable.
     *
     * @param array<string, string> $server
     */
    protected static function marshalRequestUriFromHttpXOriginalUrl(array $server): string|null
    {
        // Check for IIS 7.0 or later with ISAPI_Rewrite
        /** @var string|null $httpXOriginalUrl */
        $httpXOriginalUrl = $server['HTTP_X_ORIGINAL_URL'] ?? null;

        $isXOriginalUrlValid = static::isValidRequestUri($httpXOriginalUrl);

        if ($isXOriginalUrlValid) {
            return $httpXOriginalUrl;
        }

        return null;
    }

    /**
     * Marshal the request URI from the ORIG_PATH_INFO server variable.
     *
     * @param array<string, string> $server
     */
    protected static function marshalRequestUriFromOrigPathInfo(array $server): string|null
    {
        /** @var string|null $origPathInfo */
        $origPathInfo = $server['ORIG_PATH_INFO'] ?? null;

        $isOrigPathValid = static::isValidRequestUri($origPathInfo);

        if ($isOrigPathValid) {
            return $origPathInfo;
        }

        return null;
    }

    /**
     * Determine whether the provided request uri is valid.
     *
     * @psalm-assert non-empty-string $subject
     *
     * @phpstan-assert non-empty-string $subject
     */
    protected static function isValidRequestUri(string|null $subject): bool
    {
        return $subject !== null && $subject !== '';
    }

    /**
     * Add the scheme to the Uri.
     *
     * @param array<string, string>                   $server  The server
     * @param array<lowercase-string, HeaderContract> $headers The headers
     */
    protected static function addSchemeToUri(UriContract $uri, array $server, array $headers): UriContract
    {
        // URI scheme
        $scheme = Scheme::HTTP;

        if (self::shouldSchemeBeSetToHttps($server, $headers)) {
            $scheme = Scheme::HTTPS;
        }

        return $uri->withScheme($scheme);
    }

    /**
     * Add the path, query, and fragment to the Uri.
     *
     * @param array<string, string> $server The server
     */
    protected static function addPathQueryAndFragmentToUri(UriContract $uri, array $server): UriContract
    {
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
     * Add the host and port to the Uri.
     *
     * @param array<string, string>                   $server  The server
     * @param array<lowercase-string, HeaderContract> $headers The headers
     */
    protected static function addHostAndPortToUri(UriContract $uri, array $server, array $headers): UriContract
    {
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

        return $uri;
    }

    /**
     * Determine whether the scheme should be set to HTTPS.
     *
     * @param array<string, string>                   $server  The server
     * @param array<lowercase-string, HeaderContract> $headers The headers
     */
    protected static function shouldSchemeBeSetToHttps(array $server, array $headers): bool
    {
        /** @var string|null $https */
        $https = $server['HTTPS'] ?? null;

        return ($https !== null && $https !== 'off')
            || self::getHeader('x-forwarded-proto', $headers) === Scheme::HTTPS->value;
    }

    /**
     * Marshal the host and port from the request header.
     */
    protected static function marshalHostAndPortFromHeader(HostPortAccumulator $accumulator, string $host): void
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
    protected static function marshalIpv6HostAndPort(HostPortAccumulator $accumulator, array $server): void
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
