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

namespace Valkyrja\Http\Message\Factory;

use Psr\Http\Message\UriInterface;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Data\HostPortAccumulator;
use Valkyrja\Http\Message\Uri\Enum\Scheme;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPathException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidPortException;
use Valkyrja\Http\Message\Uri\Throwable\Exception\InvalidQueryException;
use Valkyrja\Http\Message\Uri\Uri as HttpUri;

use function array_change_key_case;
use function array_key_exists;
use function explode;
use function implode;
use function is_array;
use function ltrim;
use function preg_match;
use function preg_replace;
use function strlen;
use function strpos;
use function strrpos;
use function strtolower;
use function substr;

/**
 * Abstract Class UriFactory.
 */
abstract class UriFactory
{
    /**
     * Marshal the URI from the $_SERVER array and headers.
     *
     * @param array<string, string>          $server
     * @param array<string, string|string[]> $headers
     *
     * @throws InvalidQueryException
     * @throws InvalidPortException
     * @throws InvalidPathException
     *
     * @return UriContract
     */
    public static function marshalUriFromServer(array $server, array $headers): UriContract
    {
        $uri = new HttpUri();

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
     * @param string                         $header
     * @param array<string, string|string[]> $headers
     * @param string|null                    $default
     *
     * @return string
     */
    public static function getHeader(string $header, array $headers, string|null $default = null): string
    {
        $header  = strtolower($header);
        $headers = array_change_key_case($headers);

        if (array_key_exists($header, $headers)) {
            return is_array($headers[$header])
                ? implode(', ', $headers[$header])
                : $headers[$header];
        }

        return $default ?? '';
    }

    /**
     * Marshal the host and port from HTTP headers and/or the PHP environment.
     *
     * @param HostPortAccumulator            $accumulator
     * @param array<string, string>          $server
     * @param array<string, string|string[]> $headers
     *
     * @return void
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
     *
     * @return string
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
     *
     * @param string $path
     *
     * @return string
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
     * @param UriInterface $uri The PSR uri
     *
     * @return UriContract
     */
    public static function fromPsr(UriInterface $uri): UriContract
    {
        $userInfo = $uri->getUserInfo();
        $user     = '';
        $password = null;

        if ($userInfo !== '') {
            [$user, $password] = explode(':', $userInfo);
        }

        return new HttpUri()
            ->withScheme(Scheme::from($uri->getScheme()))
            ->withUserInfo($user, $password)
            ->withHost($uri->getHost())
            ->withPort($uri->getPort())
            ->withPath($uri->getPath())
            ->withQuery($uri->getQuery())
            ->withFragment($uri->getFragment());
    }

    /**
     * Marshal the host and port from the request header.
     *
     * @param HostPortAccumulator $accumulator
     * @param string              $host
     *
     * @return void
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
     * @param HostPortAccumulator   $accumulator
     * @param array<string, string> $server
     *
     * @return void
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
