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
use ReflectionProperty;
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Cli\Interaction\Data\CliInteractionConfig;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
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
use Valkyrja\Cli\Server\Provider\CliServerServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Classes\Cli\Server\Data\CliCommandCommandConfigClass;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = CliServerServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(InputHandlerContract::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(HelpCommand::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(ListBashCommand::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(ListCommand::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(VersionCommand::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(LogThrowableCaughtMiddleware::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(OutputThrowableCaughtMiddleware::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(CheckForHelpOptionsMiddleware::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(CheckForVersionOptionsMiddleware::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(CheckGlobalInteractionOptionsMiddleware::class, CliServerServiceProvider::publishers());
        self::assertArrayHasKey(CheckCommandForTypoMiddleware::class, CliServerServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishInputHandler(): void
    {
        $this->container->setSingleton(CliInteractionConfigContract::class, self::createStub(CliInteractionConfig::class));
        $this->container->setSingleton(RouterContract::class, self::createStub(RouterContract::class));
        $this->container->setSingleton(InputReceivedHandlerContract::class, self::createStub(InputReceivedHandlerContract::class));
        $this->container->setSingleton(ThrowableCaughtHandlerContract::class, self::createStub(ThrowableCaughtHandlerContract::class));
        $this->container->setSingleton(ExitedHandlerContract::class, self::createStub(ExitedHandlerContract::class));

        $callback = CliServerServiceProvider::publishers()[InputHandlerContract::class];
        $callback($this->container);

        self::assertInstanceOf(InputHandler::class, $this->container->getSingleton(InputHandlerContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishHelpCommand(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());
        $this->container->setSingleton(RouteContract::class, self::createStub(RouteContract::class));
        $this->container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = CliServerServiceProvider::publishers()[HelpCommand::class];
        $callback($this->container);

        self::assertInstanceOf(HelpCommand::class, $this->container->getSingleton(HelpCommand::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishListBashCommand(): void
    {
        $this->container->setSingleton(RouteContract::class, self::createStub(RouteContract::class));
        $this->container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = CliServerServiceProvider::publishers()[ListBashCommand::class];
        $callback($this->container);

        self::assertInstanceOf(ListBashCommand::class, $this->container->getSingleton(ListBashCommand::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishListCommand(): void
    {
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());
        $this->container->setSingleton(RouteContract::class, self::createStub(RouteContract::class));
        $this->container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = CliServerServiceProvider::publishers()[ListCommand::class];
        $callback($this->container);

        self::assertInstanceOf(ListCommand::class, $this->container->getSingleton(ListCommand::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishVersionCommand(): void
    {
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));
        $this->container->setSingleton(CliConfigContract::class, new CliConfig());
        $this->container->setSingleton(RouteContract::class, self::createStub(RouteContract::class));

        $callback = CliServerServiceProvider::publishers()[VersionCommand::class];
        $callback($this->container);

        self::assertInstanceOf(VersionCommand::class, $this->container->getSingleton(VersionCommand::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishLogThrowableCaughtMiddleware(): void
    {
        $this->container->setSingleton(LoggerContract::class, self::createStub(LoggerContract::class));

        $callback = CliServerServiceProvider::publishers()[LogThrowableCaughtMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(LogThrowableCaughtMiddleware::class, $this->container->getSingleton(LogThrowableCaughtMiddleware::class));
    }

    public function testPublishOutputThrowableCaughtMiddleware(): void
    {
        $callback = CliServerServiceProvider::publishers()[OutputThrowableCaughtMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(OutputThrowableCaughtMiddleware::class, $this->container->getSingleton(OutputThrowableCaughtMiddleware::class));
    }

    public function testPublishCheckForHelpOptionsMiddleware(): void
    {
        $callback = CliServerServiceProvider::publishers()[CheckForHelpOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckForHelpOptionsMiddleware::class, $this->container->getSingleton(CheckForHelpOptionsMiddleware::class));
    }

    public function testPublishCheckForHelpOptionsMiddlewareWithCustomConfig(): void
    {
        $this->container->setSingleton(
            ConfigContract::class,
            $config = new CliCommandCommandConfigClass(
                helpCommandName: 'helpTest',
                helpOptionName: 'helpOptionNameTest',
                helpOptionShortName: 'helpOptionShortNameTest',
            )
        );

        $callback = CliServerServiceProvider::publishers()[CheckForHelpOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckForHelpOptionsMiddleware::class, $middleware = $this->container->getSingleton(CheckForHelpOptionsMiddleware::class));

        $reflection  = new ReflectionProperty($middleware, 'commandName');
        $commandName = $reflection->getValue($middleware);

        $reflection = new ReflectionProperty($middleware, 'optionName');
        $optionName = $reflection->getValue($middleware);

        $reflection      = new ReflectionProperty($middleware, 'optionShortName');
        $optionShortName = $reflection->getValue($middleware);

        self::assertSame($config->helpCommandName, $commandName);
        self::assertSame($config->helpOptionName, $optionName);
        self::assertSame($config->helpOptionShortName, $optionShortName);
    }

    public function testPublishCheckForVersionOptionsMiddleware(): void
    {
        $callback = CliServerServiceProvider::publishers()[CheckForVersionOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckForVersionOptionsMiddleware::class, $this->container->getSingleton(CheckForVersionOptionsMiddleware::class));
    }

    public function testPublishCheckForVersionOptionsMiddlewareWithCustomConfig(): void
    {
        $this->container->setSingleton(
            ConfigContract::class,
            $config = new CliCommandCommandConfigClass(
                versionCommandName: 'versionTest',
                versionOptionName: 'versionOptionNameTest',
                versionOptionShortName: 'versionOptionShortNameTest',
            )
        );

        $callback = CliServerServiceProvider::publishers()[CheckForVersionOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckForVersionOptionsMiddleware::class, $middleware = $this->container->getSingleton(CheckForVersionOptionsMiddleware::class));

        $reflection  = new ReflectionProperty($middleware, 'commandName');
        $commandName = $reflection->getValue($middleware);

        $reflection = new ReflectionProperty($middleware, 'optionName');
        $optionName = $reflection->getValue($middleware);

        $reflection      = new ReflectionProperty($middleware, 'optionShortName');
        $optionShortName = $reflection->getValue($middleware);

        self::assertSame($config->versionCommandName, $commandName);
        self::assertSame($config->versionOptionName, $optionName);
        self::assertSame($config->versionOptionShortName, $optionShortName);
    }

    public function testPublishCheckGlobalInteractionOptionsMiddleware(): void
    {
        $this->container->setSingleton(CliInteractionConfigContract::class, self::createStub(CliInteractionConfig::class));

        $callback = CliServerServiceProvider::publishers()[CheckGlobalInteractionOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckGlobalInteractionOptionsMiddleware::class, $this->container->getSingleton(CheckGlobalInteractionOptionsMiddleware::class));
    }

    public function testPublishCheckGlobalInteractionOptionsMiddlewareWithCustomConfig(): void
    {
        $this->container->setSingleton(
            ConfigContract::class,
            $config = new CliCommandCommandConfigClass(
                noInteractionOptionName: 'noInteractionOptionNameTest',
                noInteractionOptionShortName: 'noInteractionOptionShortNameTest',
                quietOptionName: 'quietOptionNameTest',
                quietOptionShortName: 'quietOptionShortNameTest',
                silentOptionName: 'silentOptionNameTest',
                silentOptionShortName: 'silentOptionShortNameTest',
            )
        );
        $this->container->setSingleton(CliInteractionConfigContract::class, self::createStub(CliInteractionConfig::class));

        $callback = CliServerServiceProvider::publishers()[CheckGlobalInteractionOptionsMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckGlobalInteractionOptionsMiddleware::class, $middleware = $this->container->getSingleton(CheckGlobalInteractionOptionsMiddleware::class));

        $reflection              = new ReflectionProperty($middleware, 'noInteractionOptionName');
        $noInteractionOptionName = $reflection->getValue($middleware);

        $reflection                   = new ReflectionProperty($middleware, 'noInteractionOptionShortName');
        $noInteractionOptionShortName = $reflection->getValue($middleware);

        self::assertSame($config->noInteractionOptionName, $noInteractionOptionName);
        self::assertSame($config->noInteractionOptionShortName, $noInteractionOptionShortName);

        $reflection      = new ReflectionProperty($middleware, 'quietOptionName');
        $quietOptionName = $reflection->getValue($middleware);

        $reflection           = new ReflectionProperty($middleware, 'quietOptionShortName');
        $quietOptionShortName = $reflection->getValue($middleware);

        self::assertSame($config->quietOptionName, $quietOptionName);
        self::assertSame($config->quietOptionShortName, $quietOptionShortName);

        $reflection       = new ReflectionProperty($middleware, 'silentOptionName');
        $silentOptionName = $reflection->getValue($middleware);

        $reflection            = new ReflectionProperty($middleware, 'silentOptionShortName');
        $silentOptionShortName = $reflection->getValue($middleware);

        self::assertSame($config->silentOptionName, $silentOptionName);
        self::assertSame($config->silentOptionShortName, $silentOptionShortName);
    }

    /**
     * @throws Exception
     */
    public function testPublishCheckCommandForTypoMiddleware(): void
    {
        $this->container->setSingleton(RouterContract::class, self::createStub(RouterContract::class));
        $this->container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));

        $callback = CliServerServiceProvider::publishers()[CheckCommandForTypoMiddleware::class];
        $callback($this->container);

        self::assertInstanceOf(CheckCommandForTypoMiddleware::class, $this->container->getSingleton(CheckCommandForTypoMiddleware::class));
    }
}
