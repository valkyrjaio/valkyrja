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

use Closure;
use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
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
    public static function publishers(): array
    {
        return [
            RequestReceivedHandler::class  => [self::class, 'publishRequestReceivedHandler'],
            ThrowableCaughtHandler::class  => [self::class, 'publishExceptionHandler'],
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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->httpMiddleware->requestReceived;

        $container->setSingleton(
            RequestReceivedHandler::class,
            $handler = new Handler\RequestReceivedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...self::getBeforeMiddleware());
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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->httpMiddleware->routeDispatched;

        $container->setSingleton(
            RouteDispatchedHandler::class,
            $handler = new Handler\RouteDispatchedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...self::getDispatchedMiddleware());
    }

    /**
     * Publish the ExceptionHandler service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishExceptionHandler(Container $container): void
    {
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->httpMiddleware->throwableCaught;

        $container->setSingleton(
            ThrowableCaughtHandler::class,
            $handler = new Handler\ThrowableCaughtHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...self::getExceptionMiddleware());
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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->httpMiddleware->routeMatched;

        $container->setSingleton(
            RouteMatchedHandler::class,
            $handler = new Handler\RouteMatchedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...self::getRouteMatchedMiddleware());
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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->httpMiddleware->routeNotMatched;

        $container->setSingleton(
            RouteNotMatchedHandler::class,
            $handler = new Handler\RouteNotMatchedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...self::getRouteNotMatchedMiddleware());
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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->httpMiddleware->sendingResponse;

        $container->setSingleton(
            SendingResponseHandler::class,
            $handler = new Handler\SendingResponseHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...self::getSendingMiddleware());
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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->httpMiddleware->terminated;

        $container->setSingleton(
            TerminatedHandler::class,
            $handler = new Handler\TerminatedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...self::getTerminatedMiddleware());
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
        $config = $container->getSingleton(Valkyrja::class);

        $container->setSingleton(
            CacheResponseMiddleware::class,
            new CacheResponseMiddleware(
                $container->getSingleton(Filesystem::class),
                $config->app->debug
            )
        );
    }

    /**
     * @return array<int, class-string<RequestReceivedMiddleware>|Closure(Container): RequestReceivedMiddleware>
     */
    protected static function getBeforeMiddleware(): array
    {
        return [];
    }

    /**
     * @return array<int, class-string<RouteDispatchedMiddleware>|Closure(Container): RouteDispatchedMiddleware>
     */
    protected static function getDispatchedMiddleware(): array
    {
        return [];
    }

    /**
     * @return array<int, class-string<ThrowableCaughtMiddleware>|Closure(Container): ThrowableCaughtMiddleware>
     */
    protected static function getExceptionMiddleware(): array
    {
        return [];
    }

    /**
     * @return array<int, class-string<RouteMatchedMiddleware>|Closure(Container): RouteMatchedMiddleware>
     */
    protected static function getRouteMatchedMiddleware(): array
    {
        return [];
    }

    /**
     * @return array<int, class-string<RouteNotMatchedMiddleware>|Closure(Container): RouteNotMatchedMiddleware>
     */
    protected static function getRouteNotMatchedMiddleware(): array
    {
        return [];
    }

    /**
     * @return array<int, class-string<SendingResponseMiddleware>|Closure(Container): SendingResponseMiddleware>
     */
    protected static function getSendingMiddleware(): array
    {
        return [];
    }

    /**
     * @return array<int, class-string<TerminatedMiddleware>|Closure(Container): TerminatedMiddleware>
     */
    protected static function getTerminatedMiddleware(): array
    {
        return [];
    }
}
