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
use Valkyrja\Application\Env;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Filesystem\Manager\Contract\Filesystem;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware as HttpRequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware as HttpRouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware as HttpRouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware as HttpRouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware as HttpSendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware as HttpTerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware as HttpThrowableCaughtMiddleware;
use Valkyrja\Http\Middleware\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            RequestReceivedHandler::class  => [self::class, 'publishRequestReceivedHandler'],
            ThrowableCaughtHandler::class  => [self::class, 'publishThrowableCaughtHandler'],
            RouteMatchedHandler::class     => [self::class, 'publishRouteMatchedHandler'],
            RouteNotMatchedHandler::class  => [self::class, 'publishRouteNotMatchedHandler'],
            RouteDispatchedHandler::class  => [self::class, 'publishRouteDispatchedHandler'],
            SendingResponseHandler::class  => [self::class, 'publishSendingResponseHandler'],
            TerminatedHandler::class       => [self::class, 'publishTerminatedHandler'],
            CacheResponseMiddleware::class => [self::class, 'publishCacheResponseMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            RequestReceivedHandler::class,
            RouteDispatchedHandler::class,
            ThrowableCaughtHandler::class,
            RouteMatchedHandler::class,
            RouteNotMatchedHandler::class,
            SendingResponseHandler::class,
            TerminatedHandler::class,
            CacheResponseMiddleware::class,
        ];
    }

    /**
     * Publish the RequestReceivedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRequestReceivedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpRequestReceivedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_REQUEST_RECEIVED;

        $container->setSingleton(
            RequestReceivedHandler::class,
            $handler = new Handler\RequestReceivedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteDispatchedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRouteDispatchedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpRouteDispatchedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_DISPATCHED;

        $container->setSingleton(
            RouteDispatchedHandler::class,
            $handler = new Handler\RouteDispatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the ThrowableCaughtHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishThrowableCaughtHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpThrowableCaughtMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_THROWABLE_CAUGHT;

        $container->setSingleton(
            ThrowableCaughtHandler::class,
            $handler = new Handler\ThrowableCaughtHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteMatchedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRouteMatchedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpRouteMatchedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_MATCHED;

        $container->setSingleton(
            RouteMatchedHandler::class,
            $handler = new Handler\RouteMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the RouteNotMatchedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRouteNotMatchedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpRouteNotMatchedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED;

        $container->setSingleton(
            RouteNotMatchedHandler::class,
            $handler = new Handler\RouteNotMatchedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the SendingResponseHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSendingResponseHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpSendingResponseMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_SENDING_RESPONSE;

        $container->setSingleton(
            SendingResponseHandler::class,
            $handler = new Handler\SendingResponseHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the TerminatedHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishTerminatedHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<HttpTerminatedMiddleware>[] $middleware */
        $middleware = $env::HTTP_MIDDLEWARE_TERMINATED;

        $container->setSingleton(
            TerminatedHandler::class,
            $handler = new Handler\TerminatedHandler($container)
        );

        $handler->add(...$middleware);
    }

    /**
     * Publish the CacheResponseMiddleware service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCacheResponseMiddleware(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;

        $container->setSingleton(
            CacheResponseMiddleware::class,
            new CacheResponseMiddleware(
                $container->getSingleton(Filesystem::class),
                $debugMode
            )
        );
    }
}
