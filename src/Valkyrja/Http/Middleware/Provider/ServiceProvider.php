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
use Valkyrja\Config\Config\Config;
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
        /** @var array{middleware: array{requestReceived?: class-string<RequestReceivedMiddleware>[]}} $config */
        $config     = $container->getSingleton(Config::class);
        $middleware = $config['middleware']['requestReceived'] ?? [];

        $container->setSingleton(
            RequestReceivedHandler::class,
            $handler = new Handler\RequestReceivedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...static::getBeforeMiddleware());
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
        /** @var array{middleware: array{routeDispatched?: class-string<RouteDispatchedMiddleware>[]}} $config */
        $config     = $container->getSingleton(Config::class);
        $middleware = $config['middleware']['routeDispatched'] ?? [];

        $container->setSingleton(
            RouteDispatchedHandler::class,
            $handler = new Handler\RouteDispatchedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...static::getDispatchedMiddleware());
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
        /** @var array{middleware: array{throwableCaught?: class-string<ThrowableCaughtMiddleware>[]}} $config */
        $config     = $container->getSingleton(Config::class);
        $middleware = $config['middleware']['throwableCaught'] ?? [];

        $container->setSingleton(
            ThrowableCaughtHandler::class,
            $handler = new Handler\ThrowableCaughtHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...static::getExceptionMiddleware());
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
        /** @var array{middleware: array{routeMatched?: class-string<RouteMatchedMiddleware>[]}} $config */
        $config     = $container->getSingleton(Config::class);
        $middleware = $config['middleware']['routeMatched'] ?? [];

        $container->setSingleton(
            RouteMatchedHandler::class,
            $handler = new Handler\RouteMatchedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...static::getRouteMatchedMiddleware());
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
        /** @var array{middleware: array{routeNotMatched?: class-string<RouteNotMatchedMiddleware>[]}} $config */
        $config     = $container->getSingleton(Config::class);
        $middleware = $config['middleware']['routeNotMatched'] ?? [];

        $container->setSingleton(
            RouteNotMatchedHandler::class,
            $handler = new Handler\RouteNotMatchedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...static::getRouteNotMatchedMiddleware());
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
        /** @var array{middleware: array{sendingResponse?: class-string<SendingResponseMiddleware>[]}} $config */
        $config     = $container->getSingleton(Config::class);
        $middleware = $config['middleware']['sendingResponse'] ?? [];

        $container->setSingleton(
            SendingResponseHandler::class,
            $handler = new Handler\SendingResponseHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...static::getSendingMiddleware());
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
        /** @var array{middleware: array{terminated?: class-string<TerminatedMiddleware>[]}} $config */
        $config     = $container->getSingleton(Config::class);
        $middleware = $config['middleware']['terminated'] ?? [];

        $container->setSingleton(
            TerminatedHandler::class,
            $handler = new Handler\TerminatedHandler($container)
        );

        $handler->add(...$middleware);
        $handler->add(...static::getTerminatedMiddleware());
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
        /** @var array{app: array{debug: bool}} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            CacheResponseMiddleware::class,
            new CacheResponseMiddleware(
                $container->getSingleton(Filesystem::class),
                $config['app']['debug']
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
