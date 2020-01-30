<?php

declare(strict_types=1);

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
use Valkyrja\Annotations\Annotations;
use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Application;
use Valkyrja\Client\Client;
use Valkyrja\Console\Annotations\CommandAnnotations;
use Valkyrja\Console\Console;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Console\Output\Output;
use Valkyrja\Console\Output\OutputFormatter;
use Valkyrja\Container\Annotations\ContainerAnnotations;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Enum\Enum;
use Valkyrja\Env\Env;
use Valkyrja\Events\Annotations\ListenerAnnotations;
use Valkyrja\Events\Events;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Logger\Logger;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Routing\Annotations\RouteAnnotations;
use Valkyrja\Routing\Router;
use Valkyrja\Session\Session;
use Valkyrja\View\View;

/**
 * Enum CoreComponent.
 *
 * @author Melech Mizrachi
 */
final class CoreComponent extends Enum
{
    public const APP                   = Application::class;
    public const ANNOTATIONS           = Annotations::class;
    public const ANNOTATIONS_PARSER    = AnnotationsParser::class;
    public const COMMAND_ANNOTATIONS   = CommandAnnotations::class;
    public const CONFIG                = 'config';
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
    public const LOGGER_INTERFACE      = LoggerInterface::class;
    public const LOGGER                = Logger::class;

    protected const VALUES = [
        self::APP                   => self::APP,
        self::ANNOTATIONS           => self::ANNOTATIONS,
        self::ANNOTATIONS_PARSER    => self::ANNOTATIONS_PARSER,
        self::COMMAND_ANNOTATIONS   => self::COMMAND_ANNOTATIONS,
        self::CONFIG                => self::CONFIG,
        self::CONSOLE               => self::CONSOLE,
        self::CONSOLE_KERNEL        => self::CONSOLE_KERNEL,
        self::CONTAINER             => self::CONTAINER,
        self::CONTAINER_ANNOTATIONS => self::CONTAINER_ANNOTATIONS,
        self::DISPATCHER            => self::DISPATCHER,
        self::ENV                   => self::ENV,
        self::EVENTS                => self::EVENTS,
        self::FILESYSTEM            => self::FILESYSTEM,
        self::INPUT                 => self::INPUT,
        self::OUTPUT                => self::OUTPUT,
        self::OUTPUT_FORMATTER      => self::OUTPUT_FORMATTER,
        self::KERNEL                => self::KERNEL,
        self::LISTENER_ANNOTATIONS  => self::LISTENER_ANNOTATIONS,
        self::PATH_GENERATOR        => self::PATH_GENERATOR,
        self::PATH_PARSER           => self::PATH_PARSER,
        self::REQUEST               => self::REQUEST,
        self::RESPONSE              => self::RESPONSE,
        self::JSON_RESPONSE         => self::JSON_RESPONSE,
        self::REDIRECT_RESPONSE     => self::REDIRECT_RESPONSE,
        self::RESPONSE_BUILDER      => self::RESPONSE_BUILDER,
        self::ROUTER                => self::ROUTER,
        self::ROUTE_ANNOTATIONS     => self::ROUTE_ANNOTATIONS,
        self::SESSION               => self::SESSION,
        self::VIEW                  => self::VIEW,
        self::CLIENT                => self::CLIENT,
        self::LOGGER_INTERFACE      => self::LOGGER_INTERFACE,
        self::LOGGER                => self::LOGGER,
    ];
}
