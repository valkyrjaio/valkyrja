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
use Valkyrja\Application\Data\Config;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Abstract\Provider;
use Valkyrja\Http\Middleware\Data\Contract\ConfigContract;
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
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\ViewThrowableCaughtMiddleware;

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
        ];
    }

    /**
     * Publish the RequestReceivedHandler service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRequestReceivedHandler(ContainerContract $container): void
    {
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->requestReceivedMiddleware
            : [];

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
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->sendingResponseMiddleware
            : [];

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
        $config = $container->getSingleton(Config::class);

        $middleware = $config instanceof ConfigContract
            ? $config->terminatedMiddleware
            : [];

        $container->setSingleton(
            TerminatedHandlerContract::class,
            $handler = new TerminatedHandler($container)
        );

        $handler->add(...$middleware);
    }
}
