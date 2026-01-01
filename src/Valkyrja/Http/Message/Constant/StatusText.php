<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Message\Constant;

/**
 * @see http://www.iana.org/assignments/http-status-codes/
 * - Hypertext Transfer Protocol (HTTP) Status Code Registry
 */
final class StatusText
{
    public const string CONTINUE                        = 'Continue';
    public const string SWITCHING_PROTOCOLS             = 'Switching Protocols';
    public const string PROCESSING                      = 'Processing';
    public const string EARLY_HINTS                     = 'Early Hints';
    public const string OK                              = 'OK';
    public const string CREATED                         = 'Created';
    public const string ACCEPTED                        = 'Accepted';
    public const string NON_AUTHORITATIVE_INFORMATION   = 'Non-Authoritative Information';
    public const string NO_CONTENT                      = 'No Content';
    public const string RESET_CONTENT                   = 'Reset Content';
    public const string PARTIAL_CONTENT                 = 'Partial Content';
    public const string MULTI_STATUS                    = 'Multi-Status';
    public const string ALREADY_REPORTED                = 'Already Reported';
    public const string IM_USED                         = 'IM Used';
    public const string MULTIPLE_CHOICES                = 'Multiple Choices';
    public const string MOVED_PERMANENTLY               = 'Moved Permanently';
    public const string FOUND                           = 'Found';
    public const string SEE_OTHER                       = 'See Other';
    public const string NOT_MODIFIED                    = 'Not Modified';
    public const string USE_PROXY                       = 'Use Proxy';
    public const string TEMPORARY_REDIRECT              = 'Temporary Redirect';
    public const string PERMANENT_REDIRECT              = 'Permanent Redirect';
    public const string BAD_REQUEST                     = 'Bad Request';
    public const string UNAUTHORIZED                    = 'Unauthorized';
    public const string PAYMENT_REQUIRED                = 'Payment Required';
    public const string FORBIDDEN                       = 'Forbidden';
    public const string NOT_FOUND                       = 'Not Found';
    public const string METHOD_NOT_ALLOWED              = 'Method Not Allowed';
    public const string NOT_ACCEPTABLE                  = 'Not Acceptable';
    public const string PROXY_AUTHENTICATION_REQUIRED   = 'Proxy Authentication Required';
    public const string REQUEST_TIMEOUT                 = 'Request Timeout';
    public const string CONFLICT                        = 'Conflict';
    public const string GONE                            = 'Gone';
    public const string LENGTH_REQUIRED                 = 'Length Required';
    public const string PRECONDITION_FAILED             = 'Precondition Failed';
    public const string PAYLOAD_TOO_LARGE               = 'Payload Too Large';
    public const string URI_TOO_LONG                    = 'URI Too Long';
    public const string UNSUPPORTED_MEDIA_TYPE          = 'Unsupported Media Type';
    public const string RANGE_NOT_SATISFIABLE           = 'Range Not Satisfiable';
    public const string EXPECTATION_FAILED              = 'Expectation Failed';
    public const string I_AM_A_TEAPOT                   = 'I Am A Teapot';
    public const string MISDIRECTED_REQUEST             = 'Misdirected Request';
    public const string UNPROCESSABLE_ENTITY            = 'Unprocessable Entity';
    public const string LOCKED                          = 'Locked';
    public const string FAILED_DEPENDENCY               = 'Failed Dependency';
    public const string TOO_EARLY                       = 'Too Early';
    public const string UPGRADE_REQUIRED                = 'Upgrade Required';
    public const string PRECONDITION_REQUIRED           = 'Precondition Required';
    public const string TOO_MANY_REQUESTS               = 'Too Many Requests';
    public const string REQUEST_HEADER_FIELDS_TOO_LARGE = 'Request Header Fields Too Large';
    public const string UNAVAILABLE_FOR_LEGAL_REASONS   = 'Unavailable For Legal Reasons';
    public const string INTERNAL_SERVER_ERROR           = 'Internal Server Error';
    public const string NOT_IMPLEMENTED                 = 'Not Implemented';
    public const string BAD_GATEWAY                     = 'Bad Gateway';
    public const string SERVICE_UNAVAILABLE             = 'Service Unavailable';
    public const string GATEWAY_TIMEOUT                 = 'Gateway Timeout';
    public const string HTTP_VERSION_NOT_SUPPORTED      = 'HTTP Version Not Supported';
    public const string VARIANT_ALSO_NEGOTIATES         = 'Variant Also Negotiates';
    public const string INSUFFICIENT_STORAGE            = 'Insufficient Storage';
    public const string LOOP_DETECTED                   = 'Loop Detected';
    public const string NOT_EXTENDED_OBSOLETED          = 'Not Extended';
    public const string NETWORK_AUTHENTICATION_REQUIRED = 'Network Authentication Required';
}
