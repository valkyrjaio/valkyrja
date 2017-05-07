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

use DateTime;
use DateTimeZone;

use Valkyrja\Contracts\Http\Cookies as CookiesContract;
use Valkyrja\Contracts\Http\Headers as HeadersContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Http\Exceptions\InvalidStatusCodeException;

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
     * @var \Valkyrja\Contracts\Http\Headers
     */
    protected $headers;

    /**
     * Cache control.
     *
     * @var array
     */
    protected $cacheControl = [];

    /**
     * Response cookies.
     *
     * @var \Valkyrja\Contracts\Http\Cookies
     */
    protected $cookies;

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
     * The view to use.
     *
     * @var \Valkyrja\Contracts\View\View
     */
    protected $view;

    /**
     * Response constructor.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $content = '', int $status = ResponseCode::HTTP_OK, array $headers = [])
    {
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');

        $this->cookies = new Cookies();
    }

    /**
     * Create a new response.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \InvalidArgumentException
     */
    public static function create(
        string $content = '',
        int $status = ResponseCode::HTTP_OK,
        array $headers = []
    ): ResponseContract
    {
        return new static($content, $status, $headers);
    }

    /**
     * Sends HTTP headers.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function sendHeaders(): ResponseContract
    {
        // Headers have already been sent so there's nothing to do here
        if (headers_sent()) {
            return $this;
        }

        // If there is no date header
        if (! $this->headers->has('Date')) {
            // Set it with the current time
            $this->setDateHeader(DateTime::createFromFormat('U', time()));
        }

        // Iterate through all the headers
        foreach ($this->headers->all() as $name => $value) {
            // Set the headers
            header($name . ': ' . $value, false, $this->statusCode);
        }

        // Set the status of the response
        header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText), true, $this->statusCode);

        // Set the response cookies
        foreach ($this->cookies->all() as $cookie) {
            // If this is a raw cookie
            if ($cookie->isRaw()) {
                // Set the raw cookie
                setrawcookie(
                    $cookie->getName(),
                    $cookie->getValue(),
                    $cookie->getExpire(),
                    $cookie->getPath(),
                    $cookie->getDomain(),
                    $cookie->isSecure(),
                    $cookie->isHttpOnly()
                );
            } // Otherwise
            else {
                // Set the cookie normally
                setcookie(
                    $cookie->getName(),
                    $cookie->getValue(),
                    $cookie->getExpire(),
                    $cookie->getPath(),
                    $cookie->getDomain(),
                    $cookie->isSecure(),
                    $cookie->isHttpOnly()
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
    public function sendContent(): ResponseContract
    {
        echo $this->content;

        return $this;
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function send(): ResponseContract
    {
        // Send headers
        $this->sendHeaders()
            // And content
             ->sendContent();

        // If fastcgi is enabled
        if (function_exists('fastcgi_finish_request')) {
            // Use it to finish the request
            fastcgi_finish_request();
        } // Otherwise if this isn't a cli request
        elseif ('cli' !== PHP_SAPI) {
            // Use an internal method to finish the request
            static::closeOutputBuffers(0, true);
        }

        return $this;
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
    public function __toString(): string
    {
        $cookies = '';

        // Iterate through all the cookies
        foreach ($this->cookies->all() as $cookie) {
            // Set the cookie's string
            $cookies .= 'Set-Cookie: '
                . $cookie
                . "\r\n";
        }

        return sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText)
            . "\r\n"
            . $cookies
            . $this->headers
            . "\r\n"
            . $this->getContent();
    }

    /**
     * Set the content for the response.
     *
     * @param string $content The response content to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setContent(string $content): ResponseContract
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the content for the response.
     *
     * @return string
     */
    public function getContent(): string
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
    public function setProtocolVersion(string $version = '1.0'): ResponseContract
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Gets the HTTP protocol version.
     *
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->version;
    }

    /**
     * Sets the response status code.
     *
     * @param int    $code HTTP status code
     * @param string $text [optional] HTTP status text
     *
     * If the status text is null it will be automatically populated for the known
     * status codes and left empty otherwise.
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Http\Exceptions\InvalidStatusCodeException
     */
    public function setStatusCode(int $code, string $text = null): ResponseContract
    {
        $this->statusCode = $code;

        // Check if the status code is valid
        if ($this->isInvalid()) {
            throw new InvalidStatusCodeException(sprintf('The HTTP status code "%s" is not valid.', $code));
        }

        // If no text was supplied
        if (null === $text) {
            // Set the status text from the status texts array
            $this->statusText = ResponseCode::STATUS_TEXTS[$code] ?? 'unknown status';

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
    public function getStatusCode(): int
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
    public function setCharset(string $charset): ResponseContract
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Retrieves the response charset.
     *
     * @return string Character set
     */
    public function getCharset(): string
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
    public function setHeaders(array $headers = []): ResponseContract
    {
        // If the headers have no been set yet
        if (null === $this->headers) {
            // Set them to a new Headers collection
            $this->headers = new Headers();
        }

        // Set all the headers with the array provided
        $this->headers->setAll($headers);

        // If there is no cache control header
        if (! $this->headers->has('Cache-Control')) {
            // Set it to an empty string
            $this->headers->set('Cache-Control', '');
        }

        return $this;
    }

    /**
     * Get response headers collection.
     *
     * @return \Valkyrja\Contracts\Http\Headers
     */
    public function headers(): HeadersContract
    {
        return $this->headers;
    }

    /**
     * Returns the Date header as a DateTime instance.
     *
     * @return DateTime A DateTime instance
     *
     * @throws \RuntimeException When the header is not parseable
     */
    public function getDateHeader(): DateTime
    {
        if (! $this->headers->has('Date')) {
            $this->setDateHeader(DateTime::createFromFormat('U', time()));
        }

        return $this->headers->get('Date');
    }

    /**
     * Sets the Date header.
     *
     * @param DateTime $date A DateTime instance
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setDateHeader(DateTime $date): ResponseContract
    {
        $date->setTimezone(new DateTimeZone('UTC'));
        $this->headers->set('Date', $date->format('D, d M Y H:i:s') . ' GMT');

        return $this;
    }

    /**
     * Get response cookies collection.
     *
     * @return \Valkyrja\Contracts\Http\Cookies
     */
    public function cookies(): CookiesContract
    {
        return $this->cookies;
    }

    /**
     * Set a response cache control.
     *
     * @param string $name  Cache control name
     * @param string $value [optional] Cache control value
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function addCacheControl(string $name, string $value = null): ResponseContract
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
    public function getCacheControl(string $name): string
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
    public function hasCacheControl(string $name): bool
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
    public function removeCacheControl(string $name): ResponseContract
    {
        if (! $this->hasCacheControl($name)) {
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
     *
     * @throws \RuntimeException
     */
    public function isCacheable(): bool
    {
        if (! in_array(
            $this->statusCode,
            [
                ResponseCode::HTTP_OK,
                ResponseCode::HTTP_NON_AUTHORITATIVE_INFORMATION,
                ResponseCode::HTTP_MULTIPLE_CHOICES,
                ResponseCode::HTTP_MOVED_PERMANENTLY,
                ResponseCode::HTTP_FOUND,
                ResponseCode::HTTP_NOT_FOUND,
                ResponseCode::HTTP_GONE,
            ],
            true
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
     *
     * @throws \RuntimeException
     */
    public function isFresh(): bool
    {
        return $this->getTtl() > 0;
    }

    /**
     * Returns true if the response includes headers that can be used to validate
     * the response with the origin server using a conditional GET request.
     *
     * @return bool true if the response is validateable, false otherwise
     */
    public function isValidateable(): bool
    {
        return $this->headers->has('Last-Modified') || $this->headers->has('ETag');
    }

    /**
     * Marks the response as "private".
     *
     * It makes the response ineligible for serving other clients.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setPrivate(): ResponseContract
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
    public function setPublic(): ResponseContract
    {
        $this->addCacheControl('public');
        $this->removeCacheControl('private');

        return $this;
    }

    /**
     * Returns the age of the response.
     *
     * @return int The age of the response in seconds
     *
     * @throws \RuntimeException
     */
    public function getAge(): int
    {
        if (null !== $age = $this->headers->get('Age')) {
            return $age;
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
     *
     * @throws \RuntimeException
     */
    public function expire(): ResponseContract
    {
        if ($this->isFresh()) {
            $this->headers->set('Age', $this->getMaxAge());
        }

        return $this;
    }

    /**
     * Todo
     *
     * Returns the value of the Expires header as a DateTime instance.
     *
     * @return DateTime A DateTime instance or null if the header does not exist
     */
    public function getExpires(): DateTime
    {
        try {
            return $this->headers->get('Expires');
        } catch (\RuntimeException $e) {
            // according to RFC 2616 invalid date formats (e.g. "0" and "-1") must be treated as in the past
            return DateTime::createFromFormat(DATE_RFC2822, 'Sat, 01 Jan 00 00:00:00 +0000');
        }
    }

    /**
     * Sets the Expires HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param DateTime $date [optional] A DateTime instance or null to remove the header
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setExpires(DateTime $date = null): ResponseContract
    {
        if (null === $date) {
            $this->headers->remove('Expires');
        } else {
            $date = clone $date;
            $date->setTimezone(new DateTimeZone('UTC'));
            $this->headers->set('Expires', $date->format('D, d M Y H:i:s') . ' GMT');
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
     *
     * @throws \RuntimeException
     */
    public function getMaxAge(): int
    {
        if ($this->hasCacheControl('s-maxage')) {
            return $this->getCacheControl('s-maxage');
        }

        if ($this->hasCacheControl('max-age')) {
            return $this->getCacheControl('max-age');
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
    public function setMaxAge(int $value): ResponseContract
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
    public function setSharedMaxAge(int $value): ResponseContract
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
     *
     * @throws \RuntimeException
     */
    public function getTtl(): int
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
     *
     * @throws \RuntimeException
     */
    public function setTtl(int $seconds): ResponseContract
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
     *
     * @throws \RuntimeException
     */
    public function setClientTtl(int $seconds): ResponseContract
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
    public function getLastModified(): string
    {
        return $this->headers->get('Last-Modified');
    }

    /**
     * Sets the Last-Modified HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param DateTime $date [optional] A DateTime instance or null to remove the header
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setLastModified(DateTime $date = null): ResponseContract
    {
        if (null === $date) {
            $this->headers->remove('Last-Modified');
        } else {
            $date = clone $date;
            $date->setTimezone(new DateTimeZone('UTC'));
            $this->headers->set('Last-Modified', $date->format('D, d M Y H:i:s') . ' GMT');
        }

        return $this;
    }

    /**
     * Returns the literal value of the ETag HTTP header.
     *
     * @return string The ETag HTTP header or null if it does not exist
     */
    public function getEtag(): string
    {
        return $this->headers->get('ETag');
    }

    /**
     * Sets the ETag value.
     *
     * @param string $etag [optional] The ETag unique identifier or null to remove the header
     * @param bool   $weak [optional] Whether you want a weak ETag or not
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setEtag(string $etag = null, bool $weak = false): ResponseContract
    {
        if (null === $etag) {
            $this->headers->remove('Etag');
        } else {
            if (0 !== strpos($etag, '"')) {
                $etag = '"' . $etag . '"';
            }
            $this->headers->set(
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
    public function setCache(array $options): ResponseContract
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
            } else {
                $this->setPrivate();
            }
        }

        if (isset($options['private'])) {
            if ($options['private']) {
                $this->setPrivate();
            } else {
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
     * @throws \InvalidArgumentException
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3.5
     */
    public function setNotModified(): ResponseContract
    {
        $this->setStatusCode(ResponseCode::HTTP_NOT_MODIFIED);
        $this->setContent(null);
        $this->headers
            ->remove('Allow')
            ->remove('Content-Encoding')
            ->remove('Content-Language')
            ->remove('Content-Length')
            ->remove('Content-MD5')
            ->remove('Content-Type')
            ->remove('Last-Modified');

        return $this;
    }

    /**
     * Is response invalid?
     *
     * @return bool
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public function isInvalid(): bool
    {
        return $this->statusCode < ResponseCode::HTTP_CONTINUE
            || $this->statusCode >= ResponseCode::HTTP_NETWORK_AUTHENTICATION_REQUIRED;
    }

    /**
     * Is response informative?
     *
     * @return bool
     */
    public function isInformational(): bool
    {
        return $this->statusCode >= ResponseCode::HTTP_CONTINUE
            && $this->statusCode < ResponseCode::HTTP_OK;
    }

    /**
     * Is response successful?
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= ResponseCode::HTTP_OK
            && $this->statusCode < ResponseCode::HTTP_MULTIPLE_CHOICES;
    }

    /**
     * Is the response a redirect?
     *
     * @return bool
     */
    public function isRedirection(): bool
    {
        return $this->statusCode >= ResponseCode::HTTP_MULTIPLE_CHOICES
            && $this->statusCode < ResponseCode::HTTP_BAD_REQUEST;
    }

    /**
     * Is there a client error?
     *
     * @return bool
     */
    public function isClientError(): bool
    {
        return $this->statusCode >= ResponseCode::HTTP_BAD_REQUEST
            && $this->statusCode < ResponseCode::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Was there a server side error?
     *
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->statusCode >= ResponseCode::HTTP_INTERNAL_SERVER_ERROR
            && $this->statusCode < 600;
    }

    /**
     * Is the response OK?
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return ResponseCode::HTTP_OK === $this->statusCode;
    }

    /**
     * Is the response forbidden?
     *
     * @return bool
     */
    public function isForbidden(): bool
    {
        return ResponseCode::HTTP_FORBIDDEN === $this->statusCode;
    }

    /**
     * Is the response a not found error?
     *
     * @return bool
     */
    public function isNotFound(): bool
    {
        return ResponseCode::HTTP_NOT_FOUND === $this->statusCode;
    }

    /**
     * Is the response a redirect of some form?
     *
     * @param string $location [optional] Redirect location
     *
     * @return bool
     */
    public function isRedirect(string $location = null): bool
    {
        return in_array(
                $this->statusCode,
                [
                    ResponseCode::HTTP_CREATED,
                    ResponseCode::HTTP_MOVED_PERMANENTLY,
                    ResponseCode::HTTP_FOUND,
                    ResponseCode::HTTP_SEE_OTHER,
                    ResponseCode::HTTP_TEMPORARY_REDIRECT,
                    ResponseCode::HTTP_PERMANENTLY_REDIRECT,
                ],
                true
            )
            && (null === $location
                ?: $location === $this->headers->get('Location'));
    }

    /**
     * Is the response empty?
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return in_array(
            $this->statusCode,
            [
                ResponseCode::HTTP_NO_CONTENT,
                ResponseCode::HTTP_NOT_MODIFIED,
            ],
            true
        );
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
    public static function closeOutputBuffers(int $targetLevel, bool $flush): void
    {
        $status = ob_get_status(true);
        $level = count($status);
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE')
            ? PHP_OUTPUT_HANDLER_REMOVABLE | ($flush
                ? PHP_OUTPUT_HANDLER_FLUSHABLE
                : PHP_OUTPUT_HANDLER_CLEANABLE)
            : -1;

        while (
            $level-- > $targetLevel && ($s = $status[$level]) &&
            ($s['del'] ?? ! isset($s['flags']) || $flags === ($s['flags'] & $flags))
        ) {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }
}
