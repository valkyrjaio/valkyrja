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

/**
 * Interface Response.
 *
 * @author Melech Mizrachi
 */
interface Response
{
    /**
     * Create a new response.
     *
     * @param mixed $content [optional] The response content, see setContent()
     * @param int   $status  [optional] The response status code
     * @param array $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Http\Response
     */
    public static function create(
        string $content = '',
        int $status = StatusCode::OK,
        array $headers = []
    ): self;

    /**
     * Set the content for the response.
     *
     * @param string $content The response content to set
     *
     * @return \Valkyrja\Http\Response
     */
    public function setContent(string $content): self;

    /**
     * Get the content for the response.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version [optional] The protocol version to set
     *
     * @return \Valkyrja\Http\Response
     */
    public function setProtocolVersion(string $version = '1.0'): self;

    /**
     * Gets the HTTP protocol version.
     *
     * @return string
     */
    public function getProtocolVersion(): string;

    /**
     * Sets the response status code.
     *
     * @param int    $code HTTP status code
     * @param string $text [optional] HTTP status text
     *
     * If the status text is null it will be automatically populated for the known
     * status codes and left empty otherwise.
     *
     * @return \Valkyrja\Http\Response
     */
    public function setStatusCode(int $code, string $text = null): self;

    /**
     * Retrieves the status code for the current web response.
     *
     * @return int Status code
     */
    public function getStatusCode(): int;

    /**
     * Sets the response charset.
     *
     * @param string $charset Character set
     *
     * @return \Valkyrja\Http\Response
     */
    public function setCharset(string $charset): self;

    /**
     * Retrieves the response charset.
     *
     * @return string Character set
     */
    public function getCharset(): string;

    /**
     * Set response headers.
     *
     * @param array $headers [optional] The headers to set
     *
     * @return \Valkyrja\Http\Response
     */
    public function setHeaders(array $headers = []): self;

    /**
     * Get response headers object.
     *
     * @return \Valkyrja\Http\Headers
     */
    public function headers(): Headers;

    /**
     * Returns the Date header as a DateTime instance.
     *
     * @return \DateTime A \DateTime instance
     */
    public function getDateHeader(): DateTime;

    /**
     * Sets the Date header.
     *
     * @param \DateTime $date A \DateTime instance
     *
     * @return \Valkyrja\Http\Response
     */
    public function setDateHeader(DateTime $date): self;

    /**
     * Get response cookies object.
     *
     * @return \Valkyrja\Http\Cookies
     */
    public function cookies(): Cookies;

    /**
     * Set a response cache control.
     *
     * @param string $name  Cache control name
     * @param string $value [optional] Cache control value
     *
     * @return \Valkyrja\Http\Response
     */
    public function addCacheControl(string $name, string $value = null): self;

    /**
     * Get a response cache control.
     *
     * @param string $name Cache control name
     *
     * @return string
     */
    public function getCacheControl(string $name): string;

    /**
     * Check if a response cache control exists.
     *
     * @param string $name Cache control name
     *
     * @return bool
     */
    public function hasCacheControl(string $name): bool;

    /**
     * Remove a response cache control.
     *
     * @param string $name Cache control name
     *
     * @return \Valkyrja\Http\Response
     */
    public function removeCacheControl(string $name): self;

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
    public function isCacheable(): bool;

    /**
     * Returns true if the response is "fresh".
     *
     * Fresh responses may be served from cache without any interaction with the
     * origin. A response is considered fresh when it includes a Cache-Control/max-age
     * indicator or Expires header and the calculated age is less than the freshness lifetime.
     *
     * @return bool true if the response is fresh, false otherwise
     */
    public function isFresh(): bool;

    /**
     * Returns true if the response includes headers that can be used to validate
     * the response with the origin server using a conditional GET request.
     *
     * @return bool true if the response is validateable, false otherwise
     */
    public function isValidateable(): bool;

    /**
     * Marks the response as "private".
     *
     * It makes the response ineligible for serving other clients.
     *
     * @return \Valkyrja\Http\Response
     */
    public function setPrivate(): self;

    /**
     * Marks the response as "public".
     *
     * It makes the response eligible for serving other clients.
     *
     * @return \Valkyrja\Http\Response
     */
    public function setPublic(): self;

    /**
     * Returns the age of the response.
     *
     * @return int The age of the response in seconds
     */
    public function getAge(): int;

    /**
     * Marks the response stale by setting the Age header to be equal to the maximum age of the response.
     *
     * @return \Valkyrja\Http\Response
     */
    public function expire(): self;

    /**
     * Returns the value of the Expires header as a DateTime instance.
     *
     * @return \DateTime A DateTime instance or null if the header does not exist
     */
    public function getExpires(): DateTime;

    /**
     * Sets the Expires HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param \DateTime $date [optional] A \DateTime instance or null to remove the header
     *
     * @return \Valkyrja\Http\Response
     */
    public function setExpires(DateTime $date = null): self;

    /**
     * Returns the number of seconds after the time specified in the response's Date
     * header when the response should no longer be considered fresh.
     *
     * First, it checks for a s-maxage directive, then a max-age directive, and then it falls
     * back on an expires header. It returns null when no maximum age can be established.
     *
     * @return int Number of seconds
     */
    public function getMaxAge(): int;

    /**
     * Sets the number of seconds after which the response should no longer be considered fresh.
     *
     * This methods sets the Cache-Control max-age directive.
     *
     * @param int $value Number of seconds
     *
     * @return \Valkyrja\Http\Response
     */
    public function setMaxAge(int $value): self;

    /**
     * Sets the number of seconds after which the response should no longer be considered fresh by shared caches.
     *
     * This methods sets the Cache-Control s-maxage directive.
     *
     * @param int $value Number of seconds
     *
     * @return \Valkyrja\Http\Response
     */
    public function setSharedMaxAge(int $value): self;

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
    public function getTtl(): int;

    /**
     * Sets the response's time-to-live for shared caches.
     *
     * This method adjusts the Cache-Control/s-maxage directive.
     *
     * @param int $seconds Number of seconds
     *
     * @return \Valkyrja\Http\Response
     */
    public function setTtl(int $seconds): self;

    /**
     * Sets the response's time-to-live for private/client caches.
     *
     * This method adjusts the Cache-Control/max-age directive.
     *
     * @param int $seconds Number of seconds
     *
     * @return \Valkyrja\Http\Response
     */
    public function setClientTtl(int $seconds): self;

    /**
     * Returns the Last-Modified HTTP header as a DateTime instance.
     *
     * @return string A date string
     */
    public function getLastModified(): string;

    /**
     * Sets the Last-Modified HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param \DateTime $date [optional] A \DateTime instance or null to remove the header
     *
     * @return \Valkyrja\Http\Response
     */
    public function setLastModified(DateTime $date = null): self;

    /**
     * Returns the literal value of the ETag HTTP header.
     *
     * @return string The ETag HTTP header or null if it does not exist
     */
    public function getEtag(): string;

    /**
     * Sets the ETag value.
     *
     * @param string $etag [optional] The ETag unique identifier or null to remove the header
     * @param bool   $weak [optional] Whether you want a weak ETag or not
     *
     * @return \Valkyrja\Http\Response
     */
    public function setEtag(string $etag = null, bool $weak = false): self;

    /**
     * Sets the response's cache headers (validation and/or expiration).
     *
     * Available options are: etag, last_modified, max_age, s_maxage, private, and public.
     *
     * @param array $options An array of cache options
     *
     * @return \Valkyrja\Http\Response
     */
    public function setCache(array $options): self;

    /**
     * Modifies the response so that it conforms to the rules defined for a 304 status code.
     *
     * This sets the status, removes the body, and discards any headers
     * that MUST NOT be included in 304 responses.
     *
     * @return \Valkyrja\Http\Response
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3.5
     */
    public function setNotModified(): self;

    /**
     * Is response invalid?
     *
     * @return bool
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public function isInvalid(): bool;

    /**
     * Is response informative?
     *
     * @return bool
     */
    public function isInformational(): bool;

    /**
     * Is response successful?
     *
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * Is the response a redirect?
     *
     * @return bool
     */
    public function isRedirection(): bool;

    /**
     * Is there a client error?
     *
     * @return bool
     */
    public function isClientError(): bool;

    /**
     * Was there a server side error?
     *
     * @return bool
     */
    public function isServerError(): bool;

    /**
     * Is the response OK?
     *
     * @return bool
     */
    public function isOk(): bool;

    /**
     * Is the response forbidden?
     *
     * @return bool
     */
    public function isForbidden(): bool;

    /**
     * Is the response a not found error?
     *
     * @return bool
     */
    public function isNotFound(): bool;

    /**
     * Is the response a redirect of some form?
     *
     * @param string $location [optional] Redirect location
     *
     * @return bool
     */
    public function isRedirect(string $location = null): bool;

    /**
     * Is the response empty?
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Sends HTTP headers.
     *
     * @return \Valkyrja\Http\Response
     */
    public function sendHeaders(): self;

    /**
     * Sends content for the current web response.
     *
     * @return \Valkyrja\Http\Response
     */
    public function sendContent(): self;

    /**
     * Sends HTTP headers and content.
     *
     * @return \Valkyrja\Http\Response
     */
    public function send(): self;

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
    public static function closeOutputBuffers(int $targetLevel, bool $flush): void;

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
    public function __toString(): string;
}
