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

namespace Valkyrja\Http\Server\Provider;

use Override;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Handler\RequestHandler;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\RequestStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\ResponseStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\ViewThrowableCaughtMiddleware;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract;
use Valkyrja\View\Renderer\Contract\RendererContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            RequestHandlerContract::class        => [self::class, 'publishRequestHandler'],
            LogThrowableCaughtMiddleware::class  => [self::class, 'publishLogThrowableCaughtMiddleware'],
            ViewThrowableCaughtMiddleware::class => [self::class, 'publishViewThrowableCaughtMiddleware'],
            RequestStructMiddleware::class       => [self::class, 'publishRequestStructMiddleware'],
            ResponseStructMiddleware::class      => [self::class, 'publishResponseStructMiddleware'],
            ViewRouteNotMatchedMiddleware::class => [self::class, 'publishViewRouteNotMatchedMiddleware'],
            CacheResponseMiddleware::class       => [self::class, 'publishCacheResponseMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            RequestHandlerContract::class,
            LogThrowableCaughtMiddleware::class,
            ViewThrowableCaughtMiddleware::class,
            RequestStructMiddleware::class,
            ResponseStructMiddleware::class,
            ViewRouteNotMatchedMiddleware::class,
            CacheResponseMiddleware::class,
        ];
    }

    /**
     * Publish the RequestHandler service.
     */
    public static function publishRequestHandler(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;

        $requestReceived = $container->getSingleton(RequestReceivedHandlerContract::class);
        $exception       = $container->getSingleton(ThrowableCaughtHandlerContract::class);
        $sendingResponse = $container->getSingleton(SendingResponseHandlerContract::class);
        $terminated      = $container->getSingleton(TerminatedHandlerContract::class);

        $exception->add(LogThrowableCaughtMiddleware::class, ViewThrowableCaughtMiddleware::class);

        $container->setSingleton(
            RequestHandlerContract::class,
            new RequestHandler(
                container: $container,
                router: $container->getSingleton(RouterContract::class),
                requestReceivedHandler: $requestReceived,
                throwableCaughtHandler: $exception,
                sendingResponseHandler: $sendingResponse,
                terminatedHandler: $terminated,
                debug: $debugMode
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
                viewResponseFactory: $container->getSingleton(ResponseFactoryContract::class),
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
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;
        /** @var non-empty-string $filePath */
        $filePath = $env::HTTP_SERVER_RESPONSE_CACHE_FILE_PATH
            ?? Directory::frameworkStorageCachePath('response/');

        $container->setSingleton(
            CacheResponseMiddleware::class,
            new CacheResponseMiddleware(
                filePath: $filePath,
                debug: $debugMode,
            )
        );
    }
}
