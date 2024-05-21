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

namespace Valkyrja\Annotation\Constant;

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const ENABLED = false;
    public const MAP     = [
        AnnotationName::COMMAND                          => AnnotationClass::COMMAND,
        AnnotationName::LISTENER                         => AnnotationClass::LISTENER,
        AnnotationName::ROUTE                            => AnnotationClass::ROUTE,
        AnnotationName::ROUTE_ANY                        => AnnotationClass::ROUTE_ANY,
        AnnotationName::ROUTE_GET                        => AnnotationClass::ROUTE_GET,
        AnnotationName::ROUTE_POST                       => AnnotationClass::ROUTE_POST,
        AnnotationName::ROUTE_HEAD                       => AnnotationClass::ROUTE_HEAD,
        AnnotationName::ROUTE_PATCH                      => AnnotationClass::ROUTE_PATCH,
        AnnotationName::ROUTE_PUT                        => AnnotationClass::ROUTE_PUT,
        AnnotationName::ROUTE_OPTIONS                    => AnnotationClass::ROUTE_OPTIONS,
        AnnotationName::ROUTE_TRACE                      => AnnotationClass::ROUTE_TRACE,
        AnnotationName::ROUTE_CONNECT                    => AnnotationClass::ROUTE_CONNECT,
        AnnotationName::ROUTE_DELETE                     => AnnotationClass::ROUTE_DELETE,
        AnnotationName::ROUTE_REDIRECT                   => AnnotationClass::ROUTE_REDIRECT,
        AnnotationName::ROUTE_REDIRECT_ANY               => AnnotationClass::ROUTE_REDIRECT_ANY,
        AnnotationName::ROUTE_REDIRECT_GET               => AnnotationClass::ROUTE_REDIRECT_GET,
        AnnotationName::ROUTE_REDIRECT_POST              => AnnotationClass::ROUTE_REDIRECT_POST,
        AnnotationName::ROUTE_REDIRECT_HEAD              => AnnotationClass::ROUTE_REDIRECT_HEAD,
        AnnotationName::ROUTE_REDIRECT_PATCH             => AnnotationClass::ROUTE_REDIRECT_PATCH,
        AnnotationName::ROUTE_REDIRECT_PUT               => AnnotationClass::ROUTE_REDIRECT_PUT,
        AnnotationName::ROUTE_REDIRECT_OPTIONS           => AnnotationClass::ROUTE_REDIRECT_OPTIONS,
        AnnotationName::ROUTE_REDIRECT_TRACE             => AnnotationClass::ROUTE_REDIRECT_TRACE,
        AnnotationName::ROUTE_REDIRECT_CONNECT           => AnnotationClass::ROUTE_REDIRECT_CONNECT,
        AnnotationName::ROUTE_REDIRECT_DELETE            => AnnotationClass::ROUTE_REDIRECT_DELETE,
        AnnotationName::ROUTE_REDIRECT_PERMANENT         => AnnotationClass::ROUTE_REDIRECT_PERMANENT,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_ANY     => AnnotationClass::ROUTE_REDIRECT_PERMANENT_ANY,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_GET     => AnnotationClass::ROUTE_REDIRECT_PERMANENT_GET,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_POST    => AnnotationClass::ROUTE_REDIRECT_PERMANENT_POST,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_HEAD    => AnnotationClass::ROUTE_REDIRECT_PERMANENT_HEAD,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_PATCH   => AnnotationClass::ROUTE_REDIRECT_PERMANENT_PATCH,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_PUT     => AnnotationClass::ROUTE_REDIRECT_PERMANENT_PUT,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_OPTIONS => AnnotationClass::ROUTE_REDIRECT_PERMANENT_OPTIONS,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_TRACE   => AnnotationClass::ROUTE_REDIRECT_PERMANENT_TRACE,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_CONNECT => AnnotationClass::ROUTE_REDIRECT_PERMANENT_CONNECT,
        AnnotationName::ROUTE_REDIRECT_PERMANENT_DELETE  => AnnotationClass::ROUTE_REDIRECT_PERMANENT_DELETE,
        AnnotationName::ROUTE_SECURE                     => AnnotationClass::ROUTE_SECURE,
        AnnotationName::ROUTE_SECURE_ANY                 => AnnotationClass::ROUTE_SECURE_ANY,
        AnnotationName::ROUTE_SECURE_GET                 => AnnotationClass::ROUTE_SECURE_GET,
        AnnotationName::ROUTE_SECURE_POST                => AnnotationClass::ROUTE_SECURE_POST,
        AnnotationName::ROUTE_SECURE_HEAD                => AnnotationClass::ROUTE_SECURE_HEAD,
        AnnotationName::ROUTE_SECURE_PATCH               => AnnotationClass::ROUTE_SECURE_PATCH,
        AnnotationName::ROUTE_SECURE_PUT                 => AnnotationClass::ROUTE_SECURE_PUT,
        AnnotationName::ROUTE_SECURE_OPTIONS             => AnnotationClass::ROUTE_SECURE_OPTIONS,
        AnnotationName::ROUTE_SECURE_TRACE               => AnnotationClass::ROUTE_SECURE_TRACE,
        AnnotationName::ROUTE_SECURE_CONNECT             => AnnotationClass::ROUTE_SECURE_CONNECT,
        AnnotationName::ROUTE_SECURE_DELETE              => AnnotationClass::ROUTE_SECURE_DELETE,
        AnnotationName::SERVICE                          => AnnotationClass::SERVICE,
        AnnotationName::SERVICE_ALIAS                    => AnnotationClass::SERVICE_ALIAS,
        AnnotationName::SERVICE_CONTEXT                  => AnnotationClass::SERVICE_CONTEXT,
    ];
    public const ALIASES = [
        Alias::REQUEST_METHOD => AliasClass::REQUEST_METHOD,
        Alias::STATUS_CODE    => AliasClass::STATUS_CODE,
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::ENABLED => self::ENABLED,
        CKP::MAP     => self::MAP,
        CKP::ALIASES => self::ALIASES,
    ];
}
