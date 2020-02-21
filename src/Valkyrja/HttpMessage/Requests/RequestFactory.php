<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage\Requests;

use InvalidArgumentException;
use stdClass;
use UnexpectedValueException;
use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\HttpMessage\Exceptions\InvalidMethod;
use Valkyrja\HttpMessage\Exceptions\InvalidPath;
use Valkyrja\HttpMessage\Exceptions\InvalidPort;
use Valkyrja\HttpMessage\Exceptions\InvalidProtocolVersion;
use Valkyrja\HttpMessage\Exceptions\InvalidQuery;
use Valkyrja\HttpMessage\Exceptions\InvalidScheme;
use Valkyrja\HttpMessage\Exceptions\InvalidStream;
use Valkyrja\HttpMessage\Exceptions\InvalidUploadedFile;
use Valkyrja\HttpMessage\Files\UploadedFile;
use Valkyrja\HttpMessage\Streams\Stream;
use Valkyrja\HttpMessage\Uri;
use Valkyrja\HttpMessage\Uris\Uri as HttpUri;

use function array_key_exists;
use function is_array;
use function is_callable;
use function strlen;

/**
 * Abstract Class RequestFactory.
 *
 * @author Melech Mizrachi
 */
abstract class RequestFactory
{
    /**
     * Function to use to get apache request headers; present only to simplify mocking.
     *
     * @var callable
     */
    private static $apacheRequestHeaders = 'apache_request_headers';

    /**
     * Create a request from the supplied superglobal values.
     * If any argument is not supplied, the corresponding superglobal value will
     * be used.
     * The ServerRequest created is then passed to the fromServer() method in
     * order to marshal the request URI and headers.
     *
     * @param array $server  $_SERVER superglobal
     * @param array $query   $_GET superglobal
     * @param array $body    $_POST superglobal
     * @param array $cookies $_COOKIE superglobal
     * @param array $files   $_FILES superglobal
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws InvalidUploadedFile
     * @throws InvalidStream
     * @throws InvalidScheme
     * @throws InvalidQuery
     * @throws InvalidProtocolVersion
     * @throws InvalidPort
     * @throws InvalidPath
     * @throws InvalidMethod
     *
     * @return Request
     *
     * @see fromServer()
     */
    public static function fromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null
    ): Request {
        $server  = static::normalizeServer($server ?: $_SERVER);
        $files   = static::normalizeFiles($files ?: $_FILES);
        $headers = static::marshalHeaders($server);

        if (null === $cookies && array_key_exists('cookie', $headers)) {
            $cookies = self::parseCookieHeader($headers['cookie']);
        }

        return new Request(
            static::marshalUriFromServer($server, $headers),
            static::get('REQUEST_METHOD', $server, RequestMethod::GET),
            new Stream('php://input'),
            $headers,
            $server,
            $cookies ?? $_COOKIE,
            $query ?? $_GET,
            $body ?? $_POST,
            $files,
            static::marshalProtocolVersion($server)
        );
    }

    /**
     * Marshal the $_SERVER array.
     * Pre-processes and returns the $_SERVER superglobal.
     *
     * @param array $server
     *
     * @return array
     */
    public static function normalizeServer(array $server): array
    {
        // This seems to be the only way to get the Authorization header on Apache
        $apacheRequestHeaders = self::$apacheRequestHeaders;

        if (isset($server['HTTP_AUTHORIZATION']) || ! is_callable($apacheRequestHeaders)) {
            return $server;
        }

        $apacheRequestHeaders = $apacheRequestHeaders();

        if (isset($apacheRequestHeaders['Authorization'])) {
            $server['HTTP_AUTHORIZATION'] = $apacheRequestHeaders['Authorization'];

            return $server;
        }

        if (isset($apacheRequestHeaders['authorization'])) {
            $server['HTTP_AUTHORIZATION'] = $apacheRequestHeaders['authorization'];

            return $server;
        }

        return $server;
    }

    /**
     * Normalize uploaded files.
     * Transforms each value into an UploadedFileInterface instance, and ensures
     * that nested arrays are normalized.
     *
     * @param array $files
     *
     * @throws InvalidArgumentException for unrecognized values
     *
     * @return array
     */
    public static function normalizeFiles(array $files): array
    {
        $normalized = [];

        foreach ($files as $key => $value) {
            if ($value instanceof UploadedFile) {
                $normalized[$key] = $value;
                continue;
            }

            if (is_array($value) && isset($value['tmp_name'])) {
                $normalized[$key] = self::createUploadedFileFromSpec($value);
                continue;
            }

            if (is_array($value)) {
                $normalized[$key] = self::normalizeFiles($value);
                continue;
            }

            throw new InvalidArgumentException('Invalid value in files specification');
        }

        return $normalized;
    }

    /**
     * Marshal headers from $_SERVER.
     *
     * @param array $server
     *
     * @return array
     */
    public static function marshalHeaders(array $server): array
    {
        $headers = [];

        foreach ($server as $key => $value) {
            // Apache prefixes environment variables with REDIRECT_
            // if they are added by rewrite rules
            if (strpos($key, 'REDIRECT_') === 0) {
                $key = substr($key, 9);

                // We will not overwrite existing variables with the
                // prefixed versions, though
                if (array_key_exists($key, $server)) {
                    continue;
                }
            }

            if ($value && strpos($key, 'HTTP_') === 0) {
                $name           = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[$name] = $value;

                continue;
            }

            if ($value && strpos($key, 'CONTENT_') === 0) {
                $name           = 'content-' . strtolower(substr($key, 8));
                $headers[$name] = $value;
            }
        }

        return $headers;
    }

    /**
     * Parse a cookie header according to RFC 6265.
     * PHP will replace special characters in cookie names, which results in other cookies not being available due to
     * overwriting. Thus, the server request should take the cookies from the request header instead.
     *
     * @param string $cookieHeader
     *
     * @return array
     */
    private static function parseCookieHeader(string $cookieHeader): array
    {
        preg_match_all(
            '(
            (?:^\\n?[ \t]*|;[ ])
            (?P<name>[!#$%&\'*+\-.0-9A-Z^_`a-z|~]+)
            =
            (?P<DQUOTE>"?)
                (?P<value>[\x21\x23-\x2b\x2d-\x3a\x3c-\x5b\x5d-\x7e]*)
            (?P=DQUOTE)
            (?=\\n?[ \t]*$|;[ ])
        )x',
            $cookieHeader,
            $matches,
            PREG_SET_ORDER
        );

        $cookies = [];

        /** @var array $matches */
        foreach ($matches as $match) {
            $cookies[$match['name']] = urldecode($match['value']);
        }

        return $cookies;
    }

    /**
     * Marshal the URI from the $_SERVER array and headers.
     *
     * @param array $server
     * @param array $headers
     *
     * @throws InvalidQuery
     * @throws InvalidPort
     * @throws InvalidPath
     * @throws InvalidScheme
     *
     * @return Uri
     */
    public static function marshalUriFromServer(array $server, array $headers): Uri
    {
        $uri = new HttpUri();

        // URI scheme
        $scheme = 'http';
        $https  = self::get('HTTPS', $server);

        if (($https && 'off' !== $https)
            || self::getHeader('x-forwarded-proto', $headers, false) === 'https'
        ) {
            $scheme = 'https';
        }

        if (! empty($scheme)) {
            $uri = $uri->withScheme($scheme);
        }

        // Set the host
        $accumulator = (object) ['host' => '', 'port' => null];

        self::marshalHostAndPortFromHeaders($accumulator, $server, $headers);

        $host = $accumulator->host;
        $port = $accumulator->port;

        if (! empty($host)) {
            $uri = $uri->withHost($host);

            if (! empty($port)) {
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

        if (strpos($path, '#') !== false) {
            [$path, $fragment] = explode('#', $path, 2);
        }

        return $uri->withPath($path)->withFragment($fragment)->withQuery($query);
    }

    /**
     * Access a value in an array, returning a default value if not found.
     * Will also do a case-insensitive search if a case sensitive search fails.
     *
     * @param string $key
     * @param array  $values
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function get($key, array $values, $default = null)
    {
        if (array_key_exists($key, $values)) {
            return $values[$key];
        }

        return $default;
    }

    /**
     * Return HTTP protocol version (X.Y).
     *
     * @param array $server
     *
     * @throws UnexpectedValueException
     *
     * @return string
     */
    protected static function marshalProtocolVersion(array $server): string
    {
        if (! isset($server['SERVER_PROTOCOL'])) {
            return '1.1';
        }

        if (! preg_match('#^(HTTP/)?(?P<version>[1-9]\d*(?:\.\d)?)$#', $server['SERVER_PROTOCOL'], $matches)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Unrecognized protocol version (%s)',
                    $server['SERVER_PROTOCOL']
                )
            );
        }

        return $matches['version'];
    }

    /**
     * Create and return an UploadedFile instance from a $_FILES specification.
     * If the specification represents an array of values, this method will
     * delegate to normalizeNestedFileSpec() and return that return value.
     *
     * @param array $value $_FILES struct
     *
     * @throws InvalidArgumentException
     *
     * @return array|UploadedFile
     */
    private static function createUploadedFileFromSpec(array $value)
    {
        if (is_array($value['tmp_name'])) {
            return self::normalizeNestedFileSpec($value);
        }

        return new UploadedFile(
            $value['size'],
            $value['error'],
            $value['tmp_name'],
            null,
            $value['name'],
            $value['type']
        );
    }

    /**
     * Search for a header value.
     * Does a case-insensitive search for a matching header.
     * If found, it is returned as a string, using comma concatenation.
     * If not, the $default is returned.
     *
     * @param string $header
     * @param array  $headers
     * @param mixed  $default
     *
     * @return string
     */
    public static function getHeader(string $header, array $headers, $default = null): string
    {
        $header  = strtolower($header);
        $headers = array_change_key_case($headers, CASE_LOWER);

        if (array_key_exists($header, $headers)) {
            return is_array($headers[$header]) ? implode(', ', $headers[$header]) : $headers[$header];
        }

        return (string) ($default ?? '');
    }

    /**
     * Marshal the host and port from HTTP headers and/or the PHP environment.
     *
     * @param stdClass $accumulator
     * @param array    $server
     * @param array    $headers
     *
     * @return void
     */
    public static function marshalHostAndPortFromHeaders(stdClass $accumulator, array $server, array $headers): void
    {
        if (self::getHeader('host', $headers, false)) {
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
     * @param array $server
     *
     * @return string
     */
    public static function marshalRequestUri(array $server): string
    {
        // IIS7 with URL Rewrite: make sure we get the unencoded url
        // (double slash problem).
        $iisUrlRewritten = self::get('IIS_WasUrlRewritten', $server);
        $unencodedUrl    = self::get('UNENCODED_URL', $server, '');

        if ('1' === $iisUrlRewritten && ! empty($unencodedUrl)) {
            return $unencodedUrl;
        }

        $requestUri = self::get('REQUEST_URI', $server);

        // Check this first so IIS will catch.
        $httpXRewriteUrl = self::get('HTTP_X_REWRITE_URL', $server);

        if ($httpXRewriteUrl !== null) {
            $requestUri = $httpXRewriteUrl;
        }

        // Check for IIS 7.0 or later with ISAPI_Rewrite
        $httpXOriginalUrl = self::get('HTTP_X_ORIGINAL_URL', $server);

        if ($httpXOriginalUrl !== null) {
            $requestUri = $httpXOriginalUrl;
        }

        if ($requestUri !== null) {
            return preg_replace('#^[^/:]+://[^/]+#', '', $requestUri);
        }

        $origPathInfo = self::get('ORIG_PATH_INFO', $server);

        if (empty($origPathInfo)) {
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
     * Normalize an array of file specifications.
     * Loops through all nested files and returns a normalized array of
     * UploadedFileInterface instances.
     *
     * @param array $files
     *
     * @throws InvalidArgumentException
     *
     * @return UploadedFile[]
     */
    private static function normalizeNestedFileSpec(array $files = []): array
    {
        $normalizedFiles = [];

        foreach (array_keys($files['tmp_name']) as $key) {
            $spec                  = [
                'tmp_name' => $files['tmp_name'][$key],
                'size'     => $files['size'][$key],
                'error'    => $files['error'][$key],
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
            ];
            $normalizedFiles[$key] = self::createUploadedFileFromSpec($spec);
        }

        return $normalizedFiles;
    }

    /**
     * Marshal the host and port from the request header.
     *
     * @param stdClass     $accumulator
     * @param string|array $host
     *
     * @return void
     */
    private static function marshalHostAndPortFromHeader(stdClass $accumulator, $host): void
    {
        if (is_array($host)) {
            $host = implode(', ', $host);
        }

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
     * @param stdClass $accumulator
     * @param array    $server
     *
     * @return void
     */
    private static function marshalIpv6HostAndPort(stdClass $accumulator, array $server): void
    {
        $accumulator->host = '[' . $server['SERVER_ADDR'] . ']';
        $accumulator->port = $accumulator->port ?: 80;

        if ($accumulator->port . ']' === substr($accumulator->host, strrpos($accumulator->host, ':') + 1)) {
            // The last digit of the IPv6-Address has been taken as port
            // Unset the port so the default port can be used
            $accumulator->port = null;
        }
    }
}