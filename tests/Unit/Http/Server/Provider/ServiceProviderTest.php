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

namespace Valkyrja\Tests\Unit\Http\Server\Provider;

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Container;
use Valkyrja\Http\Middleware\Provider\ServiceProvider as MiddlewareServiceProvider;
use Valkyrja\Http\Routing\Contract\Router;
use Valkyrja\Http\Server\Contract\RequestHandler as RequestHandlerContract;
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Provider\ServiceProvider;
use Valkyrja\Http\Server\RequestHandler;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;
use Valkyrja\View\Contract\View;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testPublishersArray(): void
    {
        $publishers = ServiceProvider::publishers();

        self::assertArrayHasKey(RequestHandlerContract::class, $publishers);
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, $publishers);
        self::assertArrayHasKey(ViewThrowableCaughtMiddleware::class, $publishers);

        self::assertSame([ServiceProvider::class, 'publishRequestHandler'], $publishers[RequestHandlerContract::class]);
        self::assertSame([ServiceProvider::class, 'publishLogThrowableCaughtMiddleware'], $publishers[LogThrowableCaughtMiddleware::class]);
        self::assertSame([ServiceProvider::class, 'publishViewThrowableCaughtMiddleware'], $publishers[ViewThrowableCaughtMiddleware::class]);
    }

    public function testProvidesArray(): void
    {
        $provides = ServiceProvider::provides();

        self::assertContains(RequestHandlerContract::class, $provides);
        self::assertContains(LogThrowableCaughtMiddleware::class, $provides);
        self::assertContains(ViewThrowableCaughtMiddleware::class, $provides);
    }

    public function testPublishRequestHandler(): void
    {
        $container = new Container();

        $container->setSingleton(
            Config::class,
            [
                'http' => [
                    'server' => [
                        'requestHandler' => null,
                    ],
                ],
            ]
        );

        MiddlewareServiceProvider::publishRequestReceivedHandler($container);
        MiddlewareServiceProvider::publishExceptionHandler($container);
        MiddlewareServiceProvider::publishSendingResponseHandler($container);
        MiddlewareServiceProvider::publishTerminatedHandler($container);

        $container->setSingleton(Router::class, $this->createMock(Router::class));

        ServiceProvider::publishRequestHandler($container);

        self::assertInstanceOf(
            RequestHandler::class,
            $container->getSingleton(RequestHandlerContract::class)
        );
    }

    public function testPublishLogThrowableCaughtMiddleware(): void
    {
        $container = new Container();

        $container->setSingleton(Logger::class, $this->createMock(Logger::class));

        ServiceProvider::publishLogThrowableCaughtMiddleware($container);

        self::assertInstanceOf(
            LogThrowableCaughtMiddleware::class,
            $container->getSingleton(LogThrowableCaughtMiddleware::class)
        );
    }

    public function testPublishViewThrowableCaughtMiddleware(): void
    {
        $container = new Container();

        $container->setSingleton(View::class, $this->createMock(View::class));

        ServiceProvider::publishViewThrowableCaughtMiddleware($container);

        self::assertInstanceOf(
            ViewThrowableCaughtMiddleware::class,
            $container->getSingleton(ViewThrowableCaughtMiddleware::class)
        );
    }
}
