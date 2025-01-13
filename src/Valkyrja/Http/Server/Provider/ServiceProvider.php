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

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\Middleware\Handler\Contract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Contract\Router;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;
use Valkyrja\Http\Server\RequestHandler as DefaultRequestHandler;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\View\Contract\View;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /** @var class-string<RequestHandler> */
    public static string $requestHandlerClass = DefaultRequestHandler::class;

    /**
     * @inheritDoc
     */
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRequestHandler(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        /** @var RequestReceivedHandler&Handler $requestReceived */
        $requestReceived = $container->getSingleton(RequestReceivedHandler::class);
        /** @var ThrowableCaughtHandler&Handler $exception */
        $exception = $container->getSingleton(ThrowableCaughtHandler::class);
        /** @var SendingResponseHandler&Handler $sendingResponse */
        $sendingResponse = $container->getSingleton(SendingResponseHandler::class);
        /** @var TerminatedHandler&Handler $terminated */
        $terminated = $container->getSingleton(TerminatedHandler::class);

        $exception->add(LogThrowableCaughtMiddleware::class, ViewThrowableCaughtMiddleware::class);

        $container->setSingleton(
            RequestHandler::class,
            new DefaultRequestHandler(
                container: $container,
                router: $container->getSingleton(Router::class),
                requestReceivedHandler: $requestReceived,
                exceptionHandler: $exception,
                sendingResponseHandler: $sendingResponse,
                terminatedHandler: $terminated,
                debug: $config['app']['debug'] ?? false
            )
        );
    }

    /**
     * Publish the LogThrowableCaughtMiddleware service.
     *
     * @param Container $container The container
     *
     * @return void
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishViewThrowableCaughtMiddleware(Container $container): void
    {
        $container->setSingleton(
            ViewThrowableCaughtMiddleware::class,
            new ViewThrowableCaughtMiddleware(
                view: $container->getSingleton(View::class),
            )
        );
    }
}
