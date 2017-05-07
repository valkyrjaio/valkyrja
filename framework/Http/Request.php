<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Files as FilesContract;
use Valkyrja\Contracts\Http\Headers as HeadersContract;
use Valkyrja\Contracts\Http\Server as ServerContract;
use Valkyrja\Contracts\Support\Collection as CollectionContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Support\Collection;

/**
 * Class Request
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class Request implements RequestContract
{
    protected static $httpMethodParameterOverride = false;

    /**
     * Custom parameters.
     *
     * @var \Valkyrja\Contracts\Support\Collection
     */
    protected $attributes;

    /**
     * Request body parameters ($_POST).
     *
     * @var \Valkyrja\Contracts\Support\Collection
     */
    protected $request;

    /**
     * Query string parameters ($_GET).
     *
     * @var \Valkyrja\Contracts\Http\Query
     */
    protected $query;

    /**
     * Server and execution environment parameters ($_SERVER).
     *
     * @var \Valkyrja\Contracts\Http\Server
     */
    protected $server;

    /**
     * Uploaded files ($_FILES).
     *
     * @var \Valkyrja\Contracts\Http\Files
     */
    protected $files;

    /**
     * Cookies ($_COOKIE).
     *
     * @var \Valkyrja\Contracts\Support\Collection
     */
    protected $cookies;

    /**
     * Headers (taken from the $_SERVER).
     *
     * @var \Valkyrja\Contracts\Http\Headers
     */
    protected $headers;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var array
     */
    protected $languages;

    /**
     * @var array
     */
    protected $charsets;

    /**
     * @var array
     */
    protected $encodings;

    /**
     * @var array
     */
    protected $acceptableContentTypes;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $requestUri;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $defaultLocale = 'en';

    /**
     * @var array
     */
    protected static $formats;

    /**
     * Request Constructor.
     *
     * @param array           $query      The GET parameters
     * @param array           $request    The POST parameters
     * @param array           $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array           $cookies    The COOKIE parameters
     * @param array           $files      The FILES parameters
     * @param array           $server     The SERVER parameters
     * @param string|resource $content    The raw body data
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    )
    {
        $this
            ->setQuery($query)
            ->setRequest($request)
            ->setAttributes($attributes)
            ->setCookies($cookies)
            ->setFiles($files)
            ->setServer($server)
            ->setHeaders()
            ->setContent($content)
            ->setLanguages()
            ->setCharsets()
            ->setEncodings()
            ->setAcceptableContentTypes();

        $this->path = null;
        $this->requestUri = null;
        $this->baseUrl = null;
        $this->basePath = null;
        $this->method = null;
        $this->format = null;
    }

    /**
     * Create a new Request instance.
     *
     * @param array           $query      The GET parameters
     * @param array           $request    The POST parameters
     * @param array           $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array           $cookies    The COOKIE parameters
     * @param array           $files      The FILES parameters
     * @param array           $server     The SERVER parameters
     * @param string|resource $content    The raw body data
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public static function factory(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ): RequestContract
    {
        return new static($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Creates a new request with values from PHP's super globals.
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public static function createFromGlobals(): RequestContract
    {
        // Create a new request from the PHP globals
        /** @var RequestContract $request */
        $request = new static($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        if (
            0 === strpos($request->headers()->get('Content-Type'), 'application/x-www-form-urlencoded')
            &&
            in_array(
                strtoupper($request->server()->get('REQUEST_METHOD', RequestMethod::GET)),
                [
                    RequestMethod::PUT,
                    RequestMethod::DELETE,
                    RequestMethod::PATCH,
                ],
                true
            )
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new Collection($data);
        }

        return $request;
    }

    /**
     * Creates a Request based on a given URI and configuration.
     *
     * The information contained in the URI always take precedence
     * over the other information (server and parameters).
     *
     * @param string $uri        The URI
     * @param string $method     The HTTP method
     * @param array  $parameters The query (GET) or request (POST) parameters
     * @param array  $cookies    The request cookies ($_COOKIE)
     * @param array  $files      The request files ($_FILES)
     * @param array  $server     The server parameters ($_SERVER)
     * @param string $content    The raw body data
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public static function create(
        $uri,
        $method = RequestMethod::GET,
        $parameters = [],
        $cookies = [],
        $files = [],
        $server = [],
        $content = null
    ): RequestContract
    {
        $server = array_replace(
            [
                'SERVER_NAME'          => 'localhost',
                'SERVER_PORT'          => 80,
                'HTTP_HOST'            => 'localhost',
                'HTTP_USER_AGENT'      => config()->app->version,
                'HTTP_ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
                'HTTP_ACCEPT_CHARSET'  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                'REMOTE_ADDR'          => '127.0.0.1',
                'SCRIPT_NAME'          => '',
                'SCRIPT_FILENAME'      => '',
                'SERVER_PROTOCOL'      => 'HTTP/1.1',
                'REQUEST_TIME'         => time(),
            ],
            $server
        );

        $server['PATH_INFO'] = '';
        $server['REQUEST_METHOD'] = strtoupper($method);
        $components = parse_url($uri);
        $request = [];
        $query = [];

        if (isset($components['host'])) {
            $server['SERVER_NAME'] = $components['host'];
            $server['HTTP_HOST'] = $components['host'];
        }

        if (isset($components['scheme'])) {
            if ('https' === $components['scheme']) {
                $server['HTTPS'] = 'on';
                $server['SERVER_PORT'] = 443;
            }
            else {
                unset($server['HTTPS']);
                $server['SERVER_PORT'] = 80;
            }
        }

        if (isset($components['port'])) {
            $server['SERVER_PORT'] = $components['port'];
            $server['HTTP_HOST'] = $server['HTTP_HOST'] . ':' . $components['port'];
        }

        if (isset($components['user'])) {
            $server['PHP_AUTH_USER'] = $components['user'];
        }

        if (isset($components['pass'])) {
            $server['PHP_AUTH_PW'] = $components['pass'];
        }

        if (! isset($components['path'])) {
            $components['path'] = '/';
        }

        switch (strtoupper($method)) {
            case RequestMethod::POST :
            case RequestMethod::PUT :
            case RequestMethod::DELETE :
                if (! isset($server['CONTENT_TYPE'])) {
                    $server['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
                }

                $query = $parameters;

                break;
            case RequestMethod::PATCH :
                $request = $parameters;
                break;
            default :
                $query = $parameters;
                break;
        }

        $queryString = '';

        if (isset($components['query'])) {
            parse_str(html_entity_decode($components['query']), $qs);

            if ($query) {
                $query = array_replace($qs, $query);
                $queryString = http_build_query($query, '', '&');
            }
            else {
                $query = $qs;
                $queryString = $components['query'];
            }
        }
        else if ($query) {
            $queryString = http_build_query($query, '', '&');
        }

        $server['REQUEST_URI'] = $components['path'] . ('' !== $queryString ? '?' . $queryString : '');
        $server['QUERY_STRING'] = $queryString;

        return self::factory($query, $request, [], $cookies, $files, $server, $content);
    }

    /**
     * Clones the current request.
     *
     * Note that the session is not cloned as duplicated requests
     * are most of the time sub-requests of the main one.
     */
    public function __clone()
    {
        $this->query = clone $this->query;
        $this->request = clone $this->request;
        $this->attributes = clone $this->attributes;
        $this->cookies = clone $this->cookies;
        $this->files = clone $this->files;
        $this->server = clone $this->server;
        $this->headers = clone $this->headers;
    }

    /**
     * Returns the request as a string.
     *
     * @return string The request
     */
    public function __toString(): string
    {
        return
            sprintf('%s %s %s', $this->getMethod(), $this->getRequestUri(), $this->server->get('SERVER_PROTOCOL'))
            . "\r\n"
            . $this->headers
            . "\r\n"
            . $this->getContent();
    }

    /**
     * Return the GET Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function query(): CollectionContract
    {
        if (! $this->query) {
            $this->setQuery();
        }

        return $this->query;
    }

    /**
     * Set the GET parameters.
     *
     * @param array $query
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setQuery(array $query = []): RequestContract
    {
        $this->query = new Query($query);

        return $this;
    }

    /**
     * Return the POST Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function request(): CollectionContract
    {
        if (! $this->request) {
            $this->setRequest();
        }

        return $this->request;
    }

    /**
     * Set the POST parameters.
     *
     * @param array $request
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setRequest(array $request = []): RequestContract
    {
        $this->request = new Collection($request);

        return $this;
    }

    /**
     * Return the attributes Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function attributes(): CollectionContract
    {
        if (! $this->attributes) {
            $this->setAttributes();
        }

        return $this->attributes;
    }

    /**
     * Set the attributes.
     *
     * @param array $attributes
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setAttributes(array $attributes = []): RequestContract
    {
        $this->attributes = new Collection($attributes);

        return $this;
    }

    /**
     * Return the COOKIES Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function cookies(): CollectionContract
    {
        if (! $this->cookies) {
            $this->setCookies();
        }

        return $this->cookies;
    }

    /**
     * Set the COOKIES parameters.
     *
     * @param array $cookies
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setCookies(array $cookies = []): RequestContract
    {
        $this->cookies = new Collection($cookies);

        return $this;
    }

    /**
     * Return the FILES Collection.
     *
     * @return \Valkyrja\Contracts\Http\Files
     */
    public function files(): FilesContract
    {
        if (! $this->files) {
            $this->setFiles();
        }

        return $this->files;
    }

    /**
     * Set the FILES parameters.
     *
     * @param array $files
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setFiles(array $files = []): RequestContract
    {
        $this->files = new Files($files);

        return $this;
    }

    /**
     * Return the SERVER Collection.
     *
     * @return \Valkyrja\Contracts\Http\Server
     */
    public function server(): ServerContract
    {
        if (! $this->server) {
            $this->setServer();
        }

        return $this->server;
    }

    /**
     * Set the SERVER parameters.
     *
     * @param array $server
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setServer(array $server = []): RequestContract
    {
        $server = $server
            ?: $_SERVER;

        $this->server = new Server($server);

        return $this;
    }

    /**
     * Return the headers Collection.
     *
     * @return \Valkyrja\Contracts\Http\Headers
     */
    public function headers(): HeadersContract
    {
        if (! $this->headers) {
            $this->setHeaders();
        }

        return $this->headers;
    }

    /**
     * Set the headers parameters.
     *
     * @param array $headers
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setHeaders(array $headers = []): RequestContract
    {
        $headers = $headers
            ?: $this->server()->getHeaders();

        $this->headers = new Headers($headers);

        return $this;
    }

    /**
     * Get the content.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the content.
     *
     * @param string $content
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setContent(string $content = null): RequestContract
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the languages.
     *
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * Set the languages.
     *
     * @param array $languages
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setLanguages(array $languages = []): RequestContract
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get the charsets.
     *
     * @return array
     */
    public function getCharsets(): array
    {
        return $this->charsets;
    }

    /**
     * Set the charsets.
     *
     * @param array $charsets
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setCharsets(array $charsets = []): RequestContract
    {
        $this->charsets = $charsets;

        return $this;
    }

    /**
     * Get the encodings.
     *
     * @return array
     */
    public function getEncodings(): array
    {
        return $this->encodings;
    }

    /**
     * Set the encodings.
     *
     * @param array $encodings
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setEncodings(array $encodings = []): RequestContract
    {
        $this->encodings = $encodings;

        return $this;
    }

    /**
     * Get the acceptable content types.
     *
     * @return array
     */
    public function getAcceptableContentTypes(): array
    {
        return $this->acceptableContentTypes;
    }

    /**
     * Set the acceptable content types.
     *
     * @param array $acceptableContentTypes
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setAcceptableContentTypes(array $acceptableContentTypes = []): RequestContract
    {
        $this->acceptableContentTypes = $acceptableContentTypes;

        return $this;
    }

    /**
     * Gets a "parameter" value from any bag.
     *
     * @param string $key     the key
     * @param mixed  $default the default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get(string $key, $default = null) // : mixed
    {
        if ($this !== $result = $this->attributes->get($key, $this)) {
            return $result;
        }

        if ($this !== $result = $this->query->get($key, $this)) {
            return $result;
        }

        if ($this !== $result = $this->request->get($key, $this)) {
            return $result;
        }

        return $default;
    }

    /**
     * Returns current script name.
     *
     * @return string
     */
    public function getScriptName(): string
    {
        return $this->server->get('SCRIPT_NAME', $this->server->get('ORIG_SCRIPT_NAME', ''));
    }

    /**
     * Returns the path being requested relative to the executed script.
     *
     * @return string
     */
    public function getPath(): string
    {
        if (null === $this->path) {
            $this->path = $this->server()->get('REQUEST_URI');
        }

        return $this->path;
    }

    /**
     * Returns the path being requested with no query string.
     *
     * @return string
     */
    public function getPathOnly(): string
    {
        $requestUri = $this->getPath();

        // Determine if the request uri has any query parameters
        if (false !== $queryPosition = strpos($requestUri, '?')) {
            // If so get the substring of the uri from start until the query param position
            $requestUri = substr($requestUri, 0, $queryPosition);
        }

        return $requestUri;
    }

    /**
     * Gets the request's scheme.
     *
     * @return string
     */
    public function getScheme(): string
    {
        return $this->isSecure()
            ? 'https'
            : 'http';
    }

    /**
     * Returns the port on which the request is made.
     *
     * This method can read the client port from the "X-Forwarded-Port" header
     * when trusted proxies were set via "setTrustedProxies()".
     *
     * The "X-Forwarded-Port" header must contain the client port.
     *
     * If your reverse proxy uses a different header name than "X-Forwarded-Port",
     * configure it via "setTrustedHeaderName()" with the "client-port" key.
     *
     * @return int
     */
    public function getPort(): int
    {
        return (int) $this->server->get('SERVER_PORT');
    }

    /**
     * Returns the user.
     *
     * @return string
     */
    public function getUser(): string
    {
        return $this->headers->get('PHP_AUTH_USER');
    }

    /**
     * Returns the password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->headers->get('PHP_AUTH_PW');
    }

    /**
     * Gets the user info.
     *
     * @return string
     */
    public function getUserInfo(): string
    {
        $userinfo = $this->getUser();
        $pass = $this->getPassword();

        if ($pass) {
            $userinfo .= ':' . $pass;
        }

        return $userinfo;
    }

    /**
     * Returns the HTTP host being requested.
     *
     * The port name will be appended to the host if it's non-standard.
     *
     * @return string
     */
    public function getHttpHost(): string
    {
        $scheme = $this->getScheme();
        $port = $this->getPort();

        if (('http' === $scheme && $port === 80) || ('https' === $scheme && $port === 443)) {
            return $this->getHost();
        }

        return $this->getHost() . ':' . $port;
    }

    /**
     * Returns the requested URI (path and query string).
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        if (null === $this->requestUri) {
            $this->requestUri = $this->getPath() . $this->query;
        }

        return $this->requestUri;
    }

    /**
     * Gets the scheme and HTTP host.
     *
     * @return string
     */
    public function getSchemeAndHttpHost(): string
    {
        return $this->getScheme() . '://' . $this->getHttpHost();
    }

    /**
     * Checks whether the request is secure or not.
     *
     * This method can read the client protocol from the "X-Forwarded-Proto" header
     * when trusted proxies were set via "setTrustedProxies()".
     *
     * The "X-Forwarded-Proto" header must contain the protocol: "https" or "http".
     *
     * If your reverse proxy uses a different header name than "X-Forwarded-Proto"
     * ("SSL_HTTPS" for instance), configure it via "setTrustedHeaderName()" with
     * the "client-proto" key.
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        $https = $this->server->get('HTTPS');

        return $https && 'off' !== strtolower($https);
    }

    /**
     * Returns the host name.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->headers->get('Host');
    }

    /**
     * Sets the request method.
     *
     * @param string $method
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setMethod(string $method): RequestContract
    {
        $this->method = null;
        $this->server->set('REQUEST_METHOD', $method);

        return $this;
    }

    /**
     * Gets the request "intended" method.
     *
     * @return string The request method
     *
     * @see getRealMethod()
     */
    public function getMethod(): string
    {
        if (null === $this->method) {
            $this->method = strtoupper($this->server->get('REQUEST_METHOD', RequestMethod::GET));

            if (RequestMethod::POST === $this->method) {
                if ($method = $this->headers->get('X-HTTP-METHOD-OVERRIDE')) {
                    $this->method = strtoupper($method);
                }
                else if (self::$httpMethodParameterOverride) {
                    $this->method = strtoupper(
                        $this->request->get('_method', $this->query->get('_method', RequestMethod::POST))
                    );
                }
            }
        }

        return $this->method;
    }

    /**
     * Gets the "real" request method.
     *
     * @return string The request method
     *
     * @see getMethod()
     */
    public function getRealMethod(): string
    {
        return strtoupper($this->server->get('REQUEST_METHOD', RequestMethod::GET));
    }

    /**
     * Gets the mime type associated with the format.
     *
     * @param string $format The format
     *
     * @return string
     */
    public function getMimeType(string $format): string
    {
        return isset(static::$formats[$format])
            ? static::$formats[$format][0]
            : null;
    }

    /**
     * Gets the mime types associated with the format.
     *
     * @param string $format The format
     *
     * @return array
     */
    public static function getMimeTypes(string $format): array
    {
        return static::$formats[$format] ?? [];
    }

    /**
     * Gets the format associated with the mime type.
     *
     * @param string $mimeType The associated mime type
     *
     * @return string
     */
    public function getFormat(string $mimeType): string
    {
        $canonicalMimeType = null;

        if (false !== $pos = strpos($mimeType, ';')) {
            $canonicalMimeType = substr($mimeType, 0, $pos);
        }

        foreach (static::FORMATS as $format => $mimeTypes) {
            if (in_array($mimeType, $mimeTypes, true)) {
                return $format;
            }

            if (null !== $canonicalMimeType && in_array($canonicalMimeType, $mimeTypes, true)) {
                return $format;
            }
        }

        return 'html';
    }

    /**
     * Gets the request format.
     *
     * @param string $default The default format
     *
     * @return string
     */
    public function getRequestFormat(string $default = 'html'): string
    {
        if (null === $this->format) {
            $this->format = $this->attributes->get('_format', $default);
        }

        return $this->format;
    }

    /**
     * Sets the request format.
     *
     * @param string $format The request format
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setRequestFormat(string $format): RequestContract
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Gets the format associated with the request.
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->getFormat($this->headers->get('Content-Type'));
    }

    /**
     * Get the locale.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale ?? $this->defaultLocale;
    }

    /**
     * Checks if the request method is of specified type.
     *
     * @param string $method Uppercase request method (GET, POST etc)
     *
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return $this->getMethod() === strtoupper($method);
    }

    /**
     * Gets the Etags.
     *
     * @return array
     */
    public function getETags(): array
    {
        return preg_split('/\s*,\s*/', $this->headers->get('If-None-Match'), null, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @return bool
     */
    public function isNoCache(): bool
    {
        return 'no-cache' === $this->headers->get('Pragma');
    }

    /**
     * Is this an AJAX request?
     *
     * @return bool
     */
    public function isXmlHttpRequest(): bool
    {
        return 'XMLHttpRequest' === $this->headers->get('X-Requested-With');
    }
}
