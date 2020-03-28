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

namespace Valkyrja\Annotation\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Config.
 *
 * @author Melech Mizrachi
 */
final class Config extends Enum
{
    public const MAP = [
        Annotation::COMMAND                          => AnnotationClass::COMMAND,
        Annotation::LISTENER                         => AnnotationClass::LISTENER,
        Annotation::ROUTE                            => AnnotationClass::ROUTE,
        Annotation::ROUTE_ANY                        => AnnotationClass::ROUTE_ANY,
        Annotation::ROUTE_GET                        => AnnotationClass::ROUTE_GET,
        Annotation::ROUTE_POST                       => AnnotationClass::ROUTE_POST,
        Annotation::ROUTE_HEAD                       => AnnotationClass::ROUTE_HEAD,
        Annotation::ROUTE_PATCH                      => AnnotationClass::ROUTE_PATCH,
        Annotation::ROUTE_PUT                        => AnnotationClass::ROUTE_PUT,
        Annotation::ROUTE_OPTIONS                    => AnnotationClass::ROUTE_OPTIONS,
        Annotation::ROUTE_TRACE                      => AnnotationClass::ROUTE_TRACE,
        Annotation::ROUTE_CONNECT                    => AnnotationClass::ROUTE_CONNECT,
        Annotation::ROUTE_DELETE                     => AnnotationClass::ROUTE_DELETE,
        Annotation::ROUTE_REDIRECT                   => AnnotationClass::ROUTE_REDIRECT,
        Annotation::ROUTE_REDIRECT_ANY               => AnnotationClass::ROUTE_REDIRECT_ANY,
        Annotation::ROUTE_REDIRECT_GET               => AnnotationClass::ROUTE_REDIRECT_GET,
        Annotation::ROUTE_REDIRECT_POST              => AnnotationClass::ROUTE_REDIRECT_POST,
        Annotation::ROUTE_REDIRECT_HEAD              => AnnotationClass::ROUTE_REDIRECT_HEAD,
        Annotation::ROUTE_REDIRECT_PATCH             => AnnotationClass::ROUTE_REDIRECT_PATCH,
        Annotation::ROUTE_REDIRECT_PUT               => AnnotationClass::ROUTE_REDIRECT_PUT,
        Annotation::ROUTE_REDIRECT_OPTIONS           => AnnotationClass::ROUTE_REDIRECT_OPTIONS,
        Annotation::ROUTE_REDIRECT_TRACE             => AnnotationClass::ROUTE_REDIRECT_TRACE,
        Annotation::ROUTE_REDIRECT_CONNECT           => AnnotationClass::ROUTE_REDIRECT_CONNECT,
        Annotation::ROUTE_REDIRECT_DELETE            => AnnotationClass::ROUTE_REDIRECT_DELETE,
        Annotation::ROUTE_REDIRECT_PERMANENT         => AnnotationClass::ROUTE_REDIRECT_PERMANENT,
        Annotation::ROUTE_REDIRECT_PERMANENT_ANY     => AnnotationClass::ROUTE_REDIRECT_PERMANENT_ANY,
        Annotation::ROUTE_REDIRECT_PERMANENT_GET     => AnnotationClass::ROUTE_REDIRECT_PERMANENT_GET,
        Annotation::ROUTE_REDIRECT_PERMANENT_POST    => AnnotationClass::ROUTE_REDIRECT_PERMANENT_POST,
        Annotation::ROUTE_REDIRECT_PERMANENT_HEAD    => AnnotationClass::ROUTE_REDIRECT_PERMANENT_HEAD,
        Annotation::ROUTE_REDIRECT_PERMANENT_PATCH   => AnnotationClass::ROUTE_REDIRECT_PERMANENT_PATCH,
        Annotation::ROUTE_REDIRECT_PERMANENT_PUT     => AnnotationClass::ROUTE_REDIRECT_PERMANENT_PUT,
        Annotation::ROUTE_REDIRECT_PERMANENT_OPTIONS => AnnotationClass::ROUTE_REDIRECT_PERMANENT_OPTIONS,
        Annotation::ROUTE_REDIRECT_PERMANENT_TRACE   => AnnotationClass::ROUTE_REDIRECT_PERMANENT_TRACE,
        Annotation::ROUTE_REDIRECT_PERMANENT_CONNECT => AnnotationClass::ROUTE_REDIRECT_PERMANENT_CONNECT,
        Annotation::ROUTE_REDIRECT_PERMANENT_DELETE  => AnnotationClass::ROUTE_REDIRECT_PERMANENT_DELETE,
        Annotation::ROUTE_SECURE                     => AnnotationClass::ROUTE_SECURE,
        Annotation::ROUTE_SECURE_ANY                 => AnnotationClass::ROUTE_SECURE_ANY,
        Annotation::ROUTE_SECURE_GET                 => AnnotationClass::ROUTE_SECURE_GET,
        Annotation::ROUTE_SECURE_POST                => AnnotationClass::ROUTE_SECURE_POST,
        Annotation::ROUTE_SECURE_HEAD                => AnnotationClass::ROUTE_SECURE_HEAD,
        Annotation::ROUTE_SECURE_PATCH               => AnnotationClass::ROUTE_SECURE_PATCH,
        Annotation::ROUTE_SECURE_PUT                 => AnnotationClass::ROUTE_SECURE_PUT,
        Annotation::ROUTE_SECURE_OPTIONS             => AnnotationClass::ROUTE_SECURE_OPTIONS,
        Annotation::ROUTE_SECURE_TRACE               => AnnotationClass::ROUTE_SECURE_TRACE,
        Annotation::ROUTE_SECURE_CONNECT             => AnnotationClass::ROUTE_SECURE_CONNECT,
        Annotation::ROUTE_SECURE_DELETE              => AnnotationClass::ROUTE_SECURE_DELETE,
        Annotation::SERVICE                          => AnnotationClass::SERVICE,
        Annotation::SERVICE_ALIAS                    => AnnotationClass::SERVICE_ALIAS,
        Annotation::SERVICE_CONTEXT                  => AnnotationClass::SERVICE_CONTEXT,
    ];

    public const ALIASES = [
        Alias::REQUEST_METHOD => AliasClass::REQUEST_METHOD,
        Alias::STATUS_CODE    => AliasClass::STATUS_CODE,
    ];
}
