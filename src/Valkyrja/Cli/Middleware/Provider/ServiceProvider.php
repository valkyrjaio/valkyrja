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

namespace Valkyrja\Cli\Middleware\Provider;

use Override;
use Valkyrja\Application\Data\Config;
use Valkyrja\Cli\Middleware\Data\Contract\ConfigContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForVersionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\RouteNotMatched\CheckCommandForTypoMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\OutputThrowableCaughtMiddleware;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Abstract\Provider;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            InputReceivedHandlerContract::class   => [self::class, 'publishInputReceivedHandler'],
            ThrowableCaughtHandlerContract::class => [self::class, 'publishThrowableCaughtHandler'],
            RouteMatchedHandlerContract::class    => [self::class, 'publishRouteMatchedHandler'],
            RouteNotMatchedHandlerContract::class => [self::class, 'publishRouteNotMatchedHandler'],
            RouteDispatchedHandlerContract::class => [self::class, 'publishRouteDispatchedHandler'],
            ExitedHandlerContract::class          => [self::class, 'publishExitedHandler'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            InputReceivedHandlerContract::class,
            RouteDispatchedHandlerContract::class,
            ThrowableCaughtHandlerContract::class,
            RouteMatchedHandlerContract::class,
            RouteNotMatchedHandlerContract::class,
            ExitedHandlerContract::class,
        ];
    }

    /**
     * Publish the RequestReceivedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishInputReceivedHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->inputReceivedMiddleware
            : [
                CheckForHelpOptionsMiddleware::class,
                CheckForVersionOptionsMiddleware::class,
                CheckGlobalInteractionOptionsMiddleware::class,
            ];

        $container->setSingleton(
            InputReceivedHandlerContract::class,
            $handler = new InputReceivedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteDispatchedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRouteDispatchedHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->routeDispatchedMiddleware
            : [];

        $container->setSingleton(
            RouteDispatchedHandlerContract::class,
            $handler = new RouteDispatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the ThrowableCaughtHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishThrowableCaughtHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->throwableCaughtMiddleware
            : [
                LogThrowableCaughtMiddleware::class,
                OutputThrowableCaughtMiddleware::class,
            ];

        $container->setSingleton(
            ThrowableCaughtHandlerContract::class,
            $handler = new ThrowableCaughtHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteMatchedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRouteMatchedHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->routeMatchedMiddleware
            : [];

        $container->setSingleton(
            RouteMatchedHandlerContract::class,
            $handler = new RouteMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteNotMatchedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRouteNotMatchedHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->routeNotMatchedMiddleware
            : [
                CheckCommandForTypoMiddleware::class,
            ];

        $container->setSingleton(
            RouteNotMatchedHandlerContract::class,
            $handler = new RouteNotMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the TerminatedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishExitedHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->exitedMiddleware
            : [];

        $container->setSingleton(
            ExitedHandlerContract::class,
            $handler = new ExitedHandler($container)
        );

        $handler->add(...$middleware);
    }
}
