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
    const HTTP_CONTINUE                                                  = 100;
    const HTTP_SWITCHING_PROTOCOLS                                       = 101;
    const HTTP_PROCESSING                                                = 102; // RFC2518
    const HTTP_OK                                                        = 200;
    const HTTP_CREATED                                                   = 201;
    const HTTP_ACCEPTED                                                  = 202;
    const HTTP_NON_AUTHORITATIVE_INFORMATION                             = 203;
    const HTTP_NO_CONTENT                                                = 204;
    const HTTP_RESET_CONTENT                                             = 205;
    const HTTP_PARTIAL_CONTENT                                           = 206;
    const HTTP_MULTI_STATUS                                              = 207; // RFC4918
    const HTTP_ALREADY_REPORTED                                          = 208; // RFC5842
    const HTTP_IM_USED                                                   = 226; // RFC3229
    const HTTP_MULTIPLE_CHOICES                                          = 300;
    const HTTP_MOVED_PERMANENTLY                                         = 301;
    const HTTP_FOUND                                                     = 302;
    const HTTP_SEE_OTHER                                                 = 303;
    const HTTP_NOT_MODIFIED                                              = 304;
    const HTTP_USE_PROXY                                                 = 305;
    const HTTP_RESERVED                                                  = 306;
    const HTTP_TEMPORARY_REDIRECT                                        = 307;
    const HTTP_PERMANENTLY_REDIRECT                                      = 308; // RFC7238
    const HTTP_BAD_REQUEST                                               = 400;
    const HTTP_UNAUTHORIZED                                              = 401;
    const HTTP_PAYMENT_REQUIRED                                          = 402;
    const HTTP_FORBIDDEN                                                 = 403;
    const HTTP_NOT_FOUND                                                 = 404;
    const HTTP_METHOD_NOT_ALLOWED                                        = 405;
    const HTTP_NOT_ACCEPTABLE                                            = 406;
    const HTTP_PROXY_AUTHENTICATION_REQUIRED                             = 407;
    const HTTP_REQUEST_TIMEOUT                                           = 408;
    const HTTP_CONFLICT                                                  = 409;
    const HTTP_GONE                                                      = 410;
    const HTTP_LENGTH_REQUIRED                                           = 411;
    const HTTP_PRECONDITION_FAILED                                       = 412;
    const HTTP_REQUEST_ENTITY_TOO_LARGE                                  = 413;
    const HTTP_REQUEST_URI_TOO_LONG                                      = 414;
    const HTTP_UNSUPPORTED_MEDIA_TYPE                                    = 415;
    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE                           = 416;
    const HTTP_EXPECTATION_FAILED                                        = 417;
    const HTTP_I_AM_A_TEAPOT                                             = 418; // RFC2324
    const HTTP_MISDIRECTED_REQUEST                                       = 421; // RFC7540
    const HTTP_UNPROCESSABLE_ENTITY                                      = 422; // RFC4918
    const HTTP_LOCKED                                                    = 423; // RFC4918
    const HTTP_FAILED_DEPENDENCY                                         = 424; // RFC4918
    const HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425; // RFC2817
    const HTTP_UPGRADE_REQUIRED                                          = 426; // RFC2817
    const HTTP_PRECONDITION_REQUIRED                                     = 428; // RFC6585
    const HTTP_TOO_MANY_REQUESTS                                         = 429; // RFC6585
    const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE                           = 431; // RFC6585
    const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS                             = 451;
    const HTTP_INTERNAL_SERVER_ERROR                                     = 500;
    const HTTP_NOT_IMPLEMENTED                                           = 501;
    const HTTP_BAD_GATEWAY                                               = 502;
    const HTTP_SERVICE_UNAVAILABLE                                       = 503;
    const HTTP_GATEWAY_TIMEOUT                                           = 504;
    const HTTP_VERSION_NOT_SUPPORTED                                     = 505;
    const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL                      = 506; // RFC2295
    const HTTP_INSUFFICIENT_STORAGE                                      = 507; // RFC4918
    const HTTP_LOOP_DETECTED                                             = 508; // RFC5842
    const HTTP_NOT_EXTENDED                                              = 510; // RFC2774
    const HTTP_NETWORK_AUTHENTICATION_REQUIRED                           = 511; // RFC6585

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
     * @param mixed $content The response content, see setContent()
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     */
    public function __construct($content = '', $status = 200, $headers = []);

    /**
     * Create a new response.
     *
     * @param mixed $content The response content, see setContent()
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     *
     * @return Response
     */
    public static function create($content = '', $status = 200, $headers = []);

    /**
     * Set the content for the response.
     *
     * @param string $content The response content to set
     *
     * @return Response
     */
    public function setContent($content);

    /**
     * Get the content for the response.
     *
     * @return string
     */
    public function getContent();

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version The protocol version to set
     *
     * @return Response
     */
    public function setProtocolVersion($version = '1.0');

    /**
     * Gets the HTTP protocol version.
     *
     * @return string
     */
    public function getProtocolVersion();

    /**
     * Sets the response status code.
     *
     * @param int   $code HTTP status code
     * @param mixed $text HTTP status text
     *
     * If the status text is null it will be automatically populated for the known
     * status codes and left empty otherwise.
     *
     * @return Response
     *
     * @throws \InvalidArgumentException When the HTTP status code is not valid
     */
    public function setStatusCode($code, $text = null);

    /**
     * Retrieves the status code for the current web response.
     *
     * @return int Status code
     */
    public function getStatusCode();

    /**
     * Sets the response charset.
     *
     * @param string $charset Character set
     *
     * @return Response
     */
    public function setCharset($charset);

    /**
     * Retrieves the response charset.
     *
     * @return string Character set
     */
    public function getCharset();

    /**
     * Set response headers.
     *
     * @param array $headers The headers to set
     *
     * @return $this
     */
    public function setHeaders(array $headers = []);

    /**
     * Get all response headers.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Set a response header.
     *
     * @param string $header The header to set
     * @param string $value  The value to set
     *
     * @return $this
     */
    public function setHeader($header, $value);

    /**
     * Get a response header.
     *
     * @param string $header The header to get
     *
     * @return bool|mixed
     */
    public function getHeader($header);

    /**
     * Check if a response header exists.
     *
     * @param string $header The header to check exists
     *
     * @return bool
     */
    public function hasHeader($header);

    /**
     * Remove a response header.
     *
     * @param string $header The header to remove
     *
     * @return $this
     */
    public function removeHeader($header);

    /**
     * Returns the Date header as a DateTime instance.
     *
     * @return \DateTime A \DateTime instance
     *
     * @throws \RuntimeException When the header is not parseable
     */
    public function getDateHeader();

    /**
     * Sets the Date header.
     *
     * @param \DateTime $date A \DateTime instance
     *
     * @return Response
     */
    public function setDateHeader(\DateTime $date);

    /**
     * Returns an array with all cookies.
     *
     * @param bool $asString Get the cookies as a string?
     *
     * @return array
     */
    public function getCookies($asString = true);

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
     * @return $this
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
    );

    /**
     * Removes a cookie from the array, but does not unset it in the browser.
     *
     * @param string $name   Cookie name
     * @param string $path   Cookie path
     * @param string $domain Cookie domain
     */
    public function removeCookie($name, $path = '/', $domain = null);

    /**
     * Set a response cache control.
     *
     * @param string $name  Cache control name
     * @param string $value Cache control value
     *
     * @return $this
     */
    public function addCacheControl($name, $value = null);

    /**
     * Get a response cache control.
     *
     * @param string $name Cache control name
     *
     * @return bool|mixed
     */
    public function getCacheControl($name);

    /**
     * Check if a response cache control exists.
     *
     * @param string $name Cache control name
     *
     * @return bool
     */
    public function hasCacheControl($name);

    /**
     * Remove a response cache control.
     *
     * @param string $name Cache control name
     *
     * @return $this
     */
    public function removeCacheControl($name);

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
    public function isCacheable();

    /**
     * Returns true if the response is "fresh".
     *
     * Fresh responses may be served from cache without any interaction with the
     * origin. A response is considered fresh when it includes a Cache-Control/max-age
     * indicator or Expires header and the calculated age is less than the freshness lifetime.
     *
     * @return bool true if the response is fresh, false otherwise
     */
    public function isFresh();

    /**
     * Returns true if the response includes headers that can be used to validate
     * the response with the origin server using a conditional GET request.
     *
     * @return bool true if the response is validateable, false otherwise
     */
    public function isValidateable();

    /**
     * Marks the response as "private".
     *
     * It makes the response ineligible for serving other clients.
     *
     * @return Response
     */
    public function setPrivate();

    /**
     * Marks the response as "public".
     *
     * It makes the response eligible for serving other clients.
     *
     * @return Response
     */
    public function setPublic();

    /**
     * Returns the age of the response.
     *
     * @return int The age of the response in seconds
     */
    public function getAge();

    /**
     * Marks the response stale by setting the Age header to be equal to the maximum age of the response.
     *
     * @return Response
     */
    public function expire();

    /**
     * Returns the value of the Expires header as a DateTime instance.
     *
     * @return \DateTime|null A DateTime instance or null if the header does not exist
     */
    public function getExpires();

    /**
     * Sets the Expires HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param \DateTime|null $date A \DateTime instance or null to remove the header
     *
     * @return Response
     */
    public function setExpires(\DateTime $date = null);

    /**
     * Returns the number of seconds after the time specified in the response's Date
     * header when the response should no longer be considered fresh.
     *
     * First, it checks for a s-maxage directive, then a max-age directive, and then it falls
     * back on an expires header. It returns null when no maximum age can be established.
     *
     * @return int|null Number of seconds
     */
    public function getMaxAge();

    /**
     * Sets the number of seconds after which the response should no longer be considered fresh.
     *
     * This methods sets the Cache-Control max-age directive.
     *
     * @param int $value Number of seconds
     *
     * @return Response
     */
    public function setMaxAge($value);

    /**
     * Sets the number of seconds after which the response should no longer be considered fresh by shared caches.
     *
     * This methods sets the Cache-Control s-maxage directive.
     *
     * @param int $value Number of seconds
     *
     * @return Response
     */
    public function setSharedMaxAge($value);

    /**
     * Returns the response's time-to-live in seconds.
     *
     * It returns null when no freshness information is present in the response.
     *
     * When the responses TTL is <= 0, the response may not be served from cache without first
     * revalidating with the origin.
     *
     * @return int|null The TTL in seconds
     */
    public function getTtl();

    /**
     * Sets the response's time-to-live for shared caches.
     *
     * This method adjusts the Cache-Control/s-maxage directive.
     *
     * @param int $seconds Number of seconds
     *
     * @return Response
     */
    public function setTtl($seconds);

    /**
     * Sets the response's time-to-live for private/client caches.
     *
     * This method adjusts the Cache-Control/max-age directive.
     *
     * @param int $seconds Number of seconds
     *
     * @return Response
     */
    public function setClientTtl($seconds);

    /**
     * Returns the Last-Modified HTTP header as a DateTime instance.
     *
     * @return \DateTime|null A DateTime instance or null if the header does not exist
     *
     * @throws \RuntimeException When the HTTP header is not parseable
     */
    public function getLastModified();

    /**
     * Sets the Last-Modified HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @param \DateTime|null $date A \DateTime instance or null to remove the header
     *
     * @return Response
     */
    public function setLastModified(\DateTime $date = null);

    /**
     * Returns the literal value of the ETag HTTP header.
     *
     * @return string|null The ETag HTTP header or null if it does not exist
     */
    public function getEtag();

    /**
     * Sets the ETag value.
     *
     * @param string|null $etag The ETag unique identifier or null to remove the header
     * @param bool        $weak Whether you want a weak ETag or not
     *
     * @return Response
     */
    public function setEtag($etag = null, $weak = false);

    /**
     * Sets the response's cache headers (validation and/or expiration).
     *
     * Available options are: etag, last_modified, max_age, s_maxage, private, and public.
     *
     * @param array $options An array of cache options
     *
     * @return Response
     */
    public function setCache(array $options);

    /**
     * Modifies the response so that it conforms to the rules defined for a 304 status code.
     *
     * This sets the status, removes the body, and discards any headers
     * that MUST NOT be included in 304 responses.
     *
     * @return Response
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3.5
     */
    public function setNotModified();

    /**
     * Is response invalid?
     *
     * @return bool
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public function isInvalid();

    /**
     * Is response informative?
     *
     * @return bool
     */
    public function isInformational();

    /**
     * Is response successful?
     *
     * @return bool
     */
    public function isSuccessful();

    /**
     * Is the response a redirect?
     *
     * @return bool
     */
    public function isRedirection();

    /**
     * Is there a client error?
     *
     * @return bool
     */
    public function isClientError();

    /**
     * Was there a server side error?
     *
     * @return bool
     */
    public function isServerError();

    /**
     * Is the response OK?
     *
     * @return bool
     */
    public function isOk();

    /**
     * Is the response forbidden?
     *
     * @return bool
     */
    public function isForbidden();

    /**
     * Is the response a not found error?
     *
     * @return bool
     */
    public function isNotFound();

    /**
     * Is the response a redirect of some form?
     *
     * @param string $location Redirect location
     *
     * @return bool
     */
    public function isRedirect($location = null);

    /**
     * Is the response empty?
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Sends HTTP headers.
     *
     * @return Response
     */
    public function sendHeaders();

    /**
     * Sends content for the current web response.
     *
     * @return Response
     */
    public function sendContent();

    /**
     * Sends HTTP headers and content.
     *
     * @return Response
     */
    public function send();

    /**
     * Cleans or flushes output buffers up to target level.
     *
     * Resulting level can be greater than target level if a non-removable buffer has been encountered.
     *
     * @param int  $targetLevel The target output buffering level
     * @param bool $flush       Whether to flush or clean the buffers
     */
    public static function closeOutputBuffers($targetLevel, $flush);

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
    public function __toString();
}
