<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Middleware\Provider;

use Override;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ResponseSentHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\ResponseSentHandler;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;

class HttpMiddlewareServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the RequestReceivedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRequestReceivedHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(HttpConfigContract::class);

        $middleware = $config->requestReceivedMiddleware;

        $container->setSingleton(
            RequestReceivedHandlerContract::class,
            $handler = new RequestReceivedHandler($container)
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
        $config = $container->getSingleton(HttpConfigContract::class);

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
        $config = $container->getSingleton(HttpConfigContract::class);

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
        $config = $container->getSingleton(HttpConfigContract::class);

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
        $config = $container->getSingleton(HttpConfigContract::class);

        $middleware = $config->routeNotMatchedMiddleware;

        $container->setSingleton(
            RouteNotMatchedHandlerContract::class,
            $handler = new RouteNotMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the SendingResponseHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishSendingResponseHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(HttpConfigContract::class);

        $middleware = $config->sendingResponseMiddleware;

        $container->setSingleton(
            SendingResponseHandlerContract::class,
            $handler = new SendingResponseHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the ResponseSentHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishResponseSentHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(HttpConfigContract::class);

        $middleware = $config->responseSentMiddleware;

        $container->setSingleton(
            ResponseSentHandlerContract::class,
            $handler = new ResponseSentHandler($container)
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
            RequestReceivedHandlerContract::class => [self::class, 'publishRequestReceivedHandler'],
            ThrowableCaughtHandlerContract::class => [self::class, 'publishThrowableCaughtHandler'],
            RouteMatchedHandlerContract::class    => [self::class, 'publishRouteMatchedHandler'],
            RouteNotMatchedHandlerContract::class => [self::class, 'publishRouteNotMatchedHandler'],
            RouteDispatchedHandlerContract::class => [self::class, 'publishRouteDispatchedHandler'],
            SendingResponseHandlerContract::class => [self::class, 'publishSendingResponseHandler'],
            ResponseSentHandlerContract::class    => [self::class, 'publishResponseSentHandler'],
        ];
    }
}
