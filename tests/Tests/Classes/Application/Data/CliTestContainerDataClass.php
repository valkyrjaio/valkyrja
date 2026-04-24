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

namespace Valkyrja\Tests\Classes\Application\Data;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Provider\CliInteractionServiceProvider;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareServiceProvider;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Collector\Contract\RouteCollectorContract;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Provider\CliRoutingServiceProvider;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForVersionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\RouteNotMatched\CheckCommandForTypoMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\OutputThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Provider\CliServerServiceProvider;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Provider\DispatchServiceProvider;
use Valkyrja\Event\Collection\Contract\ListenerCollectionContract;
use Valkyrja\Event\Collector\Contract\ListenerCollectorContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Event\Dispatcher\Contract\EventDispatcherContract;
use Valkyrja\Event\Provider\EventServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Log\Provider\LogServiceProvider;
use Valkyrja\Tests\Classes\Application\Provider\CliContainerDataProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\CliRoutingDataProviderClass;

final readonly class CliTestContainerDataClass extends ContainerData
{
    public function __construct()
    {
        parent::__construct(
            deferredCallback: [
                ContainerData::class                           => [CliContainerDataProviderClass::class, 'publishData'],
                DispatcherContract::class                      => [DispatchServiceProvider::class, 'publishDispatcher'],
                CliInteractionConfigContract::class            => [CliInteractionServiceProvider::class, 'publishConfig'],
                OutputFactoryContract::class                   => [CliInteractionServiceProvider::class, 'publishOutputFactory'],
                InputReceivedHandlerContract::class            => [CliMiddlewareServiceProvider::class, 'publishInputReceivedHandler'],
                ThrowableCaughtHandlerContract::class          => [CliMiddlewareServiceProvider::class, 'publishThrowableCaughtHandler'],
                RouteMatchedHandlerContract::class             => [CliMiddlewareServiceProvider::class, 'publishRouteMatchedHandler'],
                RouteNotMatchedHandlerContract::class          => [CliMiddlewareServiceProvider::class, 'publishRouteNotMatchedHandler'],
                RouteDispatchedHandlerContract::class          => [CliMiddlewareServiceProvider::class, 'publishRouteDispatchedHandler'],
                ExitedHandlerContract::class                   => [CliMiddlewareServiceProvider::class, 'publishExitedHandler'],
                RouteCollectorContract::class                  => [CliRoutingServiceProvider::class, 'publishAttributeRouteCollector'],
                RouterContract::class                          => [CliRoutingServiceProvider::class, 'publishRouter'],
                RouteCollectionContract::class                 => [CliRoutingServiceProvider::class, 'publishRouteCollection'],
                CliRoutingData::class                          => [CliRoutingDataProviderClass::class, 'publishData'],
                InputHandlerContract::class                    => [CliServerServiceProvider::class, 'publishInputHandler'],
                HelpCommand::class                             => [CliServerServiceProvider::class, 'publishHelpCommand'],
                ListBashCommand::class                         => [CliServerServiceProvider::class, 'publishListBashCommand'],
                ListCommand::class                             => [CliServerServiceProvider::class, 'publishListCommand'],
                VersionCommand::class                          => [CliServerServiceProvider::class, 'publishVersionCommand'],
                LogThrowableCaughtMiddleware::class            => [CliServerServiceProvider::class, 'publishLogThrowableCaughtMiddleware'],
                OutputThrowableCaughtMiddleware::class         => [CliServerServiceProvider::class, 'publishOutputThrowableCaughtMiddleware'],
                CheckForHelpOptionsMiddleware::class           => [CliServerServiceProvider::class, 'publishCheckForHelpOptionsMiddleware'],
                CheckForVersionOptionsMiddleware::class        => [CliServerServiceProvider::class, 'publishCheckForVersionOptionsMiddleware'],
                CheckGlobalInteractionOptionsMiddleware::class => [CliServerServiceProvider::class, 'publishCheckGlobalInteractionOptionsMiddleware'],
                CheckCommandForTypoMiddleware::class           => [CliServerServiceProvider::class, 'publishCheckCommandForTypoMiddleware'],
                ListenerCollectorContract::class               => [EventServiceProvider::class, 'publishAttributesListenerCollector'],
                EventDispatcherContract::class                 => [EventServiceProvider::class, 'publishDispatcher'],
                ListenerCollectionContract::class              => [EventServiceProvider::class, 'publishListenerCollection'],
                EventData::class                               => [EventServiceProvider::class, 'publishData'],
                LoggerContract::class                          => [LogServiceProvider::class, 'publishLogger'],
                PsrLogger::class                               => [LogServiceProvider::class, 'publishPsrLogger'],
                NullLogger::class                              => [LogServiceProvider::class, 'publishNullLogger'],
                LoggerInterface::class                         => [LogServiceProvider::class, 'publishLoggerInterface'],
                Logger::class                                  => [LogServiceProvider::class, 'publishMonolog'],
            ],
        );
    }
}
