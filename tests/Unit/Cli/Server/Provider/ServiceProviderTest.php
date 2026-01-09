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

namespace Valkyrja\Tests\Unit\Cli\Server\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Cli\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Middleware\OutputThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Provider\ServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(InputHandlerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(OutputThrowableCaughtMiddleware::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(InputHandlerContract::class, ServiceProvider::provides());
        self::assertContains(LogThrowableCaughtMiddleware::class, ServiceProvider::provides());
        self::assertContains(OutputThrowableCaughtMiddleware::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishInputHandler(): void
    {
        $this->container->setSingleton(Config::class, self::createStub(Config::class));
        $this->container->setSingleton(RouterContract::class, self::createStub(RouterContract::class));
        $this->container->setSingleton(InputReceivedHandlerContract::class, self::createStub(InputReceivedHandlerContract::class));
        $this->container->setSingleton(ThrowableCaughtHandlerContract::class, self::createStub(ThrowableCaughtHandlerContract::class));
        $this->container->setSingleton(ExitedHandlerContract::class, self::createStub(ExitedHandlerContract::class));

        $callback = ServiceProvider::publishers()[InputHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(InputHandler::class, $this->container->getSingleton(InputHandlerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogThrowableCaughtMiddleware(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = ServiceProvider::publishers()[LogThrowableCaughtMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(LogThrowableCaughtMiddleware::class, $this->container->getSingleton(LogThrowableCaughtMiddleware::class));
    }

    public function testPublishOutputThrowableCaughtMiddleware(): void
    {
        $callback = ServiceProvider::publishers()[OutputThrowableCaughtMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(OutputThrowableCaughtMiddleware::class, $this->container->getSingleton(OutputThrowableCaughtMiddleware::class));
    }
}
