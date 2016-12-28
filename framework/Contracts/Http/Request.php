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

/**
 * Interface Request
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Request
{
    const HEADER_FORWARDED = 'forwarded';
    const HEADER_CLIENT_IP = 'client_ip';
    const HEADER_CLIENT_HOST = 'client_host';
    const HEADER_CLIENT_PROTO = 'client_proto';
    const HEADER_CLIENT_PORT = 'client_port';

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
     * Creates a new request with values from PHP's super globals.
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public static function createFromGlobals(): Request;

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
    public function setQuery(array $query = []): Request;

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
    public function setRequest(array $request = []): Request;

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
    public function setAttributes(array $attributes = []): Request;

    /**
     * Return the COOKIES Collection.
     *
     * @return \Valkyrja\Contracts\Http\Cookies
     */
    public function cookies(): Cookies;

    /**
     * Set the COOKIES parameters.
     *
     * @param array $cookies
     *
     * @return \Valkyrja\Contracts\Http\Request
     */
    public function setCookies(array $cookies = []): Request;

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
    public function setFiles(array $files = []): Request;

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
    public function setServer(array $server = []): Request;

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
    public function setHeaders(array $headers = []): Request;

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
    public function setContent(string $content = null): Request;

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
    public function setLanguages(array $languages = []): Request;

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
    public function setCharsets(array $charsets = []): Request;

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
    public function setEncodings(array $encodings = []): Request;

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
    public function setAcceptableContentTypes(array $acceptableContentTypes = []): Request;
}
