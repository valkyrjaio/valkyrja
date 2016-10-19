<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Request.php
 */

namespace Valkyrja\Http;

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
    /**
     * @var string[]
     */
    protected static $trustedProxies = [];

    /**
     * @var string[]
     */
    protected static $trustedHostPatterns = [];

    /**
     * @var string[]
     */
    protected static $trustedHosts = [];

    /**
     * Names for headers that can be trusted when
     * using trusted proxies.
     *
     * The FORWARDED header is the standard as of rfc7239.
     *
     * The other headers are non-standard, but widely used
     * by popular reverse proxies (like Apache mod_proxy or Amazon EC2).
     */
    protected static $trustedHeaders = [
        self::HEADER_FORWARDED    => 'FORWARDED',
        self::HEADER_CLIENT_IP    => 'X_FORWARDED_FOR',
        self::HEADER_CLIENT_HOST  => 'X_FORWARDED_HOST',
        self::HEADER_CLIENT_PROTO => 'X_FORWARDED_PROTO',
        self::HEADER_CLIENT_PORT  => 'X_FORWARDED_PORT',
    ];

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
     * @var \Valkyrja\Contracts\Support\Collection
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
     * @var \Valkyrja\Contracts\Http\Cookies
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
    protected $pathInfo;

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
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

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
    ) {
        $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Sets the parameters for this request.
     *
     * This method also re-initializes all properties.
     *
     * @param array           $query      The GET parameters
     * @param array           $request    The POST parameters
     * @param array           $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array           $cookies    The COOKIE parameters
     * @param array           $files      The FILES parameters
     * @param array           $server     The SERVER parameters
     * @param string|resource $content    The raw body data
     */
    public function initialize(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        $this->setQuery($query)
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

        $this->pathInfo = null;
        $this->requestUri = null;
        $this->baseUrl = null;
        $this->basePath = null;
        $this->method = null;
        $this->format = null;
    }

    /**
     * Creates a new request with values from PHP's super globals.
     *
     * @return Request A new request
     */
    public static function createFromGlobals()
    {
        // With the php's bug #66606, the php's built-in web server
        // stores the Content-Type and Content-Length header values in
        // HTTP_CONTENT_TYPE and HTTP_CONTENT_LENGTH fields.
        $server = $_SERVER;

        if ('cli-server' === PHP_SAPI) {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
                $server['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
                $server['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }

        $request = new static($_GET, $_POST, [], $_COOKIE, $_FILES, $server);

        if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && in_array(
                strtoupper($request->server->get('REQUEST_METHOD', 'GET')),
                [
                    'PUT',
                    'DELETE',
                    'PATCH',
                ]
            )
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new Collection($data);
        }

        return $request;
    }

    /**
     * Return the GET Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function query()
    {
        if (!$this->query) {
            $this->setQuery();
        }

        return $this->query;
    }

    /**
     * Set the GET parameters.
     *
     * @param array $query
     *
     * @return $this
     */
    public function setQuery(array $query = [])
    {
        $this->query = new Collection($query);

        return $this;
    }

    /**
     * Return the POST Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function request()
    {
        if (!$this->request) {
            $this->setRequest();
        }

        return $this->request;
    }

    /**
     * Set the POST parameters.
     *
     * @param array $request
     *
     * @return $this
     */
    public function setRequest(array $request = [])
    {
        $this->request = new Collection($request);

        return $this;
    }

    /**
     * Return the attributes Collection.
     *
     * @return \Valkyrja\Contracts\Support\Collection
     */
    public function attributes()
    {
        if (!$this->attributes) {
            $this->setAttributes();
        }

        return $this->attributes;
    }

    /**
     * Set the attributes.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes = [])
    {
        $this->attributes = new Collection($attributes);

        return $this;
    }

    /**
     * Return the COOKIES Collection.
     *
     * @return \Valkyrja\Contracts\Http\Cookies
     */
    public function cookies()
    {
        if (!$this->cookies) {
            $this->setCookies();
        }

        return $this->cookies;
    }

    /**
     * Set the COOKIES parameters.
     *
     * @param array $cookies
     *
     * @return $this
     */
    public function setCookies(array $cookies = [])
    {
        $this->cookies = new Cookies($cookies);

        return $this;
    }

    /**
     * Return the FILES Collection.
     *
     * @return \Valkyrja\Contracts\Http\Files
     */
    public function files()
    {
        if (!$this->files) {
            $this->setFiles();
        }

        return $this->files;
    }

    /**
     * Set the FILES parameters.
     *
     * @param array $files
     *
     * @return $this
     */
    public function setFiles(array $files = [])
    {
        $this->files = new Files($files);

        return $this;
    }

    /**
     * Return the SERVER Collection.
     *
     * @return \Valkyrja\Contracts\Http\Server
     */
    public function server()
    {
        if (!$this->server) {
            $this->setServer();
        }

        return $this->server;
    }

    /**
     * Set the SERVER parameters.
     *
     * @param array $server
     *
     * @return $this
     */
    public function setServer(array $server = [])
    {
        $this->server = new Server($server);

        return $this;
    }

    /**
     * Return the headers Collection.
     *
     * @return \Valkyrja\Contracts\Http\Headers
     */
    public function headers()
    {
        if (!$this->headers) {
            $this->setHeaders();
        }

        return $this->headers;
    }

    /**
     * Set the headers parameters.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers = [])
    {
        $headers = $headers
            ? $headers
            : $this->server()
                   ->getHeaders();

        $this->headers = new Headers($headers);

        return $this;
    }

    /**
     * Get the content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the languages.
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set the languages.
     *
     * @param array $languages
     *
     * @return $this
     */
    public function setLanguages(array $languages = [])
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get the charsets.
     *
     * @return array
     */
    public function getCharsets()
    {
        return $this->charsets;
    }

    /**
     * Set the charsets.
     *
     * @param array $charsets
     *
     * @return $this
     */
    public function setCharsets(array $charsets = [])
    {
        $this->charsets = $charsets;

        return $this;
    }

    /**
     * Get the encodings.
     *
     * @return array
     */
    public function getEncodings()
    {
        return $this->encodings;
    }

    /**
     * Set the encodings.
     *
     * @param array $encodings
     *
     * @return $this
     */
    public function setEncodings(array $encodings = [])
    {
        $this->encodings = $encodings;

        return $this;
    }

    /**
     * Get the acceptable content types.
     *
     * @return array
     */
    public function getAcceptableContentTypes()
    {
        return $this->acceptableContentTypes;
    }

    /**
     * Set the acceptable content types.
     *
     * @param array $acceptableContentTypes
     *
     * @return $this
     */
    public function setAcceptableContentTypes(array $acceptableContentTypes = [])
    {
        $this->acceptableContentTypes = $acceptableContentTypes;

        return $this;
    }

    /**
     * Gets a "parameter" value from any bag.
     *
     * This method is mainly useful for libraries that want to provide some flexibility. If you don't need the
     * flexibility in controllers, it is better to explicitly get request parameters from the appropriate
     * public property instead (attributes, query, request).
     *
     * Order of precedence: PATH (routing placeholders or custom attributes), GET, BODY
     *
     * @param string $key     the key
     * @param mixed  $default the default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get($key, $default = null)
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
}
