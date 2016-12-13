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

use DateTime;
use Valkyrja\Contracts\View\View;

/**
 * Interface Response
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Response
{
    /**
     * @constant
     *
     * Status codes constants.
     *
     * The list of codes is complete according to the
     * {@link http://www.iana.org/assignments/http-status-codes/ Hypertext Transfer Protocol (HTTP) Status Code
     * Registry}
     * (last updated 2016-03-01).
     *
     * Unless otherwise noted, the status code is defined in RFC2616.
     */
    const HTTP_CONTINUE = 100;
    const HTTP_SWITCHING_PROTOCOLS = 101;
    const HTTP_PROCESSING = 102; // RFC2518
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
    const HTTP_NO_CONTENT = 204;
    const HTTP_RESET_CONTENT = 205;
    const HTTP_PARTIAL_CONTENT = 206;
    const HTTP_MULTI_STATUS = 207; // RFC4918
    const HTTP_ALREADY_REPORTED = 208; // RFC5842
    const HTTP_IM_USED = 226; // RFC3229
    const HTTP_MULTIPLE_CHOICES = 300;
    const HTTP_MOVED_PERMANENTLY = 301;
    const HTTP_FOUND = 302;
    const HTTP_SEE_OTHER = 303;
    const HTTP_NOT_MODIFIED = 304;
    const HTTP_USE_PROXY = 305;
    const HTTP_RESERVED = 306;
    const HTTP_TEMPORARY_REDIRECT = 307;
    const HTTP_PERMANENTLY_REDIRECT = 308; // RFC7238
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_PAYMENT_REQUIRED = 402;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    const HTTP_REQUEST_TIMEOUT = 408;
    const HTTP_CONFLICT = 409;
    const HTTP_GONE = 410;
    const HTTP_LENGTH_REQUIRED = 411;
    const HTTP_PRECONDITION_FAILED = 412;
    const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    const HTTP_REQUEST_URI_TOO_LONG = 414;
    const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const HTTP_EXPECTATION_FAILED = 417;
    const HTTP_I_AM_A_TEAPOT = 418; // RFC2324
    const HTTP_MISDIRECTED_REQUEST = 421; // RFC7540
    const HTTP_UNPROCESSABLE_ENTITY = 422; // RFC4918
    const HTTP_LOCKED = 423; // RFC4918
    const HTTP_FAILED_DEPENDENCY = 424; // RFC4918
    const HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425; // RFC2817
    const HTTP_UPGRADE_REQUIRED = 426; // RFC2817
    const HTTP_PRECONDITION_REQUIRED = 428; // RFC6585
    const HTTP_TOO_MANY_REQUESTS = 429; // RFC6585
    const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431; // RFC6585
    const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED = 501;
    const HTTP_BAD_GATEWAY = 502;
    const HTTP_SERVICE_UNAVAILABLE = 503;
    const HTTP_GATEWAY_TIMEOUT = 504;
    const HTTP_VERSION_NOT_SUPPORTED = 505;
    const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506; // RFC2295
    const HTTP_INSUFFICIENT_STORAGE = 507; // RFC4918
    const HTTP_LOOP_DETECTED = 508; // RFC5842
    const HTTP_NOT_EXTENDED = 510; // RFC2774
    const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511; // RFC6585

    /**
     * @constant array
     */
    const STATUS_TEXTS = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        // RFC2518
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // RFC4918
        207 => 'Multi-Status',
        // RFC5842
        208 => 'Already Reported',
        // RFC3229
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        // RFC7238
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        // RFC2324
        418 => 'I\'m a teapot',
        // RFC7540
        421 => 'Misdirected Request',
        // RFC4918
        422 => 'Unprocessable Entity',
        // RFC4918
        423 => 'Locked',
        // RFC4918
        424 => 'Failed Dependency',
        // RFC2817
        425 => 'Reserved for WebDAV advanced collections expired proposal',
        // RFC2817
        426 => 'Upgrade Required',
        // RFC6585
        428 => 'Precondition Required',
        // RFC6585
        429 => 'Too Many Requests',
        // RFC6585
        431 => 'Request Header Fields Too Large',
        // RFC7725
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        // RFC2295
        506 => 'Variant Also Negotiates (Experimental)',
        // RFC4918
        507 => 'Insufficient Storage',
        // RFC5842
        508 => 'Loop Detected',
        // RFC2774
        510 => 'Not Extended',
        // RFC6585
        511 => 'Network Authentication Required',
    ];

    /**
     * Response constructor.
     *
     * @param mixed $content [optional] The response content, see setContent()
     * @param int   $status  [optional] The response status code
     * @param array $headers [optional] An array of response headers
     */
    public function __construct(string $content = '', int $status = 200, array $headers = []);

    /**
     * Create a new response.
     *
     * @param mixed $content [optional] The response content, see setContent()
     * @param int   $status  [optional] The response status code
     * @param array $headers [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public static function create(string $content = '', int $status = 200, array $headers = []) : Response;

    /**
     * Set the content for the response.
     *
     * @param string $content The response content to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setContent(string $content) : Response;

    /**
     * Get the content for the response.
     *
     * @return string
     */
    public function getContent() : string;

    /**
     * Set the view for the response.
     *
     * @param \Valkyrja\Contracts\View\View $view The view to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setView(View $view) : Response;

    /**
     * Get the view for the response.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view(string $template = '', array $variables = []) : View;

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version [optional] The protocol version to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setProtocolVersion(string $version = '1.0') : Response;

    /**
     * Gets the HTTP protocol version.
     *
     * @return string
     */
    public function getProtocolVersion() : string;

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
     * @throws \InvalidArgumentException When the HTTP status code is not valid
     */
    public function setStatusCode(int $code, string $text = null) : Response;

    /**
     * Retrieves the status code for the current web response.
     *
     * @return int Status code
     */
    public function getStatusCode() : int;

    /**
     * Sets the response charset.
     *
     * @param string $charset Character set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setCharset(string $charset) : Response;

    /**
     * Retrieves the response charset.
     *
     * @return string Character set
     */
    public function getCharset() : string;

    /**
     * Set response headers.
     *
     * @param array $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setHeaders(array $headers = []) : Response;

    /**
     * Get all response headers.
     *
     * @return Headers
     */
    public function headers() : Headers;

    /**
     * Returns the Date header as a DateTime instance.
     *
     * @return \DateTime A \DateTime instance
     *
     * @throws \RuntimeException When the header is not parseable
     */
    public function getDateHeader() : DateTime;

    /**
     * Sets the Date header.
     *
     * @param \DateTime $date A \DateTime instance
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setDateHeader(DateTime $date) : Response;

    /**
     * Returns an array with all cookies.
     *
     * @param bool $asString [optional] Get the cookies as a string?
     *
     * @return array
     */
    public function getCookies(bool $asString = true) : array;

    /**
     * Set a response cookie.
     *
     * @param string $name     Cookie name
     * @param string $value    Cookie value
     * @param int    $expire   Cookie expires time
     * @param string $path     Cookie path
     * @param string $domain   Cookie domain
     * @param bool   $secure   Cookie http(s)
     * @param bool   $httpOnly Cookie http only?
     * @param bool   $raw      Cookie raw
     * @param string $sameSite Cookie same site?
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setCookie(
        string $name,
        string $value = null,
        int $expire = 0,
        string $path = '/',
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = true,
        bool $raw = false,
        string $sameSite = null
    ) : Response;

    /**
     * Removes a cookie from the array, but does not unset it in the browser.
     *
     * @param string $name   Cookie name
     * @param string $path   [optional] Cookie path
     * @param string $domain [optional] Cookie domain
     *
     * @return void
     */
    public function removeCookie(string $name, string $path = '/', string $domain = null) : void;

    /**
     * Set a response cache control.
     *
     * @param string $name  Cache control name
     * @param string $value [optional] Cache control value
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function addCacheControl(string $name, string $value = null) : Response;

    /**
     * Get a response cache control.
     *
     * @param string $name Cache control name
     *
     * @return string
     */
    public function getCacheControl(string $name) : string;

    /**
     * Check if a response cache control exists.
     *
     * @param string $name Cache control name
     *
     * @return bool
     */
    public function hasCacheControl(string $name) : bool;

    /**
     * Remove a response cache control.
     *
     * @param string $name Cache control name
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function removeCacheControl(string $name) : Response;

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
    public function isCacheable() : bool;

    /**
     * Returns true if the response is "fresh".
     *
     * Fresh responses may be served from cache without any interaction with the
     * origin. A response is considered fresh when it includes a Cache-Control/max-age
     * indicator or Expires header and the calculated age is less than the freshness lifetime.
     *
     * @return bool true if the response is fresh, false otherwise
     */
    public function isFresh() : bool;

    /**
     * Returns true if the response includes headers that can be used to validate
     * the response with the origin server using a conditional GET request.
     *
     * @return bool true if the response is validateable, false otherwise
     */
    public function isValidateable() : bool;

    /**
     * Marks the response as "private".
     *
     * It makes the response ineligible for serving other clients.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setPrivate() : Response;

    /**
     * Marks the response as "public".
     *
     * It makes the response eligible for serving other clients.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setPublic() : Response;

    /**
     * Returns the age of the response.
     *
     * @return int The age of the response in seconds
     */
    public function getAge() : int;

    /**
     * Marks the response stale by setting the Age header to be equal to the maximum age of the response.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function expire() : Response;

    /**
     * Returns the value of the Expires header as a DateTime instance.
     *
     * @return \DateTime|null A DateTime instance or null if the header does not exist
     */
    public function getExpires() : DateTime;

    /**
     * Sets the Expires HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param \DateTime $date [optional] A \DateTime instance or null to remove the header
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setExpires(DateTime $date = null) : Response;

    /**
     * Returns the number of seconds after the time specified in the response's Date
     * header when the response should no longer be considered fresh.
     *
     * First, it checks for a s-maxage directive, then a max-age directive, and then it falls
     * back on an expires header. It returns null when no maximum age can be established.
     *
     * @return int Number of seconds
     */
    public function getMaxAge() : int;

    /**
     * Sets the number of seconds after which the response should no longer be considered fresh.
     *
     * This methods sets the Cache-Control max-age directive.
     *
     * @param int $value Number of seconds
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setMaxAge(int $value) : Response;

    /**
     * Sets the number of seconds after which the response should no longer be considered fresh by shared caches.
     *
     * This methods sets the Cache-Control s-maxage directive.
     *
     * @param int $value Number of seconds
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setSharedMaxAge(int $value) : Response;

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
    public function getTtl() : int;

    /**
     * Sets the response's time-to-live for shared caches.
     *
     * This method adjusts the Cache-Control/s-maxage directive.
     *
     * @param int $seconds Number of seconds
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setTtl(int $seconds) : Response;

    /**
     * Sets the response's time-to-live for private/client caches.
     *
     * This method adjusts the Cache-Control/max-age directive.
     *
     * @param int $seconds Number of seconds
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setClientTtl(int $seconds) : Response;

    /**
     * Returns the Last-Modified HTTP header as a DateTime instance.
     *
     * @return string A date string
     *
     * @throws \RuntimeException When the HTTP header is not parseable
     */
    public function getLastModified() : string;

    /**
     * Sets the Last-Modified HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param \DateTime $date [optional] A \DateTime instance or null to remove the header
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setLastModified(DateTime $date = null) : Response;

    /**
     * Returns the literal value of the ETag HTTP header.
     *
     * @return string The ETag HTTP header or null if it does not exist
     */
    public function getEtag() : string;

    /**
     * Sets the ETag value.
     *
     * @param string $etag [optional] The ETag unique identifier or null to remove the header
     * @param bool   $weak [optional] Whether you want a weak ETag or not
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setEtag(string $etag = null, bool $weak = false) : Response;

    /**
     * Sets the response's cache headers (validation and/or expiration).
     *
     * Available options are: etag, last_modified, max_age, s_maxage, private, and public.
     *
     * @param array $options An array of cache options
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function setCache(array $options) : Response;

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
    public function setNotModified() : Response;

    /**
     * Is response invalid?
     *
     * @return bool
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public function isInvalid() : bool;

    /**
     * Is response informative?
     *
     * @return bool
     */
    public function isInformational() : bool;

    /**
     * Is response successful?
     *
     * @return bool
     */
    public function isSuccessful() : bool;

    /**
     * Is the response a redirect?
     *
     * @return bool
     */
    public function isRedirection() : bool;

    /**
     * Is there a client error?
     *
     * @return bool
     */
    public function isClientError() : bool;

    /**
     * Was there a server side error?
     *
     * @return bool
     */
    public function isServerError() : bool;

    /**
     * Is the response OK?
     *
     * @return bool
     */
    public function isOk() : bool;

    /**
     * Is the response forbidden?
     *
     * @return bool
     */
    public function isForbidden() : bool;

    /**
     * Is the response a not found error?
     *
     * @return bool
     */
    public function isNotFound() : bool;

    /**
     * Is the response a redirect of some form?
     *
     * @param string $location [optional] Redirect location
     *
     * @return bool
     */
    public function isRedirect(string $location = null) : bool;

    /**
     * Is the response empty?
     *
     * @return bool
     */
    public function isEmpty() : bool;

    /**
     * Sends HTTP headers.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function sendHeaders() : Response;

    /**
     * Sends content for the current web response.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function sendContent() : Response;

    /**
     * Sends HTTP headers and content.
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function send() : Response;

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
    public static function closeOutputBuffers(int $targetLevel, bool $flush) : void;

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
    public function __toString() : string;
}
