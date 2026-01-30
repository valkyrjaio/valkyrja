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

namespace Valkyrja\Http\Middleware\Provider;

use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Handler\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Constant\AllowedClasses;
use Valkyrja\Http\Routing\Middleware\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware as HttpLogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            RequestReceivedHandlerContract::class => [self::class, 'publishRequestReceivedHandler'],
            ThrowableCaughtHandlerContract::class => [self::class, 'publishThrowableCaughtHandler'],
            RouteMatchedHandlerContract::class    => [self::class, 'publishRouteMatchedHandler'],
            RouteNotMatchedHandlerContract::class => [self::class, 'publishRouteNotMatchedHandler'],
            RouteDispatchedHandlerContract::class => [self::class, 'publishRouteDispatchedHandler'],
            SendingResponseHandlerContract::class => [self::class, 'publishSendingResponseHandler'],
            TerminatedHandlerContract::class      => [self::class, 'publishTerminatedHandler'],
            CacheResponseMiddleware::class        => [self::class, 'publishCacheResponseMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            RequestReceivedHandlerContract::class,
            RouteDispatchedHandlerContract::class,
            ThrowableCaughtHandlerContract::class,
            RouteMatchedHandlerContract::class,
            RouteNotMatchedHandlerContract::class,
            SendingResponseHandlerContract::class,
            TerminatedHandlerContract::class,
            CacheResponseMiddleware::class,
        ];
    }

    /**
     * Publish the RequestReceivedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRequestReceivedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<RequestReceivedMiddlewareContract>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_REQUEST_RECEIVED
            ?? [];

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
        $env = $container->getSingleton(Env::class);
        /** @var class-string<RouteDispatchedMiddlewareContract>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_DISPATCHED
            ?? [];

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
        $env = $container->getSingleton(Env::class);
        /** @var class-string<ThrowableCaughtMiddlewareContract>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_THROWABLE_CAUGHT
            ?? [
                HttpLogThrowableCaughtMiddleware::class,
                ViewThrowableCaughtMiddleware::class,
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
        $env = $container->getSingleton(Env::class);
        /** @var class-string<RouteMatchedMiddlewareContract>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_MATCHED
            ?? [];

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
        $env = $container->getSingleton(Env::class);
        /** @var class-string<RouteNotMatchedMiddlewareContract>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED
            ?? [
                ViewRouteNotMatchedMiddleware::class,
            ];

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
        $env = $container->getSingleton(Env::class);
        /** @var class-string<SendingResponseMiddlewareContract>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_SENDING_RESPONSE
            ?? [];

        $container->setSingleton(
            SendingResponseHandlerContract::class,
            $handler = new SendingResponseHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the TerminatedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishTerminatedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<TerminatedMiddlewareContract>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_TERMINATED
            ?? [];

        $container->setSingleton(
            TerminatedHandlerContract::class,
            $handler = new TerminatedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the CacheResponseMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishCacheResponseMiddleware(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;
        /** @var class-string[] $allowedClasses */
        $allowedClasses = $env::HTTP_MIDDLEWARE_NO_CACHE_ALLOWED_CLASSES
            ?? AllowedClasses::CACHE_RESPONSE_MIDDLEWARE;

        $container->setSingleton(
            CacheResponseMiddleware::class,
            new CacheResponseMiddleware(
                filesystem: $container->getSingleton(FilesystemContract::class),
                debug: $debugMode,
                allowedClasses: $allowedClasses
            )
        );
    }
}
