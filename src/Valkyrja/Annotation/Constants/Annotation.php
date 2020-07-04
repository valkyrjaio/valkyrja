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

namespace Valkyrja\Annotation\Constants;

use Valkyrja\Console\Annotation\Enums\Annotation as ConsoleAnnotation;
use Valkyrja\Container\Annotation\Enums\Annotation as ContainerAnnotation;
use Valkyrja\Event\Annotation\Enums\Annotation as EventAnnotation;
use Valkyrja\Routing\Annotation\Enums\AnnotationName as RoutingAnnotation;

/**
 * Constant Annotation.
 *
 * @author Melech Mizrachi
 */
final class Annotation
{
    public const COMMAND                          = ConsoleAnnotation::COMMAND;
    public const LISTENER                         = EventAnnotation::LISTENER;
    public const ROUTE                            = RoutingAnnotation::ROUTE;
    public const ROUTE_ANY                        = RoutingAnnotation::ROUTE_ANY;
    public const ROUTE_GET                        = RoutingAnnotation::ROUTE_GET;
    public const ROUTE_POST                       = RoutingAnnotation::ROUTE_POST;
    public const ROUTE_HEAD                       = RoutingAnnotation::ROUTE_HEAD;
    public const ROUTE_PATCH                      = RoutingAnnotation::ROUTE_PATCH;
    public const ROUTE_PUT                        = RoutingAnnotation::ROUTE_PUT;
    public const ROUTE_OPTIONS                    = RoutingAnnotation::ROUTE_OPTIONS;
    public const ROUTE_TRACE                      = RoutingAnnotation::ROUTE_TRACE;
    public const ROUTE_CONNECT                    = RoutingAnnotation::ROUTE_CONNECT;
    public const ROUTE_DELETE                     = RoutingAnnotation::ROUTE_DELETE;
    public const ROUTE_REDIRECT                   = RoutingAnnotation::ROUTE_REDIRECT;
    public const ROUTE_REDIRECT_ANY               = RoutingAnnotation::ROUTE_REDIRECT_ANY;
    public const ROUTE_REDIRECT_GET               = RoutingAnnotation::ROUTE_REDIRECT_GET;
    public const ROUTE_REDIRECT_POST              = RoutingAnnotation::ROUTE_REDIRECT_POST;
    public const ROUTE_REDIRECT_HEAD              = RoutingAnnotation::ROUTE_REDIRECT_HEAD;
    public const ROUTE_REDIRECT_PATCH             = RoutingAnnotation::ROUTE_REDIRECT_PATCH;
    public const ROUTE_REDIRECT_PUT               = RoutingAnnotation::ROUTE_REDIRECT_PUT;
    public const ROUTE_REDIRECT_OPTIONS           = RoutingAnnotation::ROUTE_REDIRECT_OPTIONS;
    public const ROUTE_REDIRECT_TRACE             = RoutingAnnotation::ROUTE_REDIRECT_TRACE;
    public const ROUTE_REDIRECT_CONNECT           = RoutingAnnotation::ROUTE_REDIRECT_CONNECT;
    public const ROUTE_REDIRECT_DELETE            = RoutingAnnotation::ROUTE_REDIRECT_DELETE;
    public const ROUTE_REDIRECT_PERMANENT         = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT;
    public const ROUTE_REDIRECT_PERMANENT_ANY     = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_ANY;
    public const ROUTE_REDIRECT_PERMANENT_GET     = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_GET;
    public const ROUTE_REDIRECT_PERMANENT_POST    = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_POST;
    public const ROUTE_REDIRECT_PERMANENT_HEAD    = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_HEAD;
    public const ROUTE_REDIRECT_PERMANENT_PATCH   = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_PATCH;
    public const ROUTE_REDIRECT_PERMANENT_PUT     = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_PUT;
    public const ROUTE_REDIRECT_PERMANENT_OPTIONS = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_OPTIONS;
    public const ROUTE_REDIRECT_PERMANENT_TRACE   = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_TRACE;
    public const ROUTE_REDIRECT_PERMANENT_CONNECT = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_CONNECT;
    public const ROUTE_REDIRECT_PERMANENT_DELETE  = RoutingAnnotation::ROUTE_REDIRECT_PERMANENT_DELETE;
    public const ROUTE_SECURE                     = RoutingAnnotation::ROUTE_SECURE;
    public const ROUTE_SECURE_ANY                 = RoutingAnnotation::ROUTE_SECURE_ANY;
    public const ROUTE_SECURE_GET                 = RoutingAnnotation::ROUTE_SECURE_GET;
    public const ROUTE_SECURE_POST                = RoutingAnnotation::ROUTE_SECURE_POST;
    public const ROUTE_SECURE_HEAD                = RoutingAnnotation::ROUTE_SECURE_HEAD;
    public const ROUTE_SECURE_PATCH               = RoutingAnnotation::ROUTE_SECURE_PATCH;
    public const ROUTE_SECURE_PUT                 = RoutingAnnotation::ROUTE_SECURE_PUT;
    public const ROUTE_SECURE_OPTIONS             = RoutingAnnotation::ROUTE_SECURE_OPTIONS;
    public const ROUTE_SECURE_TRACE               = RoutingAnnotation::ROUTE_SECURE_TRACE;
    public const ROUTE_SECURE_CONNECT             = RoutingAnnotation::ROUTE_SECURE_CONNECT;
    public const ROUTE_SECURE_DELETE              = RoutingAnnotation::ROUTE_SECURE_DELETE;
    public const SERVICE                          = ContainerAnnotation::SERVICE;
    public const SERVICE_ALIAS                    = ContainerAnnotation::SERVICE_ALIAS;
    public const SERVICE_CONTEXT                  = ContainerAnnotation::SERVICE_CONTEXT;
}
