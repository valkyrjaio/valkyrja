<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Http;

use Valkyrja\Contracts\Support\Collection;
use Valkyrja\Http\RequestMethod;

/**
 * Interface Request
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Request
{
    const HEADER_FORWARDED    = 'forwarded';
    const HEADER_CLIENT_IP    = 'client_ip';
    const HEADER_CLIENT_HOST  = 'client_host';
    const HEADER_CLIENT_PROTO = 'client_proto';
    const HEADER_CLIENT_PORT  = 'client_port';

    const FORMATS = [
        'html' => ['text/html', 'application/xhtml+xml'],
        'txt'  => ['text/plain'],
        'js'   => ['application/javascript', 'application/x-javascript', 'text/javascript'],
        'css'  => ['text/css'],
        'json' => ['application/json', 'application/x-json'],
        'xml'  => ['text/xml', 'application/xml', 'application/x-xml'],
        'rdf'  => ['application/rdf+xml'],
        'atom' => ['application/atom+xml'],
        'rss'  => ['application/rss+xml'],
        'form' => ['application/x-www-form-urlencoded'],
    ];

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
    );

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
    ): self;

    /**
     * Creates a new request with values from PHP's super globals.
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public static function createFromGlobals(): self;

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
    ): self;

    /**
     * Clones the current request.
     *
     * Note that the session is not cloned as duplicated requests
     * are most of the time sub-requests of the main one.
     */
    public function __clone();

    /**
     * Returns the request as a string.
     *
     * @return string The request
     */
    public function __toString(): string;

    /**
     * Return the GET Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function query(): Collection;

    /**
     * Set the GET parameters.
     *
     * @param array $query
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setQuery(array $query = []): self;

    /**
     * Return the POST Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function request(): Collection;

    /**
     * Set the POST parameters.
     *
     * @param array $request
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setRequest(array $request = []): self;

    /**
     * Return the attributes Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function attributes(): Collection;

    /**
     * Set the attributes.
     *
     * @param array $attributes
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setAttributes(array $attributes = []): self;

    /**
     * Return the COOKIES Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function cookies(): Collection;

    /**
     * Set the COOKIES parameters.
     *
     * @param array $cookies
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setCookies(array $cookies = []): self;

    /**
     * Return the FILES Collection.
     *
     * @return \Valkyrja\Contracts\Http\Files
     */
    public function files(): Files;

    /**
     * Set the FILES parameters.
     *
     * @param array $files
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setFiles(array $files = []): self;

    /**
     * Return the SERVER Collection.
     *
     * @return \Valkyrja\Contracts\Http\Server
     */
    public function server(): Server;

    /**
     * Set the SERVER parameters.
     *
     * @param array $server
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setServer(array $server = []): self;

    /**
     * Return the headers Collection.
     *
     * @return \Valkyrja\Contracts\Http\Headers
     */
    public function headers(): Headers;

    /**
     * Set the headers parameters.
     *
     * @param array $headers
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setHeaders(array $headers = []): self;

    /**
     * Get the content.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Set the content.
     *
     * @param string $content
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setContent(string $content = null): self;

    /**
     * Get the languages.
     *
     * @return array
     */
    public function getLanguages(): array;

    /**
     * Set the languages.
     *
     * @param array $languages
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setLanguages(array $languages = []): self;

    /**
     * Get the charsets.
     *
     * @return array
     */
    public function getCharsets(): array;

    /**
     * Set the charsets.
     *
     * @param array $charsets
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setCharsets(array $charsets = []): self;

    /**
     * Get the encodings.
     *
     * @return array
     */
    public function getEncodings(): array;

    /**
     * Set the encodings.
     *
     * @param array $encodings
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setEncodings(array $encodings = []): self;

    /**
     * Get the acceptable content types.
     *
     * @return array
     */
    public function getAcceptableContentTypes(): array;

    /**
     * Set the acceptable content types.
     *
     * @param array $acceptableContentTypes
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setAcceptableContentTypes(array $acceptableContentTypes = []): self;

    /**
     * Gets a "parameter" value from any bag.
     *
     * @param string $key     the key
     * @param mixed  $default the default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get(string $key, $default = null); // : mixed;

    /**
     * Returns current script name.
     *
     * @return string
     */
    public function getScriptName(): string;

    /**
     * Returns the path being requested relative to the executed script.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Returns the path being requested with no query string.
     *
     * @return string
     */
    public function getPathClean(): string;

    /**
     * Gets the request's scheme.
     *
     * @return string
     */
    public function getScheme(): string;

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
     * @return string
     */
    public function getPort(): string;

    /**
     * Returns the user.
     *
     * @return string
     */
    public function getUser(): string;

    /**
     * Returns the password.
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Gets the user info.
     *
     * @return string
     */
    public function getUserInfo(): string;

    /**
     * Returns the HTTP host being requested.
     *
     * The port name will be appended to the host if it's non-standard.
     *
     * @return string
     */
    public function getHttpHost(): string;

    /**
     * Returns the requested URI (path and query string).
     *
     * @return string
     */
    public function getRequestUri(): string;

    /**
     * Gets the scheme and HTTP host.
     *
     * @return string
     */
    public function getSchemeAndHttpHost(): string;

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
    public function isSecure(): bool;

    /**
     * Returns the host name.
     *
     * @return string
     */
    public function getHost();

    /**
     * Sets the request method.
     *
     * @param string $method
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setMethod(string $method): self;

    /**
     * Gets the request "intended" method.
     *
     * @return string The request method
     *
     * @see getRealMethod()
     */
    public function getMethod(): string;

    /**
     * Gets the "real" request method.
     *
     * @return string The request method
     *
     * @see getMethod()
     */
    public function getRealMethod(): string;

    /**
     * Gets the mime type associated with the format.
     *
     * @param string $format The format
     *
     * @return string
     */
    public function getMimeType(string $format): string;

    /**
     * Gets the mime types associated with the format.
     *
     * @param string $format The format
     *
     * @return array
     */
    public static function getMimeTypes(string $format): array;

    /**
     * Gets the format associated with the mime type.
     *
     * @param string $mimeType The associated mime type
     *
     * @return string
     */
    public function getFormat(string $mimeType): string;

    /**
     * Gets the request format.
     *
     * @param string $default The default format
     *
     * @return string
     */
    public function getRequestFormat(string $default = 'html'): string;

    /**
     * Sets the request format.
     *
     * @param string $format The request format
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setRequestFormat(string $format): self;

    /**
     * Gets the format associated with the request.
     *
     * @return string
     */
    public function getContentType(): string;

    /**
     * Get the locale.
     *
     * @return string
     */
    public function getLocale(): string;

    /**
     * Checks if the request method is of specified type.
     *
     * @param string $method Uppercase request method (GET, POST etc)
     *
     * @return bool
     */
    public function isMethod(string $method): bool;

    /**
     * Gets the Etags.
     *
     * @return array
     */
    public function getETags(): array;

    /**
     * @return bool
     */
    public function isNoCache(): bool;

    /**
     * Is this an AJAX request?
     *
     * @return bool
     */
    public function isXmlHttpRequest(): bool;
}
