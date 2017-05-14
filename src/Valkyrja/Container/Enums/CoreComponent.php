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

use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Valkyrja\Contracts\Annotations\Annotations;
use Valkyrja\Contracts\Annotations\AnnotationsParser;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Config\Config;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Contracts\Console\Annotations\CommandAnnotations;
use Valkyrja\Contracts\Console\Console;
use Valkyrja\Contracts\Console\Input\Input;
use Valkyrja\Contracts\Console\Kernel as ConsoleKernel;
use Valkyrja\Contracts\Console\Output\Output;
use Valkyrja\Contracts\Console\Output\OutputFormatter;
use Valkyrja\Contracts\Container\Annotations\ContainerAnnotations;
use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Dispatcher\Dispatcher;
use Valkyrja\Contracts\Events\Annotations\ListenerAnnotations;
use Valkyrja\Contracts\Events\Events;
use Valkyrja\Contracts\Filesystem\Filesystem;
use Valkyrja\Contracts\Http\Client;
use Valkyrja\Contracts\Http\JsonResponse;
use Valkyrja\Contracts\Http\Kernel;
use Valkyrja\Contracts\Http\RedirectResponse;
use Valkyrja\Contracts\Http\Request;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Logger\Logger;
use Valkyrja\Contracts\Path\PathGenerator;
use Valkyrja\Contracts\Path\PathParser;
use Valkyrja\Contracts\Routing\Annotations\RouteAnnotations;
use Valkyrja\Contracts\Routing\Router;
use Valkyrja\Contracts\Session\Session;
use Valkyrja\Contracts\View\View;
use Valkyrja\Enum\Enum;

/**
 * Enum CoreComponent.
 *
 *
 * @author  Melech Mizrachi
 */
final class CoreComponent extends Enum
{
    public const APP                   = Application::class;
    public const ANNOTATIONS           = Annotations::class;
    public const ANNOTATIONS_PARSER    = AnnotationsParser::class;
    public const COMMAND_ANNOTATIONS   = CommandAnnotations::class;
    public const CONFIG                = Config::class;
    public const CONSOLE               = Console::class;
    public const CONSOLE_KERNEL        = ConsoleKernel::class;
    public const CONTAINER             = Container::class;
    public const CONTAINER_ANNOTATIONS = ContainerAnnotations::class;
    public const DISPATCHER            = Dispatcher::class;
    public const ENV                   = Env::class;
    public const EVENTS                = Events::class;
    public const FILESYSTEM            = Filesystem::class;
    public const INPUT                 = Input::class;
    public const OUTPUT                = Output::class;
    public const OUTPUT_FORMATTER      = OutputFormatter::class;
    public const KERNEL                = Kernel::class;
    public const LISTENER_ANNOTATIONS  = ListenerAnnotations::class;
    public const PATH_GENERATOR        = PathGenerator::class;
    public const PATH_PARSER           = PathParser::class;
    public const REQUEST               = Request::class;
    public const RESPONSE              = Response::class;
    public const JSON_RESPONSE         = JsonResponse::class;
    public const REDIRECT_RESPONSE     = RedirectResponse::class;
    public const RESPONSE_BUILDER      = ResponseBuilder::class;
    public const ROUTER                = Router::class;
    public const ROUTE_ANNOTATIONS     = RouteAnnotations::class;
    public const SESSION               = Session::class;
    public const VIEW                  = View::class;
    public const CLIENT                = Client::class;
    public const STREAM_HANDLER        = StreamHandler::class;
    public const LOGGER_INTERFACE      = LoggerInterface::class;
    public const LOGGER                = Logger::class;

    protected const VALUES = [
        self::APP,
        self::ANNOTATIONS,
        self::ANNOTATIONS_PARSER,
        self::CONFIG,
        self::COMMAND_ANNOTATIONS,
        self::CONSOLE,
        self::CONTAINER,
        self::CONTAINER_ANNOTATIONS,
        self::DISPATCHER,
        self::ENV,
        self::EVENTS,
        self::FILESYSTEM,
        self::INPUT,
        self::OUTPUT,
        self::OUTPUT_FORMATTER,
        self::KERNEL,
        self::LISTENER_ANNOTATIONS,
        self::PATH_GENERATOR,
        self::PATH_PARSER,
        self::REQUEST,
        self::RESPONSE,
        self::JSON_RESPONSE,
        self::REDIRECT_RESPONSE,
        self::RESPONSE_BUILDER,
        self::ROUTER,
        self::ROUTE_ANNOTATIONS,
        self::SESSION,
        self::VIEW,
        self::CLIENT,
        self::STREAM_HANDLER,
        self::LOGGER_INTERFACE,
        self::LOGGER,
    ];
}
