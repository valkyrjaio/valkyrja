<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Enums;

use Valkyrja\Enum\Enum;

/**
 * Status text constants.
 *
 * @link   http://www.iana.org/assignments/http-status-codes/
 * - Hypertext Transfer Protocol (HTTP) Status Code Registry
 *
 * @author Melech Mizrachi
 */
final class StatusText extends Enum
{
    public const CONTINUE                        = 'Continue';
    public const SWITCHING_PROTOCOLS             = 'Switching Protocols';
    public const PROCESSING                      = 'Processing';
    public const OK                              = 'OK';
    public const CREATED                         = 'Created';
    public const ACCEPTED                        = 'Accepted';
    public const NON_AUTHORITATIVE_INFORMATION   = 'Non-Authoritative Information';
    public const NO_CONTENT                      = 'No Content';
    public const RESET_CONTENT                   = 'Reset Content';
    public const PARTIAL_CONTENT                 = 'Partial Content';
    public const MULTI_STATUS                    = 'Multi-Status';
    public const ALREADY_REPORTED                = 'Already Reported';
    public const IM_USED                         = 'IM Used';
    public const MULTIPLE_CHOICES                = 'Multiple Choices';
    public const MOVED_PERMANENTLY               = 'Moved Permanently';
    public const FOUND                           = 'Found';
    public const SEE_OTHER                       = 'See Other';
    public const NOT_MODIFIED                    = 'Not Modified';
    public const USE_PROXY                       = 'Use Proxy';
    public const TEMPORARY_REDIRECT              = 'Temporary Redirect';
    public const PERMANENT_REDIRECT              = 'Permanent Redirect';
    public const BAD_REQUEST                     = 'Bad Request';
    public const UNAUTHORIZED                    = 'Unauthorized';
    public const PAYMENT_REQUIRED                = 'Payment Required';
    public const FORBIDDEN                       = 'Forbidden';
    public const NOT_FOUND                       = 'Not Found';
    public const METHOD_NOT_ALLOWED              = 'Method Not Allowed';
    public const NOT_ACCEPTABLE                  = 'Not Acceptable';
    public const PROXY_AUTHENTICATION_REQUIRED   = 'Proxy Authentication Required';
    public const REQUEST_TIMEOUT                 = 'Request Timeout';
    public const CONFLICT                        = 'Conflict';
    public const GONE                            = 'Gone';
    public const LENGTH_REQUIRED                 = 'Length Required';
    public const PRECONDITION_FAILED             = 'Precondition Failed';
    public const PAYLOAD_TOO_LARGE               = 'Payload Too Large';
    public const URI_TOO_LONG                    = 'URI Too Long';
    public const UNSUPPORTED_MEDIA_TYPE          = 'Unsupported Media Type';
    public const RANGE_NOT_SATISFIABLE           = 'Range Not Satisfiable';
    public const EXPECTATION_FAILED              = 'Expectation Failed';
    public const I_AM_A_TEAPOT                   = 'I Am A Teapot';
    public const MISDIRECTED_REQUEST             = 'Misdirected Request';
    public const UNPROCESSABLE_ENTITY            = 'Unprocessable Entity';
    public const LOCKED                          = 'Locked';
    public const FAILED_DEPENDENCY               = 'Failed Dependency';
    public const UPGRADE_REQUIRED                = 'Upgrade Required';
    public const PRECONDITION_REQUIRED           = 'Precondition Required';
    public const TOO_MANY_REQUESTS               = 'Too Many Requests';
    public const REQUEST_HEADER_FIELDS_TOO_LARGE = 'Request Header Fields Too Large';
    public const UNAVAILABLE_FOR_LEGAL_REASONS   = 'Unavailable For Legal Reasons';
    public const INTERNAL_SERVER_ERROR           = 'Internal Server Error';
    public const NOT_IMPLEMENTED                 = 'Not Implemented';
    public const BAD_GATEWAY                     = 'Bad Gateway';
    public const SERVICE_UNAVAILABLE             = 'Service Unavailable';
    public const GATEWAY_TIMEOUT                 = 'Gateway Timeout';
    public const HTTP_VERSION_NOT_SUPPORTED      = 'HTTP Version Not Supported';
    public const VARIANT_ALSO_NEGOTIATES         = 'Variant Also Negotiates';
    public const INSUFFICIENT_STORAGE            = 'Insufficient Storage';
    public const LOOP_DETECTED                   = 'Loop Detected';
    public const NOT_EXTENDED                    = 'Not Extended';
    public const NETWORK_AUTHENTICATION_REQUIRED = 'Network Authentication Required';

    protected const VALUES = [
        self::CONTINUE                        => self::CONTINUE,
        self::SWITCHING_PROTOCOLS             => self::SWITCHING_PROTOCOLS,
        self::PROCESSING                      => self::PROCESSING,
        self::OK                              => self::OK,
        self::CREATED                         => self::CREATED,
        self::ACCEPTED                        => self::ACCEPTED,
        self::NON_AUTHORITATIVE_INFORMATION   => self::NON_AUTHORITATIVE_INFORMATION,
        self::NO_CONTENT                      => self::NO_CONTENT,
        self::RESET_CONTENT                   => self::RESET_CONTENT,
        self::PARTIAL_CONTENT                 => self::PARTIAL_CONTENT,
        self::MULTI_STATUS                    => self::MULTI_STATUS,
        self::ALREADY_REPORTED                => self::ALREADY_REPORTED,
        self::IM_USED                         => self::IM_USED,
        self::MULTIPLE_CHOICES                => self::MULTIPLE_CHOICES,
        self::MOVED_PERMANENTLY               => self::MOVED_PERMANENTLY,
        self::FOUND                           => self::FOUND,
        self::SEE_OTHER                       => self::SEE_OTHER,
        self::NOT_MODIFIED                    => self::NOT_MODIFIED,
        self::USE_PROXY                       => self::USE_PROXY,
        self::TEMPORARY_REDIRECT              => self::TEMPORARY_REDIRECT,
        self::PERMANENT_REDIRECT              => self::PERMANENT_REDIRECT,
        self::BAD_REQUEST                     => self::BAD_REQUEST,
        self::UNAUTHORIZED                    => self::UNAUTHORIZED,
        self::PAYMENT_REQUIRED                => self::PAYMENT_REQUIRED,
        self::FORBIDDEN                       => self::FORBIDDEN,
        self::NOT_FOUND                       => self::NOT_FOUND,
        self::METHOD_NOT_ALLOWED              => self::METHOD_NOT_ALLOWED,
        self::NOT_ACCEPTABLE                  => self::NOT_ACCEPTABLE,
        self::PROXY_AUTHENTICATION_REQUIRED   => self::PROXY_AUTHENTICATION_REQUIRED,
        self::REQUEST_TIMEOUT                 => self::REQUEST_TIMEOUT,
        self::CONFLICT                        => self::CONFLICT,
        self::GONE                            => self::GONE,
        self::LENGTH_REQUIRED                 => self::LENGTH_REQUIRED,
        self::PRECONDITION_FAILED             => self::PRECONDITION_FAILED,
        self::PAYLOAD_TOO_LARGE               => self::PAYLOAD_TOO_LARGE,
        self::URI_TOO_LONG                    => self::URI_TOO_LONG,
        self::UNSUPPORTED_MEDIA_TYPE          => self::UNSUPPORTED_MEDIA_TYPE,
        self::RANGE_NOT_SATISFIABLE           => self::RANGE_NOT_SATISFIABLE,
        self::EXPECTATION_FAILED              => self::EXPECTATION_FAILED,
        self::I_AM_A_TEAPOT                   => self::I_AM_A_TEAPOT,
        self::MISDIRECTED_REQUEST             => self::MISDIRECTED_REQUEST,
        self::UNPROCESSABLE_ENTITY            => self::UNPROCESSABLE_ENTITY,
        self::LOCKED                          => self::LOCKED,
        self::FAILED_DEPENDENCY               => self::FAILED_DEPENDENCY,
        self::UPGRADE_REQUIRED                => self::UPGRADE_REQUIRED,
        self::PRECONDITION_REQUIRED           => self::PRECONDITION_REQUIRED,
        self::TOO_MANY_REQUESTS               => self::TOO_MANY_REQUESTS,
        self::REQUEST_HEADER_FIELDS_TOO_LARGE => self::REQUEST_HEADER_FIELDS_TOO_LARGE,
        self::UNAVAILABLE_FOR_LEGAL_REASONS   => self::UNAVAILABLE_FOR_LEGAL_REASONS,
        self::INTERNAL_SERVER_ERROR           => self::INTERNAL_SERVER_ERROR,
        self::NOT_IMPLEMENTED                 => self::NOT_IMPLEMENTED,
        self::BAD_GATEWAY                     => self::BAD_GATEWAY,
        self::SERVICE_UNAVAILABLE             => self::SERVICE_UNAVAILABLE,
        self::GATEWAY_TIMEOUT                 => self::GATEWAY_TIMEOUT,
        self::HTTP_VERSION_NOT_SUPPORTED      => self::HTTP_VERSION_NOT_SUPPORTED,
        self::VARIANT_ALSO_NEGOTIATES         => self::VARIANT_ALSO_NEGOTIATES,
        self::INSUFFICIENT_STORAGE            => self::INSUFFICIENT_STORAGE,
        self::LOOP_DETECTED                   => self::LOOP_DETECTED,
        self::NOT_EXTENDED                    => self::NOT_EXTENDED,
        self::NETWORK_AUTHENTICATION_REQUIRED => self::NETWORK_AUTHENTICATION_REQUIRED,
    ];
}
