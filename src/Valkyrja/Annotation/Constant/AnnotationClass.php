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

use Valkyrja\Console\Enum\AnnotationClass as ConsoleAnnotationClass;
use Valkyrja\Container\Enums\AnnotationClass as ContainerAnnotationClass;
use Valkyrja\Event\Enums\AnnotationClass as EventAnnotationClass;
use Valkyrja\Routing\Enums\AnnotationClass as RoutingAnnotationClass;

/**
 * Constant AnnotationClass.
 *
 * @author Melech Mizrachi
 */
final class AnnotationClass
{
    public const COMMAND                          = ConsoleAnnotationClass::COMMAND;
    public const LISTENER                         = EventAnnotationClass::LISTENER;
    public const ROUTE                            = RoutingAnnotationClass::ROUTE;
    public const ROUTE_ANY                        = RoutingAnnotationClass::ROUTE_ANY;
    public const ROUTE_GET                        = RoutingAnnotationClass::ROUTE_GET;
    public const ROUTE_POST                       = RoutingAnnotationClass::ROUTE_POST;
    public const ROUTE_HEAD                       = RoutingAnnotationClass::ROUTE_HEAD;
    public const ROUTE_PATCH                      = RoutingAnnotationClass::ROUTE_PATCH;
    public const ROUTE_PUT                        = RoutingAnnotationClass::ROUTE_PUT;
    public const ROUTE_OPTIONS                    = RoutingAnnotationClass::ROUTE_OPTIONS;
    public const ROUTE_TRACE                      = RoutingAnnotationClass::ROUTE_TRACE;
    public const ROUTE_CONNECT                    = RoutingAnnotationClass::ROUTE_CONNECT;
    public const ROUTE_DELETE                     = RoutingAnnotationClass::ROUTE_DELETE;
    public const ROUTE_REDIRECT                   = RoutingAnnotationClass::ROUTE_REDIRECT;
    public const ROUTE_REDIRECT_ANY               = RoutingAnnotationClass::ROUTE_REDIRECT_ANY;
    public const ROUTE_REDIRECT_GET               = RoutingAnnotationClass::ROUTE_REDIRECT_GET;
    public const ROUTE_REDIRECT_POST              = RoutingAnnotationClass::ROUTE_REDIRECT_POST;
    public const ROUTE_REDIRECT_HEAD              = RoutingAnnotationClass::ROUTE_REDIRECT_HEAD;
    public const ROUTE_REDIRECT_PATCH             = RoutingAnnotationClass::ROUTE_REDIRECT_PATCH;
    public const ROUTE_REDIRECT_PUT               = RoutingAnnotationClass::ROUTE_REDIRECT_PUT;
    public const ROUTE_REDIRECT_OPTIONS           = RoutingAnnotationClass::ROUTE_REDIRECT_OPTIONS;
    public const ROUTE_REDIRECT_TRACE             = RoutingAnnotationClass::ROUTE_REDIRECT_TRACE;
    public const ROUTE_REDIRECT_CONNECT           = RoutingAnnotationClass::ROUTE_REDIRECT_CONNECT;
    public const ROUTE_REDIRECT_DELETE            = RoutingAnnotationClass::ROUTE_REDIRECT_DELETE;
    public const ROUTE_REDIRECT_PERMANENT         = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT;
    public const ROUTE_REDIRECT_PERMANENT_ANY     = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_ANY;
    public const ROUTE_REDIRECT_PERMANENT_GET     = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_GET;
    public const ROUTE_REDIRECT_PERMANENT_POST    = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_POST;
    public const ROUTE_REDIRECT_PERMANENT_HEAD    = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_HEAD;
    public const ROUTE_REDIRECT_PERMANENT_PATCH   = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_PATCH;
    public const ROUTE_REDIRECT_PERMANENT_PUT     = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_PUT;
    public const ROUTE_REDIRECT_PERMANENT_OPTIONS = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_OPTIONS;
    public const ROUTE_REDIRECT_PERMANENT_TRACE   = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_TRACE;
    public const ROUTE_REDIRECT_PERMANENT_CONNECT = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_CONNECT;
    public const ROUTE_REDIRECT_PERMANENT_DELETE  = RoutingAnnotationClass::ROUTE_REDIRECT_PERMANENT_DELETE;
    public const ROUTE_SECURE                     = RoutingAnnotationClass::ROUTE_SECURE;
    public const ROUTE_SECURE_ANY                 = RoutingAnnotationClass::ROUTE_SECURE_ANY;
    public const ROUTE_SECURE_GET                 = RoutingAnnotationClass::ROUTE_SECURE_GET;
    public const ROUTE_SECURE_POST                = RoutingAnnotationClass::ROUTE_SECURE_POST;
    public const ROUTE_SECURE_HEAD                = RoutingAnnotationClass::ROUTE_SECURE_HEAD;
    public const ROUTE_SECURE_PATCH               = RoutingAnnotationClass::ROUTE_SECURE_PATCH;
    public const ROUTE_SECURE_PUT                 = RoutingAnnotationClass::ROUTE_SECURE_PUT;
    public const ROUTE_SECURE_OPTIONS             = RoutingAnnotationClass::ROUTE_SECURE_OPTIONS;
    public const ROUTE_SECURE_TRACE               = RoutingAnnotationClass::ROUTE_SECURE_TRACE;
    public const ROUTE_SECURE_CONNECT             = RoutingAnnotationClass::ROUTE_SECURE_CONNECT;
    public const ROUTE_SECURE_DELETE              = RoutingAnnotationClass::ROUTE_SECURE_DELETE;
    public const SERVICE                          = ContainerAnnotationClass::SERVICE;
    public const SERVICE_ALIAS                    = ContainerAnnotationClass::SERVICE_ALIAS;
    public const SERVICE_CONTEXT                  = ContainerAnnotationClass::SERVICE_CONTEXT;
}
