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
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Cli\Server\Handler\Contract\InputHandlerContract;
use Valkyrja\Cli\Server\Handler\InputHandler;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForHelpOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckForVersionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\InputReceived\CheckGlobalInteractionOptionsMiddleware;
use Valkyrja\Cli\Server\Middleware\RouteNotMatched\CheckCommandForTypoMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Cli\Server\Middleware\ThrowableCaught\OutputThrowableCaughtMiddleware;
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
        self::assertArrayHasKey(HelpCommand::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ListBashCommand::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ListCommand::class, ServiceProvider::publishers());
        self::assertArrayHasKey(VersionCommand::class, ServiceProvider::publishers());
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(OutputThrowableCaughtMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CheckForHelpOptionsMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CheckForVersionOptionsMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CheckGlobalInteractionOptionsMiddleware::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CheckCommandForTypoMiddleware::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(InputHandlerContract::class, ServiceProvider::provides());
        self::assertContains(HelpCommand::class, ServiceProvider::provides());
        self::assertContains(ListBashCommand::class, ServiceProvider::provides());
        self::assertContains(ListCommand::class, ServiceProvider::provides());
        self::assertContains(VersionCommand::class, ServiceProvider::provides());
        self::assertContains(LogThrowableCaughtMiddleware::class, ServiceProvider::provides());
        self::assertContains(OutputThrowableCaughtMiddleware::class, ServiceProvider::provides());
        self::assertContains(CheckForHelpOptionsMiddleware::class, ServiceProvider::provides());
        self::assertContains(CheckForVersionOptionsMiddleware::class, ServiceProvider::provides());
        self::assertContains(CheckGlobalInteractionOptionsMiddleware::class, ServiceProvider::provides());
        self::assertContains(CheckCommandForTypoMiddleware::class, ServiceProvider::provides());
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
    public function testPublishHelpCommand(): void
    {
        $this->container->setSingleton(VersionCommand::class, self::createStub(VersionCommand::class));
        $this->container->setSingleton(RouteContract::class, self::createStub(RouteContract::class));
        $this->container->setSingleton(Collection::class, self::createStub(Collection::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = ServiceProvider::publishers()[HelpCommand::class];
        $callback($this->container);

        self::assertInstanceOf(HelpCommand::class, $this->container->getSingleton(HelpCommand::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishListBashCommand(): void
    {
        $this->container->setSingleton(RouteContract::class, self::createStub(RouteContract::class));
        $this->container->setSingleton(Collection::class, self::createStub(Collection::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = ServiceProvider::publishers()[ListBashCommand::class];
        $callback($this->container);

        self::assertInstanceOf(ListBashCommand::class, $this->container->getSingleton(ListBashCommand::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishListCommand(): void
    {
        $this->container->setSingleton(VersionCommand::class, self::createStub(VersionCommand::class));
        $this->container->setSingleton(RouteContract::class, self::createStub(RouteContract::class));
        $this->container->setSingleton(Collection::class, self::createStub(Collection::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = ServiceProvider::publishers()[ListCommand::class];
        $callback($this->container);

        self::assertInstanceOf(ListCommand::class, $this->container->getSingleton(ListCommand::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVersionCommand(): void
    {
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = ServiceProvider::publishers()[VersionCommand::class];
        $callback($this->container);

        self::assertInstanceOf(VersionCommand::class, $this->container->getSingleton(VersionCommand::class));
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

    public function testPublishCheckForHelpOptionsMiddleware(): void
    {
        $this->container->setSingleton(Env::class, self::createStub(Env::class));

        $callback = ServiceProvider::publishers()[CheckForHelpOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckForHelpOptionsMiddleware::class, $this->container->getSingleton(CheckForHelpOptionsMiddleware::class));
    }

    public function testPublishCheckForVersionOptionsMiddleware(): void
    {
        $this->container->setSingleton(Env::class, self::createStub(Env::class));

        $callback = ServiceProvider::publishers()[CheckForVersionOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckForVersionOptionsMiddleware::class, $this->container->getSingleton(CheckForVersionOptionsMiddleware::class));
    }

    public function testPublishCheckGlobalInteractionOptionsMiddleware(): void
    {
        $this->container->setSingleton(Config::class, self::createStub(Config::class));
        $this->container->setSingleton(Env::class, self::createStub(Env::class));

        $callback = ServiceProvider::publishers()[CheckGlobalInteractionOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckGlobalInteractionOptionsMiddleware::class, $this->container->getSingleton(CheckGlobalInteractionOptionsMiddleware::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCheckCommandForTypoMiddleware(): void
    {
        $this->container->setSingleton(RouterContract::class, self::createStub(RouterContract::class));
        $this->container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        $callback = ServiceProvider::publishers()[CheckCommandForTypoMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckCommandForTypoMiddleware::class, $this->container->getSingleton(CheckCommandForTypoMiddleware::class));
    }
}
