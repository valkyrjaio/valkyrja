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
use Valkyrja\Cli\Interaction\Config;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Contract\Router;
use Valkyrja\Cli\Server\Contract\InputHandler as InputHandlerContract;
use Valkyrja\Cli\Server\InputHandler;
use Valkyrja\Cli\Server\Middleware\LogThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Provider\ServiceProvider;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishInputHandler(): void
    {
        $this->container->setSingleton(Config::class, $this->createMock(Config::class));
        $this->container->setSingleton(Router::class, $this->createMock(Router::class));
        $this->container->setSingleton(InputReceivedHandler::class, $this->createMock(InputReceivedHandler::class));
        $this->container->setSingleton(ThrowableCaughtHandler::class, $this->createMock(ThrowableCaughtHandler::class));
        $this->container->setSingleton(ExitedHandler::class, $this->createMock(ExitedHandler::class));

        ServiceProvider::publishInputHandler($this->container);

        self::assertInstanceOf(InputHandler::class, $this->container->getSingleton(InputHandlerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogThrowableCaughtMiddleware(): void
    {
        $this->container->setSingleton(Logger::class, $this->createMock(Logger::class));

        ServiceProvider::publishLogThrowableCaughtMiddleware($this->container);

        self::assertInstanceOf(LogThrowableCaughtMiddleware::class, $this->container->getSingleton(LogThrowableCaughtMiddleware::class));
    }
}
