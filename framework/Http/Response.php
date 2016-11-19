<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Response.php
 */

namespace Valkyrja\Http;

use DateTime;
use Valkyrja\Contracts\Http\Response as ResponseContract;

/**
 * Class Response
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class Response implements ResponseContract
{
    /**
     * Response headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Cache control.
     *
     * @var array
     */
    protected $cacheControl = [];

    /**
     * Response cookies.
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * Response content.
     *
     * @var string
     */
    protected $content;

    /**
     * Response protocol version.
     *
     * @var string
     */
    protected $version;

    /**
     * Response status code.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Response status text.
     *
     * @var string
     */
    protected $statusText;

    /**
     * Response charset.
     *
     * @var string
     */
    protected $charset;

    /**
     * Response constructor.
     *
     * @param mixed $content [optional] The response content, see setContent()
     * @param int   $status  [optional] The response status code
     * @param array $headers [optional] An array of response headers
     */
    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
    }

    /**
     * Create a new response.
     *
     * @param mixed $content [optional] The response content, see setContent()
     * @param int   $status  [optional] The response status code
     * @param array $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public static function create($content = '', $status = 200, $headers = []) : ResponseContract
    {
        return new static($content, $status, $headers);
    }

    /**
     * Set the content for the response.
     *
     * @param string $content The response content to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setContent($content) : ResponseContract
    {
        if (null !== $content && !is_string($content) && !is_numeric($content)
            && !is_callable(
                [
                    $content,
                    '__toString',
                ]
            )
        ) {
            throw new \UnexpectedValueException(
                sprintf(
                    'The Response content must be a string or object implementing __toString(), "%s" given.',
                    gettype($content)
                )
            );
        }

        $this->content = (string) $content;

        return $this;
    }

    /**
     * Get the content for the response.
     *
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version [optional] The protocol version to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setProtocolVersion($version = '1.0') : ResponseContract
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Gets the HTTP protocol version.
     *
     * @return string
     */
    public function getProtocolVersion() : string
    {
        return $this->version;
    }

    /**
     * Sets the response status code.
     *
     * @param int   $code HTTP status code
     * @param mixed $text [optional] HTTP status text
     *
     * If the status text is null it will be automatically populated for the known
     * status codes and left empty otherwise.
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \InvalidArgumentException When the HTTP status code is not valid
     */
    public function setStatusCode($code, $text = null) : ResponseContract
    {
        $this->statusCode = $code = (int) $code;

        if ($this->isInvalid()) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $code));
        }

        if (null === $text) {
            $statusTexts = static::STATUS_TEXTS;

            $this->statusText = isset($statusTexts[$code])
                ? $statusTexts[$code]
                : 'unknown status';

            return $this;
        }

        if (false === $text) {
            $this->statusText = '';

            return $this;
        }

        $this->statusText = $text;

        return $this;
    }

    /**
     * Retrieves the status code for the current web response.
     *
     * @return int Status code
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * Sets the response charset.
     *
     * @param string $charset Character set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setCharset($charset) : ResponseContract
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Retrieves the response charset.
     *
     * @return string Character set
     */
    public function getCharset() : string
    {
        return $this->charset;
    }

    /**
     * Set response headers.
     *
     * @param array $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setHeaders(array $headers = [])  : ResponseContract
    {
        $this->headers = $headers;

        if (!$this->hasHeader('Cache-Control')) {
            $this->setHeader('Cache-Control', '');
        }

        return $this;
    }

    /**
     * Get all response headers.
     *
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * Set a response header.
     *
     * @param string $header The header to set
     * @param string $value  The value to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setHeader($header, $value) : ResponseContract
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Get a response header.
     *
     * @param string $header The header to get
     *
     * @return string
     */
    public function getHeader($header) : string
    {
        return $this->hasHeader($header)
            ? $this->headers[$header]
            : null;
    }

    /**
     * Check if a response header exists.
     *
     * @param string $header The header to check exists
     *
     * @return bool
     */
    public function hasHeader($header) : bool
    {
        return isset($this->headers[$header]);
    }

    /**
     * Remove a response header.
     *
     * @param string $header The header to remove
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function removeHeader($header) : ResponseContract
    {
        if (!$this->hasHeader($header)) {
            return $this;
        }

        unset($this->headers[$header]);

        return $this;
    }

    /**
     * Returns the Date header as a DateTime instance.
     *
     * @return \DateTime A \DateTime instance
     *
     * @throws \RuntimeException When the header is not parseable
     */
    public function getDateHeader() : DateTime
    {
        if (!$this->hasHeader('Date')) {
            $this->setDateHeader(DateTime::createFromFormat('U', time()));
        }

        return $this->getHeader('Date');
    }

    /**
     * Sets the Date header.
     *
     * @param \DateTime $date A \DateTime instance
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setDateHeader(DateTime $date) : ResponseContract
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->setHeader('Date', $date->format('D, d M Y H:i:s') . ' GMT');

        return $this;
    }

    /**
     * Returns an array with all cookies.
     *
     * @param bool $asString [optional] Get the cookies as a string?
     *
     * @return array
     */
    public function getCookies($asString = true) : array
    {
        if (!$asString) {
            return $this->cookies;
        }

        $flattenedCookies = [];

        foreach ($this->cookies as $path) {
            foreach ($path as $cookies) {
                foreach ($cookies as $cookie) {
                    $flattenedCookies[] = $cookie;
                }
            }
        }

        return $flattenedCookies;
    }

    /**
     * Set a response cookie.
     *
     * @param string $name     Cookie name
     * @param null   $value    Cookie value
     * @param int    $expire   Cookie expires time
     * @param string $path     Cookie path
     * @param null   $domain   Cookie domain
     * @param bool   $secure   Cookie http(s)
     * @param bool   $httpOnly Cookie http only?
     * @param bool   $raw      Cookie raw
     * @param null   $sameSite Cookie same site?
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setCookie(
        $name,
        $value = null,
        $expire = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = true,
        $raw = false,
        $sameSite = null
    ) : ResponseContract
    {
        $this->cookies[$domain][$path][$name] = [
            'name'     => (string) $name,
            'value'    => (string) $value,
            'expire'   => (string) $expire,
            'path'     => (empty($path)
                ? '/'
                : (string) $path),
            'domain'   => (string) $domain,
            'secure'   => (bool) $secure,
            'httpOnly' => (bool) $httpOnly,
            'raw'      => (bool) $raw,
            'sameSite' => (in_array(
                $sameSite,
                [
                    'lax',
                    'strict',
                    null,
                ]
            )
                ? $sameSite
                : null),
        ];

        return $this;
    }

    /**
     * Removes a cookie from the array, but does not unset it in the browser.
     *
     * @param string $name   Cookie name
     * @param string $path   [optional] Cookie path
     * @param string $domain [optional] Cookie domain
     *
     * @return void
     */
    public function removeCookie($name, $path = '/', $domain = null) : void
    {
        if (null === $path) {
            $path = '/';
        }

        unset($this->cookies[$domain][$path][$name]);

        if (empty($this->cookies[$domain][$path])) {
            unset($this->cookies[$domain][$path]);

            if (empty($this->cookies[$domain])) {
                unset($this->cookies[$domain]);
            }
        }
    }

    /**
     * Set a response cache control.
     *
     * @param string $name  Cache control name
     * @param string $value [optional] Cache control value
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function addCacheControl($name, $value = null) : ResponseContract
    {
        $this->cacheControl[$name] = $value;

        return $this;
    }

    /**
     * Get a response cache control.
     *
     * @param string $name Cache control name
     *
     * @return string
     */
    public function getCacheControl($name) : string
    {
        return $this->hasCacheControl($name)
            ? $this->cacheControl[$name]
            : false;
    }

    /**
     * Check if a response cache control exists.
     *
     * @param string $name Cache control name
     *
     * @return bool
     */
    public function hasCacheControl($name) : bool
    {
        return isset($this->cacheControl[$name]);
    }

    /**
     * Remove a response cache control.
     *
     * @param string $name Cache control name
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function removeCacheControl($name) : ResponseContract
    {
        if (!$this->hasCacheControl($name)) {
            return $this;
        }

        unset($this->cacheControl[$name]);

        return $this;
    }

    /**
     * Returns true if the response is worth caching under any circumstance.
     *
     * Responses marked "private" with an explicit Cache-Control directive are
     * considered uncacheable.
     *
     * Responses with neither a freshness lifetime (Expires, max-age) nor cache
     * validator (Last-Modified, ETag) are considered uncacheable.
     *
     * @return bool true if the response is worth caching, false otherwise
     */
    public function isCacheable() : bool
    {
        if (!in_array(
            $this->statusCode,
            [
                200,
                203,
                300,
                301,
                302,
                404,
                410,
            ]
        )
        ) {
            return false;
        }

        if ($this->hasCacheControl('no-store') || $this->hasCacheControl('private')) {
            return false;
        }

        return $this->isValidateable() || $this->isFresh();
    }

    /**
     * Returns true if the response is "fresh".
     *
     * Fresh responses may be served from cache without any interaction with the
     * origin. A response is considered fresh when it includes a Cache-Control/max-age
     * indicator or Expires header and the calculated age is less than the freshness lifetime.
     *
     * @return bool true if the response is fresh, false otherwise
     */
    public function isFresh() : bool
    {
        return $this->getTtl() > 0;
    }

    /**
     * Returns true if the response includes headers that can be used to validate
     * the response with the origin server using a conditional GET request.
     *
     * @return bool true if the response is validateable, false otherwise
     */
    public function isValidateable() : bool
    {
        return $this->hasHeader('Last-Modified') || $this->hasHeader('ETag');
    }

    /**
     * Marks the response as "private".
     *
     * It makes the response ineligible for serving other clients.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setPrivate() : ResponseContract
    {
        $this->removeCacheControl('public');
        $this->addCacheControl('private');

        return $this;
    }

    /**
     * Marks the response as "public".
     *
     * It makes the response eligible for serving other clients.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setPublic() : ResponseContract
    {
        $this->addCacheControl('public');
        $this->removeCacheControl('private');

        return $this;
    }

    /**
     * Returns the age of the response.
     *
     * @return int The age of the response in seconds
     */
    public function getAge() : int
    {
        if (null !== $age = $this->getHeader('Age')) {
            return (int) $age;
        }

        return max(
            time() - date('U', strtotime($this->getDateHeader())),
            0
        );
    }

    /**
     * Marks the response stale by setting the Age header to be equal to the maximum age of the response.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function expire() : ResponseContract
    {
        if ($this->isFresh()) {
            $this->setHeader('Age', $this->getMaxAge());
        }

        return $this;
    }

    /**
     * Todo
     *
     * Returns the value of the Expires header as a DateTime instance.
     *
     * @return \DateTime A DateTime instance or null if the header does not exist
     */
    public function getExpires() : DateTime
    {
        try {
            return $this->getHeader('Expires');
        }
        catch (\RuntimeException $e) {
            // according to RFC 2616 invalid date formats (e.g. "0" and "-1") must be treated as in the past
            return DateTime::createFromFormat(DATE_RFC2822, 'Sat, 01 Jan 00 00:00:00 +0000');
        }
    }

    /**
     * Sets the Expires HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param \DateTime|null $date [optional] A \DateTime instance or null to remove the header
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setExpires(DateTime $date = null) : ResponseContract
    {
        if (null === $date) {
            $this->removeHeader('Expires');
        }
        else {
            $date = clone $date;
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->setHeader('Expires', $date->format('D, d M Y H:i:s') . ' GMT');
        }

        return $this;
    }

    /**
     * Returns the number of seconds after the time specified in the response's Date
     * header when the response should no longer be considered fresh.
     *
     * First, it checks for a s-maxage directive, then a max-age directive, and then it falls
     * back on an expires header. It returns null when no maximum age can be established.
     *
     * @return int Number of seconds
     */
    public function getMaxAge() : int
    {
        if ($this->hasCacheControl('s-maxage')) {
            return (int) $this->getCacheControl('s-maxage');
        }

        if ($this->hasCacheControl('max-age')) {
            return (int) $this->getCacheControl('max-age');
        }

        if (null !== $this->getExpires()) {
            return date('U', strtotime($this->getExpires())) - date('U', strtotime($this->getDateHeader()));
        }

        return 0;
    }

    /**
     * Sets the number of seconds after which the response should no longer be considered fresh.
     *
     * This methods sets the Cache-Control max-age directive.
     *
     * @param int $value Number of seconds
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setMaxAge($value) : ResponseContract
    {
        $this->addCacheControl('max-age', $value);

        return $this;
    }

    /**
     * Sets the number of seconds after which the response should no longer be considered fresh by shared caches.
     *
     * This methods sets the Cache-Control s-maxage directive.
     *
     * @param int $value Number of seconds
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setSharedMaxAge($value) : ResponseContract
    {
        $this->setPublic();
        $this->addCacheControl('s-maxage', $value);

        return $this;
    }

    /**
     * Returns the response's time-to-live in seconds.
     *
     * It returns null when no freshness information is present in the response.
     *
     * When the responses TTL is <= 0, the response may not be served from cache without first
     * revalidating with the origin.
     *
     * @return int The TTL in seconds
     */
    public function getTtl() : int
    {
        if (null !== $maxAge = $this->getMaxAge()) {
            return $maxAge - $this->getAge();
        }

        return 0;
    }

    /**
     * Sets the response's time-to-live for shared caches.
     *
     * This method adjusts the Cache-Control/s-maxage directive.
     *
     * @param int $seconds Number of seconds
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setTtl($seconds) : ResponseContract
    {
        $this->setSharedMaxAge($this->getAge() + $seconds);

        return $this;
    }

    /**
     * Sets the response's time-to-live for private/client caches.
     *
     * This method adjusts the Cache-Control/max-age directive.
     *
     * @param int $seconds Number of seconds
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setClientTtl($seconds) : ResponseContract
    {
        $this->setMaxAge($this->getAge() + $seconds);

        return $this;
    }

    /**
     * Returns the Last-Modified HTTP header as a DateTime instance.
     *
     * @return string A date string
     *
     * @throws \RuntimeException When the HTTP header is not parseable
     */
    public function getLastModified() : string
    {
        return $this->getHeader('Last-Modified');
    }

    /**
     * Sets the Last-Modified HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param \DateTime $date [optional] A \DateTime instance or null to remove the header
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setLastModified(DateTime $date = null) : ResponseContract
    {
        if (null === $date) {
            $this->removeHeader('Last-Modified');
        }
        else {
            $date = clone $date;
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->setHeader('Last-Modified', $date->format('D, d M Y H:i:s') . ' GMT');
        }

        return $this;
    }

    /**
     * Returns the literal value of the ETag HTTP header.
     *
     * @return string The ETag HTTP header or null if it does not exist
     */
    public function getEtag() : string
    {
        return $this->getHeader('ETag');
    }

    /**
     * Sets the ETag value.
     *
     * @param string $etag [optional] The ETag unique identifier or null to remove the header
     * @param bool   $weak [optional] Whether you want a weak ETag or not
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setEtag($etag = null, $weak = false) : ResponseContract
    {
        if (null === $etag) {
            $this->removeHeader('Etag');
        }
        else {
            if (0 !== strpos($etag, '"')) {
                $etag = '"' . $etag . '"';
            }
            $this->setHeader(
                'ETag',
                (true === $weak
                    ? 'W/'
                    : '') . $etag
            );
        }

        return $this;
    }

    /**
     * Sets the response's cache headers (validation and/or expiration).
     *
     * Available options are: etag, last_modified, max_age, s_maxage, private, and public.
     *
     * @param array $options An array of cache options
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setCache(array $options) : ResponseContract
    {
        if (isset($options['etag'])) {
            $this->setEtag($options['etag']);
        }

        if (isset($options['last_modified'])) {
            $this->setLastModified($options['last_modified']);
        }

        if (isset($options['max_age'])) {
            $this->setMaxAge($options['max_age']);
        }

        if (isset($options['s_maxage'])) {
            $this->setSharedMaxAge($options['s_maxage']);
        }

        if (isset($options['public'])) {
            if ($options['public']) {
                $this->setPublic();
            }
            else {
                $this->setPrivate();
            }
        }

        if (isset($options['private'])) {
            if ($options['private']) {
                $this->setPrivate();
            }
            else {
                $this->setPublic();
            }
        }

        return $this;
    }

    /**
     * Modifies the response so that it conforms to the rules defined for a 304 status code.
     *
     * This sets the status, removes the body, and discards any headers
     * that MUST NOT be included in 304 responses.
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3.5
     */
    public function setNotModified() : ResponseContract
    {
        $this->setStatusCode(304);
        $this->setContent(null);
        $this->removeHeader('Allow')
             ->removeHeader('Content-Encoding')
             ->removeHeader('Content-Language')
             ->removeHeader('Content-Length')
             ->removeHeader('Content-MD5')
             ->removeHeader('Content-Type')
             ->removeHeader('Last-Modified');

        return $this;
    }

    /**
     * Is response invalid?
     *
     * @return bool
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public function isInvalid() : bool
    {
        return $this->statusCode < 100 || $this->statusCode >= 600;
    }

    /**
     * Is response informative?
     *
     * @return bool
     */
    public function isInformational() : bool
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }

    /**
     * Is response successful?
     *
     * @return bool
     */
    public function isSuccessful() : bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * Is the response a redirect?
     *
     * @return bool
     */
    public function isRedirection() : bool
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    /**
     * Is there a client error?
     *
     * @return bool
     */
    public function isClientError() : bool
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * Was there a server side error?
     *
     * @return bool
     */
    public function isServerError() : bool
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    /**
     * Is the response OK?
     *
     * @return bool
     */
    public function isOk() : bool
    {
        return 200 === $this->statusCode;
    }

    /**
     * Is the response forbidden?
     *
     * @return bool
     */
    public function isForbidden() : bool
    {
        return 403 === $this->statusCode;
    }

    /**
     * Is the response a not found error?
     *
     * @return bool
     */
    public function isNotFound() : bool
    {
        return 404 === $this->statusCode;
    }

    /**
     * Is the response a redirect of some form?
     *
     * @param string $location [optional] Redirect location
     *
     * @return bool
     */
    public function isRedirect($location = null) : bool
    {
        return in_array(
                   $this->statusCode,
                   [
                       201,
                       301,
                       302,
                       303,
                       307,
                       308,
                   ]
               )
               && (null === $location
            ?: $location == $this->getHeader('Location'));
    }

    /**
     * Is the response empty?
     *
     * @return bool
     */
    public function isEmpty() : bool
    {
        return in_array(
            $this->statusCode,
            [
                204,
                304,
            ]
        );
    }

    /**
     * Sends HTTP headers.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function sendHeaders() : ResponseContract
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return $this;
        }

        if (!$this->hasHeader('Date')) {
            $this->setDateHeader(\DateTime::createFromFormat('U', time()));
        }

        foreach ($this->getHeaders() as $name => $value) {
            header($name . ': ' . $value, false, $this->statusCode);
        }

        // status
        header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText), true, $this->statusCode);

        // cookies
        foreach ($this->getCookies() as $cookie) {
            if ($cookie['raw']) {
                setrawcookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['expire'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httpOnly']
                );
            }
            else {
                setcookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['expire'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httpOnly']
                );
            }
        }

        return $this;
    }

    /**
     * Sends content for the current web response.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function sendContent() : ResponseContract
    {
        echo $this->content;

        return $this;
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function send() : ResponseContract
    {
        $this->sendHeaders()
             ->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        elseif ('cli' !== PHP_SAPI) {
            static::closeOutputBuffers(0, true);
        }

        return $this;
    }

    /**
     * Cleans or flushes output buffers up to target level.
     *
     * Resulting level can be greater than target level if a non-removable buffer has been encountered.
     *
     * @param int  $targetLevel The target output buffering level
     * @param bool $flush       Whether to flush or clean the buffers
     *
     * @return void
     */
    public static function closeOutputBuffers($targetLevel, $flush) : void
    {
        $status = ob_get_status(true);
        $level = count($status);
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE')
            ? PHP_OUTPUT_HANDLER_REMOVABLE | ($flush
                ? PHP_OUTPUT_HANDLER_FLUSHABLE
                : PHP_OUTPUT_HANDLER_CLEANABLE)
            : -1;

        while ($level-- > $targetLevel && ($s = $status[$level])
               && (!isset($s['del'])
                ? !isset($s['flags']) || $flags === ($s['flags'] & $flags)
                : $s['del'])) {
            if ($flush) {
                ob_end_flush();
            }
            else {
                ob_end_clean();
            }
        }
    }

    /**
     * Returns the Response as an HTTP string.
     *
     * The string representation of the Response is the same as the
     * one that will be sent to the client only if the prepare() method
     * has been called before.
     *
     * @return string The Response as an HTTP string
     *
     * @see prepare()
     */
    public function __toString() : string
    {
        return sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText)
               . "\r\n"
               . $this->headers
               . "\r\n"
               . $this->getContent();
    }
}
