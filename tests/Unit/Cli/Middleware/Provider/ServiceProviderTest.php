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

namespace Valkyrja\Tests\Unit\Cli\Middleware\Provider;

use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Middleware\Provider\ServiceProvider;
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

    public function testPublishInputReceivedHandler(): void
    {
        ServiceProvider::publishInputReceivedHandler($this->container);

        self::assertInstanceOf(InputReceivedHandler::class, $this->container->getSingleton(InputReceivedHandler::class));
    }

    public function testPublishThrowableCaughtHandler(): void
    {
        ServiceProvider::publishThrowableCaughtHandler($this->container);

        self::assertInstanceOf(ThrowableCaughtHandler::class, $this->container->getSingleton(ThrowableCaughtHandler::class));
    }

    public function testPublishCommandMatchedHandler(): void
    {
        ServiceProvider::publishCommandMatchedHandler($this->container);

        self::assertInstanceOf(CommandMatchedHandler::class, $this->container->getSingleton(CommandMatchedHandler::class));
    }

    public function testPublishCommandNotMatchedHandler(): void
    {
        ServiceProvider::publishCommandNotMatchedHandler($this->container);

        self::assertInstanceOf(CommandNotMatchedHandler::class, $this->container->getSingleton(CommandNotMatchedHandler::class));
    }

    public function testPublishCommandDispatchedHandler(): void
    {
        ServiceProvider::publishCommandDispatchedHandler($this->container);

        self::assertInstanceOf(CommandDispatchedHandler::class, $this->container->getSingleton(CommandDispatchedHandler::class));
    }

    public function testPublishExitedHandler(): void
    {
        ServiceProvider::publishExitedHandler($this->container);

        self::assertInstanceOf(ExitedHandler::class, $this->container->getSingleton(ExitedHandler::class));
    }
}
