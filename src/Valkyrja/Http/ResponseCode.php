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

use Valkyrja\Enum\Enum;

/**
 * Final Class ResponseCode
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
final class ResponseCode extends Enum
{
    /**
     * Status code constants.
     *
     * The list of codes is complete according to the
     * {@link http://www.iana.org/assignments/http-status-codes/ Hypertext Transfer Protocol (HTTP) Status Code Registry}
     * (last updated 2016-03-01).
     *
     * Unless otherwise noted, the status code is defined in RFC2616.
     *
     * @constant
     */
    public const HTTP_CONTINUE                                                  = 100;
    public const HTTP_SWITCHING_PROTOCOLS                                       = 101;
    public const HTTP_PROCESSING                                                = 102; // RFC2518
    public const HTTP_OK                                                        = 200;
    public const HTTP_CREATED                                                   = 201;
    public const HTTP_ACCEPTED                                                  = 202;
    public const HTTP_NON_AUTHORITATIVE_INFORMATION                             = 203;
    public const HTTP_NO_CONTENT                                                = 204;
    public const HTTP_RESET_CONTENT                                             = 205;
    public const HTTP_PARTIAL_CONTENT                                           = 206;
    public const HTTP_MULTI_STATUS                                              = 207; // RFC4918
    public const HTTP_ALREADY_REPORTED                                          = 208; // RFC5842
    public const HTTP_IM_USED                                                   = 226; // RFC3229
    public const HTTP_MULTIPLE_CHOICES                                          = 300;
    public const HTTP_MOVED_PERMANENTLY                                         = 301;
    public const HTTP_FOUND                                                     = 302;
    public const HTTP_SEE_OTHER                                                 = 303;
    public const HTTP_NOT_MODIFIED                                              = 304;
    public const HTTP_USE_PROXY                                                 = 305;
    public const HTTP_RESERVED                                                  = 306;
    public const HTTP_TEMPORARY_REDIRECT                                        = 307;
    public const HTTP_PERMANENTLY_REDIRECT                                      = 308; // RFC7238
    public const HTTP_BAD_REQUEST                                               = 400;
    public const HTTP_UNAUTHORIZED                                              = 401;
    public const HTTP_PAYMENT_REQUIRED                                          = 402;
    public const HTTP_FORBIDDEN                                                 = 403;
    public const HTTP_NOT_FOUND                                                 = 404;
    public const HTTP_METHOD_NOT_ALLOWED                                        = 405;
    public const HTTP_NOT_ACCEPTABLE                                            = 406;
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED                             = 407;
    public const HTTP_REQUEST_TIMEOUT                                           = 408;
    public const HTTP_CONFLICT                                                  = 409;
    public const HTTP_GONE                                                      = 410;
    public const HTTP_LENGTH_REQUIRED                                           = 411;
    public const HTTP_PRECONDITION_FAILED                                       = 412;
    public const HTTP_REQUEST_ENTITY_TOO_LARGE                                  = 413;
    public const HTTP_REQUEST_URI_TOO_LONG                                      = 414;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE                                    = 415;
    public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE                           = 416;
    public const HTTP_EXPECTATION_FAILED                                        = 417;
    public const HTTP_I_AM_A_TEAPOT                                             = 418; // RFC2324
    public const HTTP_MISDIRECTED_REQUEST                                       = 421; // RFC7540
    public const HTTP_UNPROCESSABLE_ENTITY                                      = 422; // RFC4918
    public const HTTP_LOCKED                                                    = 423; // RFC4918
    public const HTTP_FAILED_DEPENDENCY                                         = 424; // RFC4918
    public const HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425; // RFC2817
    public const HTTP_UPGRADE_REQUIRED                                          = 426; // RFC2817
    public const HTTP_PRECONDITION_REQUIRED                                     = 428; // RFC6585
    public const HTTP_TOO_MANY_REQUESTS                                         = 429; // RFC6585
    public const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE                           = 431; // RFC6585
    public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS                             = 451;
    public const HTTP_INTERNAL_SERVER_ERROR                                     = 500;
    public const HTTP_NOT_IMPLEMENTED                                           = 501;
    public const HTTP_BAD_GATEWAY                                               = 502;
    public const HTTP_SERVICE_UNAVAILABLE                                       = 503;
    public const HTTP_GATEWAY_TIMEOUT                                           = 504;
    public const HTTP_VERSION_NOT_SUPPORTED                                     = 505;
    public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL                      = 506; // RFC2295
    public const HTTP_INSUFFICIENT_STORAGE                                      = 507; // RFC4918
    public const HTTP_LOOP_DETECTED                                             = 508; // RFC5842
    public const HTTP_NOT_EXTENDED                                              = 510; // RFC2774
    public const HTTP_NETWORK_AUTHENTICATION_REQUIRED                           = 511; // RFC6585

    /**
     * Status code texts.
     *
     * @constant array
     */
    public const STATUS_TEXTS = [
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
}
