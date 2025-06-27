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

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Http\Middleware\Cache\CacheResponseMiddleware;
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
        $middleware = $config->http->middleware->requestReceived;

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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->http->middleware->routeDispatched;

        $container->setSingleton(
            RouteDispatchedHandler::class,
            $handler = new Handler\RouteDispatchedHandler($container)
        );

        $handler->add(...$middleware);
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
        $middleware = $config->http->middleware->throwableCaught;

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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->http->middleware->routeMatched;

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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->http->middleware->routeNotMatched;

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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->http->middleware->sendingResponse;

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
        $config     = $container->getSingleton(Valkyrja::class);
        $middleware = $config->http->middleware->terminated;

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
        $config = $container->getSingleton(Valkyrja::class);

        $container->setSingleton(
            CacheResponseMiddleware::class,
            new CacheResponseMiddleware(
                $container->getSingleton(Filesystem::class),
                $config->app->debugMode
            )
        );
    }
}
