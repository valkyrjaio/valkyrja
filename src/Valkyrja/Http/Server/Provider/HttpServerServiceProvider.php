<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Server\Provider;

use Override;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ResponseSentHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Server\Data\Contract\HttpServerConfigContract;
use Valkyrja\Http\Server\Data\HttpServerConfig;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Handler\RequestHandler;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\RequestStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\ResponseStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\SendingResponse\NoCacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\ViewThrowableCaughtMiddleware;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;
use Valkyrja\View\Renderer\Contract\RendererContract;

class HttpServerServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the HttpServerConfig service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof HttpServerConfigContract) {
            $container->setSingleton(HttpServerConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            HttpServerConfigContract::class,
            new HttpServerConfig()
        );
    }

    /**
     * Publish the RequestHandler service.
     */
    public static function publishRequestHandler(ContainerContract $container): void
    {
        $app = $container->getSingleton(ApplicationContract::class);

        $requestReceived   = $container->getSingleton(RequestReceivedHandlerContract::class);
        $exception         = $container->getSingleton(ThrowableCaughtHandlerContract::class);
        $sendingResponse   = $container->getSingleton(SendingResponseHandlerContract::class);
        $responseSent      = $container->getSingleton(ResponseSentHandlerContract::class);

        $exception->add(LogThrowableCaughtMiddleware::class, ViewThrowableCaughtMiddleware::class);

        $container->setSingleton(
            RequestHandlerContract::class,
            new RequestHandler(
                container: $container,
                router: $container->getSingleton(RouterContract::class),
                requestReceivedHandler: $requestReceived,
                throwableCaughtHandler: $exception,
                sendingResponseHandler: $sendingResponse,
                responseSentHandler: $responseSent,
                debug: $app->getDebugMode()
            )
        );
    }

    /**
     * Publish the LogThrowableCaughtMiddleware service.
     */
    public static function publishLogThrowableCaughtMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            LogThrowableCaughtMiddleware::class,
            new LogThrowableCaughtMiddleware(
                logger: $container->getSingleton(LoggerContract::class),
            )
        );
    }

    /**
     * Publish the ViewThrowableCaughtMiddleware service.
     */
    public static function publishViewThrowableCaughtMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            ViewThrowableCaughtMiddleware::class,
            new ViewThrowableCaughtMiddleware(
                viewResponseFactory: $container->getSingleton(ViewResponseFactoryContract::class),
            )
        );
    }

    /**
     * Publish the RequestStructMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRequestStructMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            RequestStructMiddleware::class,
            new RequestStructMiddleware()
        );
    }

    /**
     * Publish the ResponseStructMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishResponseStructMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            ResponseStructMiddleware::class,
            new ResponseStructMiddleware()
        );
    }

    /**
     * Publish the ViewRouteNotMatchedMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishViewRouteNotMatchedMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            ViewRouteNotMatchedMiddleware::class,
            new ViewRouteNotMatchedMiddleware(
                renderer: $container->getSingleton(RendererContract::class),
            )
        );
    }

    /**
     * Publish the CacheResponseMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishCacheResponseMiddleware(ContainerContract $container): void
    {
        $app    = $container->getSingleton(ApplicationContract::class);
        $config = $container->getSingleton(HttpServerConfigContract::class);

        $filePath = $config->responseCacheFilePath
            ?? Directory::frameworkStorageCachePath('response/');

        $container->setSingleton(
            CacheResponseMiddleware::class,
            new CacheResponseMiddleware(
                filePath: $filePath,
                debug: $app->getDebugMode(),
            )
        );
    }

    /**
     * Publish the NoCacheResponseMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishNoCacheResponseMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            NoCacheResponseMiddleware::class,
            new NoCacheResponseMiddleware()
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            HttpServerConfigContract::class      => [self::class, 'publishConfig'],
            RequestHandlerContract::class        => [self::class, 'publishRequestHandler'],
            LogThrowableCaughtMiddleware::class  => [self::class, 'publishLogThrowableCaughtMiddleware'],
            ViewThrowableCaughtMiddleware::class => [self::class, 'publishViewThrowableCaughtMiddleware'],
            RequestStructMiddleware::class       => [self::class, 'publishRequestStructMiddleware'],
            ResponseStructMiddleware::class      => [self::class, 'publishResponseStructMiddleware'],
            ViewRouteNotMatchedMiddleware::class => [self::class, 'publishViewRouteNotMatchedMiddleware'],
            CacheResponseMiddleware::class       => [self::class, 'publishCacheResponseMiddleware'],
            NoCacheResponseMiddleware::class     => [self::class, 'publishNoCacheResponseMiddleware'],
        ];
    }
}
