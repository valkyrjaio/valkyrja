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
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract as HttpRequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract as HttpRouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract as HttpRouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract as HttpRouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract as HttpSendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract as HttpTerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract as HttpThrowableCaughtMiddleware;
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
     *
     * @return void
     */
    public static function publishRequestReceivedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpRequestReceivedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_REQUEST_RECEIVED;

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
     *
     * @return void
     */
    public static function publishRouteDispatchedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpRouteDispatchedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_DISPATCHED;

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
     *
     * @return void
     */
    public static function publishThrowableCaughtHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpThrowableCaughtMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_THROWABLE_CAUGHT;

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
     *
     * @return void
     */
    public static function publishRouteMatchedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpRouteMatchedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_MATCHED;

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
     *
     * @return void
     */
    public static function publishRouteNotMatchedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpRouteNotMatchedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED;

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
     *
     * @return void
     */
    public static function publishSendingResponseHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpSendingResponseMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_SENDING_RESPONSE;

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
     *
     * @return void
     */
    public static function publishTerminatedHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpTerminatedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_TERMINATED;

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
     *
     * @return void
     */
    public static function publishCacheResponseMiddleware(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;

        $container->setSingleton(
            CacheResponseMiddleware::class,
            new CacheResponseMiddleware(
                $container->getSingleton(FilesystemContract::class),
                $debugMode
            )
        );
    }
}
