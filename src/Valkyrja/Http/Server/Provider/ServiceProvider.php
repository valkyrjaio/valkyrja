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
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Dispatcher\Contract\Router;
use Valkyrja\Http\Server\Handler\Contract\RequestHandler;
use Valkyrja\Http\Server\Handler\RequestHandler as DefaultRequestHandler;
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;
use Valkyrja\Log\Logger\Contract\Logger;
use Valkyrja\View\Factory\Contract\ResponseFactory;

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
            RequestHandler::class                => [self::class, 'publishRequestHandler'],
            LogThrowableCaughtMiddleware::class  => [self::class, 'publishLogThrowableCaughtMiddleware'],
            ViewThrowableCaughtMiddleware::class => [self::class, 'publishViewThrowableCaughtMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            RequestHandler::class,
            LogThrowableCaughtMiddleware::class,
            ViewThrowableCaughtMiddleware::class,
        ];
    }

    /**
     * Publish the RequestHandler service.
     */
    public static function publishRequestHandler(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;

        $requestReceived = $container->getSingleton(RequestReceivedHandler::class);
        $exception       = $container->getSingleton(ThrowableCaughtHandler::class);
        $sendingResponse = $container->getSingleton(SendingResponseHandler::class);
        $terminated      = $container->getSingleton(TerminatedHandler::class);

        $exception->add(LogThrowableCaughtMiddleware::class, ViewThrowableCaughtMiddleware::class);

        $container->setSingleton(
            RequestHandler::class,
            new DefaultRequestHandler(
                container: $container,
                router: $container->getSingleton(Router::class),
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
    public static function publishLogThrowableCaughtMiddleware(Container $container): void
    {
        $container->setSingleton(
            LogThrowableCaughtMiddleware::class,
            new LogThrowableCaughtMiddleware(
                logger: $container->getSingleton(Logger::class),
            )
        );
    }

    /**
     * Publish the ViewThrowableCaughtMiddleware service.
     */
    public static function publishViewThrowableCaughtMiddleware(Container $container): void
    {
        $container->setSingleton(
            ViewThrowableCaughtMiddleware::class,
            new ViewThrowableCaughtMiddleware(
                viewResponseFactory: $container->getSingleton(ResponseFactory::class),
            )
        );
    }
}
