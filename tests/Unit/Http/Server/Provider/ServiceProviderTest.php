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

use Valkyrja\Http\Middleware\Provider\ServiceProvider as MiddlewareServiceProvider;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Handler\RequestHandler;
use Valkyrja\Http\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ViewThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Provider\ServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract;

/**
 * Test the ServiceProvider.
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
        $container = $this->container;

        MiddlewareServiceProvider::publishRequestReceivedHandler($container);
        MiddlewareServiceProvider::publishThrowableCaughtHandler($container);
        MiddlewareServiceProvider::publishSendingResponseHandler($container);
        MiddlewareServiceProvider::publishTerminatedHandler($container);

        $container->setSingleton(RouterContract::class, self::createStub(RouterContract::class));

        ServiceProvider::publishRequestHandler($container);

        self::assertInstanceOf(
            RequestHandler::class,
            $container->getSingleton(RequestHandlerContract::class)
        );
    }

    public function testPublishLogThrowableCaughtMiddleware(): void
    {
        $container = $this->container;

        $container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        ServiceProvider::publishLogThrowableCaughtMiddleware($container);

        self::assertInstanceOf(
            LogThrowableCaughtMiddleware::class,
            $container->getSingleton(LogThrowableCaughtMiddleware::class)
        );
    }

    public function testPublishViewThrowableCaughtMiddleware(): void
    {
        $container = $this->container;

        $container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        ServiceProvider::publishViewThrowableCaughtMiddleware($container);

        self::assertInstanceOf(
            ViewThrowableCaughtMiddleware::class,
            $container->getSingleton(ViewThrowableCaughtMiddleware::class)
        );
    }
}
