<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Enums;

use Psr\Log\LoggerInterface;

use Valkyrja\Contracts\Annotations\Annotations;
use Valkyrja\Contracts\Annotations\AnnotationsParser;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Container\Annotations\ContainerAnnotations;
use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Events\Events;
use Valkyrja\Contracts\Http\Client;
use Valkyrja\Contracts\Http\Request;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Logger\Logger;
use Valkyrja\Contracts\Routing\Annotations\RouteAnnotations;
use Valkyrja\Contracts\Routing\Router;
use Valkyrja\Contracts\View\View;
use Valkyrja\Enum\Enum;

/**
 * Enum CoreComponent
 *
 * @package Valkyrja\Container\Enums
 *
 * @author  Melech Mizrachi
 */
final class CoreComponent extends Enum
{
    public const APP                   = Application::class;
    public const CONTAINER             = Container::class;
    public const EVENTS                = Events::class;
    public const ANNOTATIONS           = Annotations::class;
    public const ANNOTATIONS_PARSER    = AnnotationsParser::class;
    public const CONTAINER_ANNOTATIONS = ContainerAnnotations::class;
    public const REQUEST               = Request::class;
    public const RESPONSE              = Response::class;
    public const RESPONSE_BUILDER      = ResponseBuilder::class;
    public const ROUTER                = Router::class;
    public const ROUTE_ANNOTATIONS     = RouteAnnotations::class;
    public const VIEW                  = View::class;
    public const CLIENT                = Client::class;
    public const LOGGER_INTERFACE      = LoggerInterface::class;
    public const LOGGER                = Logger::class;

    protected const VALUES = [
        self::APP,
        self::CONTAINER,
        self::EVENTS,
        self::ANNOTATIONS,
        self::ANNOTATIONS_PARSER,
        self::CONTAINER_ANNOTATIONS,
        self::REQUEST,
        self::RESPONSE,
        self::RESPONSE_BUILDER,
        self::ROUTER,
        self::ROUTE_ANNOTATIONS,
        self::VIEW,
        self::CLIENT,
        self::LOGGER_INTERFACE,
        self::LOGGER,
    ];
}
