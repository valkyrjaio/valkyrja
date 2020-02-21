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

namespace Valkyrja\Http;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Application\Application;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\InvalidStatusCodeException;
use Valkyrja\Support\Providers\Provides;
use Valkyrja\View\View;

use function count;
use function defined;
use function function_exists;
use function in_array;

/**
 * Class Response.
 *
 * @author Melech Mizrachi
 */
class NativeResponse implements Response
{
    use Provides;

    /**
     * Response headers.
     *
     * @var Headers|null
     */
    protected ?Headers $headers = null;

    /**
     * Cache control.
     *
     * @var array
     */
    protected array $cacheControl = [];

    /**
     * Response cookies.
     *
     * @var Cookies
     */
    protected Cookies $cookies;

    /**
     * Response content.
     *
     * @var string
     */
    protected string $content;

    /**
     * Response protocol version.
     *
     * @var string
     */
    protected string $version;

    /**
     * Response status code.
     *
     * @var int
     */
    protected int $statusCode;

    /**
     * Response status text.
     *
     * @var string
     */
    protected string $statusText;

    /**
     * Response charset.
     *
     * @var string
     */
    protected string $charset;

    /**
     * The view to use.
     *
     * @var View
     */
    protected View $view;

    /**
     * Response constructor.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $content = '', int $status = StatusCode::OK, array $headers = [])
    {
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion();

        $this->cookies = new Cookies();
    }

    /**
     * Create a new response.
     *
     * @param string $content [optional] The response content, see setContent()
     * @param int    $status  [optional] The response status code
     * @param array  $headers [optional] An array of response headers
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public static function create(string $content = '', int $status = StatusCode::OK, array $headers = []): Response
    {
        return new static($content, $status, $headers);
    }

    /**
     * Sends HTTP headers.
     *
     * @return Response
     */
    public function sendHeaders(): Response
    {
        // Headers have already been sent so there's nothing to do here
        if (headers_sent()) {
            return $this;
        }

        // If there is no date header
        if (! $this->headers->has('Date')) {
            // Set it with the current time
            $this->setDateHeader(DateTime::createFromFormat('U', (string) time()));
        }

        // Iterate through all the headers
        foreach ($this->headers->all() as $name => $value) {
            // Set the headers
            header($name . ': ' . $value, false, $this->statusCode);
        }

        // Set the status of the response
        header(
            sprintf(
                'HTTP/%s %s %s',
                $this->version,
                $this->statusCode,
                $this->statusText
            ),
            true,
            $this->statusCode
        );

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
     * @return Response
     */
    public function sendContent(): Response
    {
        echo $this->content;

        return $this;
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return Response
     */
    public function send(): Response
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

        return sprintf(
                'HTTP/%s %s %s',
                $this->version,
                $this->statusCode,
                $this->statusText
            )
            . "\r\n"
            . $cookies
            . $this->headers
            . "\r\n"
            . $this->getContent();
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Response::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            Response::class,
            new static()
        );
    }    /**
     * Set the content for the response.
     *
     * @param string $content The response content to set
     *
     * @return Response
     */
    public function setContent(string $content): Response
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
     * @return Response
     */
    public function setProtocolVersion(string $version = '1.0'): Response
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
     *                     If the status text is null it will be automatically populated for the
     *                     known status codes and left empty otherwise.
     *
     * @throws InvalidStatusCodeException
     *
     * @return Response
     */
    public function setStatusCode(int $code, string $text = null): Response
    {
        $this->statusCode = $code;

        // Check if the status code is valid
        if ($this->isInvalid()) {
            throw new InvalidStatusCodeException(sprintf('The HTTP status code "%s" is not valid.', $code));
        }

        // If no text was supplied
        if (null === $text) {
            // Set the status text from the status texts array
            $this->statusText = StatusCode::TEXTS[$code] ?? 'unknown status';

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
     * @return Response
     */
    public function setCharset(string $charset): Response
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
     * @return Response
     */
    public function setHeaders(array $headers = []): Response
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
     * @return Headers
     */
    public function headers(): Headers
    {
        return $this->headers;
    }

    /**
     * Returns the Date header as a DateTime instance.
     *
     * @throws RuntimeException When the header is not parseable
     *
     * @return DateTime A DateTime instance
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
     * @return Response
     */
    public function setDateHeader(DateTime $date): Response
    {
        $date->setTimezone(new DateTimeZone('UTC'));
        $this->headers->set('Date', $date->format('D, d M Y H:i:s') . ' GMT');

        return $this;
    }

    /**
     * Get response cookies collection.
     *
     * @return Cookies
     */
    public function cookies(): Cookies
    {
        return $this->cookies;
    }

    /**
     * Set a response cache control.
     *
     * @param string $name  Cache control name
     * @param string $value [optional] Cache control value
     *
     * @return Response
     */
    public function addCacheControl(string $name, string $value = null): Response
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
            : 'false';
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
     * @return Response
     */
    public function removeCacheControl(string $name): Response
    {
        if (! $this->hasCacheControl($name)) {
            return $this;
        }

        unset($this->cacheControl[$name]);

        return $this;
    }

    /**
     * Returns true if the response is worth caching under any circumstance.
     * Responses marked "private" with an explicit Cache-Control directive are
     * considered uncacheable.
     * Responses with neither a freshness lifetime (Expires, max-age) nor cache
     * validator (Last-Modified, ETag) are considered uncacheable.
     *
     * @throws RuntimeException
     *
     * @return bool true if the response is worth caching, false otherwise
     */
    public function isCacheable(): bool
    {
        if (! in_array(
            $this->statusCode,
            [
                StatusCode::OK,
                StatusCode::NON_AUTHORITATIVE_INFORMATION,
                StatusCode::MULTIPLE_CHOICES,
                StatusCode::MOVED_PERMANENTLY,
                StatusCode::FOUND,
                StatusCode::NOT_FOUND,
                StatusCode::GONE,
            ],
            true
        )
        ) {
            return false;
        }

        if (
            $this->hasCacheControl('no-store')
            || $this->hasCacheControl('private')
        ) {
            return false;
        }

        return $this->isValidateable() || $this->isFresh();
    }

    /**
     * Returns true if the response is "fresh".
     * Fresh responses may be served from cache without any interaction with
     * the
     * origin. A response is considered fresh when it includes a
     * Cache-Control/max-age indicator or Expires header and the calculated age
     * is less than the freshness lifetime.
     *
     * @throws RuntimeException
     *
     * @return bool true if the response is fresh, false otherwise
     */
    public function isFresh(): bool
    {
        return $this->getTtl() > 0;
    }

    /**
     * Returns true if the response includes headers that can be used to
     * validate the response with the origin server using a conditional GET
     * request.
     *
     * @return bool true if the response is validateable, false otherwise
     */
    public function isValidateable(): bool
    {
        return $this->headers->has('Last-Modified')
            || $this->headers->has('ETag');
    }

    /**
     * Marks the response as "private".
     * It makes the response ineligible for serving other clients.
     *
     * @return Response
     */
    public function setPrivate(): Response
    {
        $this->removeCacheControl('public');
        $this->addCacheControl('private');

        return $this;
    }

    /**
     * Marks the response as "public".
     * It makes the response eligible for serving other clients.
     *
     * @return Response
     */
    public function setPublic(): Response
    {
        $this->addCacheControl('public');
        $this->removeCacheControl('private');

        return $this;
    }

    /**
     * Returns the age of the response.
     *
     * @throws RuntimeException
     *
     * @return int The age of the response in seconds
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
     * Marks the response stale by setting the Age header to be equal to the
     * maximum age of the response.
     *
     * @throws RuntimeException
     *
     * @return Response
     */
    public function expire(): Response
    {
        if ($this->isFresh()) {
            $this->headers->set('Age', $this->getMaxAge());
        }

        return $this;
    }

    /**
     * Returns the value of the Expires header as a DateTime instance.
     *
     * @return DateTime A DateTime instance or null if the header does not exist
     */
    public function getExpires(): ?DateTime
    {
        try {
            return $this->headers->get('Expires');
        } catch (RuntimeException $e) {
            // according to RFC 2616 invalid date formats (e.g. "0" and "-1")
            // must be treated as in the past
            return DateTime::createFromFormat(
                DATE_RFC2822,
                'Sat, 01 Jan 00 00:00:00 +0000'
            );
        }
    }

    /**
     * Sets the Expires HTTP header with a DateTime instance.
     * Passing null as value will remove the header.
     *
     * @param DateTime $date [optional] A DateTime instance or null to remove
     *                       the header
     *
     * @return Response
     */
    public function setExpires(DateTime $date = null): Response
    {
        if (null === $date) {
            $this->headers->remove('Expires');
        } else {
            $date = clone $date;
            $date->setTimezone(new DateTimeZone('UTC'));
            $this->headers->set(
                'Expires',
                $date->format('D, d M Y H:i:s') . ' GMT'
            );
        }

        return $this;
    }

    /**
     * Returns the number of seconds after the time specified in the response's
     * Date header when the response should no longer be considered fresh.
     * First, it checks for a s-maxage directive, then a max-age directive, and
     * then it falls back on an expires header. It returns null when no maximum
     * age can be established.
     *
     * @throws RuntimeException
     *
     * @return int Number of seconds
     */
    public function getMaxAge(): int
    {
        if ($this->hasCacheControl('s-maxage')) {
            return (int) $this->getCacheControl('s-maxage');
        }

        if ($this->hasCacheControl('max-age')) {
            return (int) $this->getCacheControl('max-age');
        }

        if (null !== $this->getExpires()) {
            return (int) date('U', strtotime($this->getExpires())) - date(
                    'U',
                    strtotime(
                        $this->getDateHeader()
                    )
                );
        }

        return 0;
    }

    /**
     * Sets the number of seconds after which the response should no longer be
     * considered fresh.
     * This methods sets the Cache-Control max-age directive.
     *
     * @param int $value Number of seconds
     *
     * @return Response
     */
    public function setMaxAge(int $value): Response
    {
        $this->addCacheControl('max-age', (string) $value);

        return $this;
    }

    /**
     * Sets the number of seconds after which the response should no longer be
     * considered fresh by shared caches.
     * This methods sets the Cache-Control s-maxage directive.
     *
     * @param int $value Number of seconds
     *
     * @return Response
     */
    public function setSharedMaxAge(int $value): Response
    {
        $this->setPublic();
        $this->addCacheControl('s-maxage', (string) $value);

        return $this;
    }

    /**
     * Returns the response's time-to-live in seconds.
     * It returns null when no freshness information is present in the
     * response.
     * When the responses TTL is <= 0, the response may not be served from
     * cache without first re-validating with the origin.
     *
     * @throws RuntimeException
     *
     * @return int The TTL in seconds
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
     * This method adjusts the Cache-Control/s-maxage directive.
     *
     * @param int $seconds Number of seconds
     *
     * @throws RuntimeException
     *
     * @return Response
     */
    public function setTtl(int $seconds): Response
    {
        $this->setSharedMaxAge($this->getAge() + $seconds);

        return $this;
    }

    /**
     * Sets the response's time-to-live for private/client caches.
     * This method adjusts the Cache-Control/max-age directive.
     *
     * @param int $seconds Number of seconds
     *
     * @throws RuntimeException
     *
     * @return Response
     */
    public function setClientTtl(int $seconds): Response
    {
        $this->setMaxAge($this->getAge() + $seconds);

        return $this;
    }

    /**
     * Returns the Last-Modified HTTP header as a DateTime instance.
     *
     * @throws RuntimeException When the HTTP header is not parseable
     *
     * @return string A date string
     */
    public function getLastModified(): string
    {
        return $this->headers->get('Last-Modified');
    }

    /**
     * Sets the Last-Modified HTTP header with a DateTime instance.
     * Passing null as value will remove the header.
     *
     * @param DateTime $date [optional] A DateTime instance or null to remove
     *                       the header
     *
     * @return Response
     */
    public function setLastModified(DateTime $date = null): Response
    {
        if (null === $date) {
            $this->headers->remove('Last-Modified');
        } else {
            $date = clone $date;
            $date->setTimezone(new DateTimeZone('UTC'));
            $this->headers->set(
                'Last-Modified',
                $date->format('D, d M Y H:i:s') . ' GMT'
            );
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
     * @param string $etag [optional] The ETag unique identifier or null to
     *                     remove the header
     * @param bool   $weak [optional] Whether you want a weak ETag or not
     *
     * @return Response
     */
    public function setEtag(string $etag = null, bool $weak = false): Response
    {
        if (null === $etag) {
            $this->headers->remove('Etag');
        } else {
            if (0 !== strpos($etag, '"')) {
                $etag = '"' . $etag . '"';
            }
            $this->headers->set(
                'ETag',
                ($weak ? 'W/' : '') . $etag
            );
        }

        return $this;
    }

    /**
     * Sets the response's cache headers (validation and/or expiration).
     * Available options are: etag, last_modified, max_age, s_maxage, private,
     * and public.
     *
     * @param array $options An array of cache options
     *
     * @return Response
     */
    public function setCache(array $options): Response
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
     * Modifies the response so that it conforms to the rules defined for a 304
     * status code.
     * This sets the status, removes the body, and discards any headers
     * that MUST NOT be included in 304 responses.
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3.5
     */
    public function setNotModified(): Response
    {
        $this->setStatusCode(StatusCode::NOT_MODIFIED);
        $this->setContent('');
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
        return $this->statusCode < StatusCode::CONTINUE
            || $this->statusCode >= StatusCode::NETWORK_AUTHENTICATION_REQUIRED;
    }

    /**
     * Is response informative?
     *
     * @return bool
     */
    public function isInformational(): bool
    {
        return $this->statusCode >= StatusCode::CONTINUE
            && $this->statusCode < StatusCode::OK;
    }

    /**
     * Is response successful?
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= StatusCode::OK
            && $this->statusCode < StatusCode::MULTIPLE_CHOICES;
    }

    /**
     * Is the response a redirect?
     *
     * @return bool
     */
    public function isRedirection(): bool
    {
        return $this->statusCode >= StatusCode::MULTIPLE_CHOICES
            && $this->statusCode < StatusCode::BAD_REQUEST;
    }

    /**
     * Is there a client error?
     *
     * @return bool
     */
    public function isClientError(): bool
    {
        return $this->statusCode >= StatusCode::BAD_REQUEST
            && $this->statusCode < StatusCode::INTERNAL_SERVER_ERROR;
    }

    /**
     * Was there a server side error?
     *
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->statusCode >= StatusCode::INTERNAL_SERVER_ERROR
            && $this->statusCode < 600;
    }

    /**
     * Is the response OK?
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return StatusCode::OK === $this->statusCode;
    }

    /**
     * Is the response forbidden?
     *
     * @return bool
     */
    public function isForbidden(): bool
    {
        return StatusCode::FORBIDDEN === $this->statusCode;
    }

    /**
     * Is the response a not found error?
     *
     * @return bool
     */
    public function isNotFound(): bool
    {
        return StatusCode::NOT_FOUND === $this->statusCode;
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
                    StatusCode::CREATED,
                    StatusCode::MOVED_PERMANENTLY,
                    StatusCode::FOUND,
                    StatusCode::SEE_OTHER,
                    StatusCode::TEMPORARY_REDIRECT,
                    StatusCode::PERMANENT_REDIRECT,
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
                StatusCode::NO_CONTENT,
                StatusCode::NOT_MODIFIED,
            ],
            true
        );
    }

    /**
     * Cleans or flushes output buffers up to target level.
     * Resulting level can be greater than target level if a non-removable
     * buffer has been encountered.
     *
     * @param int  $targetLevel The target output buffering level
     * @param bool $flush       Whether to flush or clean the buffers
     *
     * @return void
     */
    public static function closeOutputBuffers(int $targetLevel, bool $flush): void
    {
        $status = ob_get_status(true);
        $level  = count($status);
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE')
            ? PHP_OUTPUT_HANDLER_REMOVABLE | ($flush
                ? PHP_OUTPUT_HANDLER_FLUSHABLE
                : PHP_OUTPUT_HANDLER_CLEANABLE)
            : -1;

        while (
            $level-- > $targetLevel
            && ($s = $status[$level])
            && ($s['del'] ?? ! isset($s['flags']) || $flags === ($s['flags'] & $flags))
        ) {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }
}
