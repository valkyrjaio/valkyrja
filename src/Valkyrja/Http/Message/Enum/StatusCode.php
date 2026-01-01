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

namespace Valkyrja\Http\Message\Enum;

use Valkyrja\Http\Message\Constant\StatusText;

/**
 * Enum Status.
 *
 * @see    http://www.iana.org/assignments/http-status-codes/
 * - Hypertext Transfer Protocol (HTTP) Status Code Registry
 */
enum StatusCode: int
{
    case CONTINUE                        = 100;
    case SWITCHING_PROTOCOLS             = 101;
    case PROCESSING                      = 102;
    case EARLY_HINTS                     = 103;
    case OK                              = 200;
    case CREATED                         = 201;
    case ACCEPTED                        = 202;
    case NON_AUTHORITATIVE_INFORMATION   = 203;
    case NO_CONTENT                      = 204;
    case RESET_CONTENT                   = 205;
    case PARTIAL_CONTENT                 = 206;
    case MULTI_STATUS                    = 207;
    case ALREADY_REPORTED                = 208;
    case IM_USED                         = 226;
    case MULTIPLE_CHOICES                = 300;
    case MOVED_PERMANENTLY               = 301;
    case FOUND                           = 302;
    case SEE_OTHER                       = 303;
    case NOT_MODIFIED                    = 304;
    case USE_PROXY                       = 305;
    case TEMPORARY_REDIRECT              = 307;
    case PERMANENT_REDIRECT              = 308;
    case BAD_REQUEST                     = 400;
    case UNAUTHORIZED                    = 401;
    case PAYMENT_REQUIRED                = 402;
    case FORBIDDEN                       = 403;
    case NOT_FOUND                       = 404;
    case METHOD_NOT_ALLOWED              = 405;
    case NOT_ACCEPTABLE                  = 406;
    case PROXY_AUTHENTICATION_REQUIRED   = 407;
    case REQUEST_TIMEOUT                 = 408;
    case CONFLICT                        = 409;
    case GONE                            = 410;
    case LENGTH_REQUIRED                 = 411;
    case PRECONDITION_FAILED             = 412;
    case PAYLOAD_TOO_LARGE               = 413;
    case URI_TOO_LONG                    = 414;
    case UNSUPPORTED_MEDIA_TYPE          = 415;
    case RANGE_NOT_SATISFIABLE           = 416;
    case EXPECTATION_FAILED              = 417;
    case I_AM_A_TEAPOT                   = 418;
    case MISDIRECTED_REQUEST             = 421;
    case UNPROCESSABLE_ENTITY            = 422;
    case LOCKED                          = 423;
    case FAILED_DEPENDENCY               = 424;
    case TOO_EARLY                       = 425;
    case UPGRADE_REQUIRED                = 426;
    case PRECONDITION_REQUIRED           = 428;
    case TOO_MANY_REQUESTS               = 429;
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    case UNAVAILABLE_FOR_LEGAL_REASONS   = 451;
    case INTERNAL_SERVER_ERROR           = 500;
    case NOT_IMPLEMENTED                 = 501;
    case BAD_GATEWAY                     = 502;
    case SERVICE_UNAVAILABLE             = 503;
    case GATEWAY_TIMEOUT                 = 504;
    case HTTP_VERSION_NOT_SUPPORTED      = 505;
    case VARIANT_ALSO_NEGOTIATES         = 506;
    case INSUFFICIENT_STORAGE            = 507;
    case LOOP_DETECTED                   = 508;
    case NOT_EXTENDED_OBSOLETED          = 510;
    case NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * Get the code representation of the status.
     *
     * @return int
     */
    public function code(): int
    {
        return $this->value;
    }

    /**
     * Check if this is a valid redirect.
     *
     * @return bool
     */
    public function isRedirect(): bool
    {
        return $this->value >= self::MULTIPLE_CHOICES->value && $this->value < self::BAD_REQUEST->value;
    }

    /**
     * Check if this is an error code.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->value >= self::INTERNAL_SERVER_ERROR->value;
    }

    /**
     * Get the phrase representation of the status.
     *
     * @return string
     */
    public function asPhrase(): string
    {
        return match ($this) {
            self::CONTINUE                        => StatusText::CONTINUE,
            self::SWITCHING_PROTOCOLS             => StatusText::SWITCHING_PROTOCOLS,
            self::PROCESSING                      => StatusText::PROCESSING,
            self::EARLY_HINTS                     => StatusText::EARLY_HINTS,
            self::OK                              => StatusText::OK,
            self::CREATED                         => StatusText::CREATED,
            self::ACCEPTED                        => StatusText::ACCEPTED,
            self::NON_AUTHORITATIVE_INFORMATION   => StatusText::NON_AUTHORITATIVE_INFORMATION,
            self::NO_CONTENT                      => StatusText::NO_CONTENT,
            self::RESET_CONTENT                   => StatusText::RESET_CONTENT,
            self::PARTIAL_CONTENT                 => StatusText::PARTIAL_CONTENT,
            self::MULTI_STATUS                    => StatusText::MULTI_STATUS,
            self::ALREADY_REPORTED                => StatusText::ALREADY_REPORTED,
            self::IM_USED                         => StatusText::IM_USED,
            self::MULTIPLE_CHOICES                => StatusText::MULTIPLE_CHOICES,
            self::MOVED_PERMANENTLY               => StatusText::MOVED_PERMANENTLY,
            self::FOUND                           => StatusText::FOUND,
            self::SEE_OTHER                       => StatusText::SEE_OTHER,
            self::NOT_MODIFIED                    => StatusText::NOT_MODIFIED,
            self::USE_PROXY                       => StatusText::USE_PROXY,
            self::TEMPORARY_REDIRECT              => StatusText::TEMPORARY_REDIRECT,
            self::PERMANENT_REDIRECT              => StatusText::PERMANENT_REDIRECT,
            self::BAD_REQUEST                     => StatusText::BAD_REQUEST,
            self::UNAUTHORIZED                    => StatusText::UNAUTHORIZED,
            self::PAYMENT_REQUIRED                => StatusText::PAYMENT_REQUIRED,
            self::FORBIDDEN                       => StatusText::FORBIDDEN,
            self::NOT_FOUND                       => StatusText::NOT_FOUND,
            self::METHOD_NOT_ALLOWED              => StatusText::METHOD_NOT_ALLOWED,
            self::NOT_ACCEPTABLE                  => StatusText::NOT_ACCEPTABLE,
            self::PROXY_AUTHENTICATION_REQUIRED   => StatusText::PROXY_AUTHENTICATION_REQUIRED,
            self::REQUEST_TIMEOUT                 => StatusText::REQUEST_TIMEOUT,
            self::CONFLICT                        => StatusText::CONFLICT,
            self::GONE                            => StatusText::GONE,
            self::LENGTH_REQUIRED                 => StatusText::LENGTH_REQUIRED,
            self::PRECONDITION_FAILED             => StatusText::PRECONDITION_FAILED,
            self::PAYLOAD_TOO_LARGE               => StatusText::PAYLOAD_TOO_LARGE,
            self::URI_TOO_LONG                    => StatusText::URI_TOO_LONG,
            self::UNSUPPORTED_MEDIA_TYPE          => StatusText::UNSUPPORTED_MEDIA_TYPE,
            self::RANGE_NOT_SATISFIABLE           => StatusText::RANGE_NOT_SATISFIABLE,
            self::EXPECTATION_FAILED              => StatusText::EXPECTATION_FAILED,
            self::I_AM_A_TEAPOT                   => StatusText::I_AM_A_TEAPOT,
            self::MISDIRECTED_REQUEST             => StatusText::MISDIRECTED_REQUEST,
            self::UNPROCESSABLE_ENTITY            => StatusText::UNPROCESSABLE_ENTITY,
            self::LOCKED                          => StatusText::LOCKED,
            self::FAILED_DEPENDENCY               => StatusText::FAILED_DEPENDENCY,
            self::TOO_EARLY                       => StatusText::TOO_EARLY,
            self::UPGRADE_REQUIRED                => StatusText::UPGRADE_REQUIRED,
            self::PRECONDITION_REQUIRED           => StatusText::PRECONDITION_REQUIRED,
            self::TOO_MANY_REQUESTS               => StatusText::TOO_MANY_REQUESTS,
            self::REQUEST_HEADER_FIELDS_TOO_LARGE => StatusText::REQUEST_HEADER_FIELDS_TOO_LARGE,
            self::UNAVAILABLE_FOR_LEGAL_REASONS   => StatusText::UNAVAILABLE_FOR_LEGAL_REASONS,
            self::INTERNAL_SERVER_ERROR           => StatusText::INTERNAL_SERVER_ERROR,
            self::NOT_IMPLEMENTED                 => StatusText::NOT_IMPLEMENTED,
            self::BAD_GATEWAY                     => StatusText::BAD_GATEWAY,
            self::SERVICE_UNAVAILABLE             => StatusText::SERVICE_UNAVAILABLE,
            self::GATEWAY_TIMEOUT                 => StatusText::GATEWAY_TIMEOUT,
            self::HTTP_VERSION_NOT_SUPPORTED      => StatusText::HTTP_VERSION_NOT_SUPPORTED,
            self::VARIANT_ALSO_NEGOTIATES         => StatusText::VARIANT_ALSO_NEGOTIATES,
            self::INSUFFICIENT_STORAGE            => StatusText::INSUFFICIENT_STORAGE,
            self::LOOP_DETECTED                   => StatusText::LOOP_DETECTED,
            self::NOT_EXTENDED_OBSOLETED          => StatusText::NOT_EXTENDED_OBSOLETED,
            self::NETWORK_AUTHENTICATION_REQUIRED => StatusText::NETWORK_AUTHENTICATION_REQUIRED,
        };
    }
}
