<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Middleware\Provider;

use Override;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ProcessExitingHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Middleware\Handler\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\ProcessExitingHandler;
use Valkyrja\Cli\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

class CliMiddlewareServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the RequestReceivedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishInputReceivedHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(CliConfigContract::class);

        $middleware = $config->inputReceivedMiddleware;

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
        $config = $container->getSingleton(CliConfigContract::class);

        $middleware = $config->routeDispatchedMiddleware;

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
        $config = $container->getSingleton(CliConfigContract::class);

        $middleware = $config->throwableCaughtMiddleware;

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
        $config = $container->getSingleton(CliConfigContract::class);

        $middleware = $config->routeMatchedMiddleware;

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
        $config = $container->getSingleton(CliConfigContract::class);

        $middleware = $config->routeNotMatchedMiddleware;

        $container->setSingleton(
            RouteNotMatchedHandlerContract::class,
            $handler = new RouteNotMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the ResponseSentHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishProcessExitingHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(CliConfigContract::class);

        $middleware = $config->processExitingMiddleware;

        $container->setSingleton(
            ProcessExitingHandlerContract::class,
            $handler = new ProcessExitingHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            InputReceivedHandlerContract::class   => [self::class, 'publishInputReceivedHandler'],
            ThrowableCaughtHandlerContract::class => [self::class, 'publishThrowableCaughtHandler'],
            RouteMatchedHandlerContract::class    => [self::class, 'publishRouteMatchedHandler'],
            RouteNotMatchedHandlerContract::class => [self::class, 'publishRouteNotMatchedHandler'],
            RouteDispatchedHandlerContract::class => [self::class, 'publishRouteDispatchedHandler'],
            ProcessExitingHandlerContract::class  => [self::class, 'publishProcessExitingHandler'],
        ];
    }
}
