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

namespace Valkyrja\Tests\Unit\Cli\Routing\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Provider\CliRoutingCliRouteProvider;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the cli route provider service.
 */
final class CliRouteProviderTest extends TestCase
{
    public function testGetRoutes(): void
    {
        self::assertEmpty(CliRoutingCliRouteProvider::getRoutes());
    }

    public function testGetControllerClasses(): void
    {
        $controllers = CliRoutingCliRouteProvider::getControllerClasses();

        self::assertContains(HelpCommand::class, $controllers);
        self::assertContains(ListBashCommand::class, $controllers);
        self::assertContains(ListCommand::class, $controllers);
        self::assertContains(VersionCommand::class, $controllers);
    }

    /**
     * @throws Exception
     */
    public function testListHandler(): void
    {
        $output  = self::createStub(OutputContract::class);
        $command = $this->createMock(ListCommand::class);
        $command->expects($this->once())->method('run')->willReturn($output);

        $container = new Container();
        $container->setSingleton(ListCommand::class, $command);

        self::assertSame($output, CliRoutingCliRouteProvider::listHandler($container, self::createStub(RouteContract::class)));
    }

    /**
     * @throws Exception
     */
    public function testListBashHandler(): void
    {
        $output  = self::createStub(OutputContract::class);
        $command = $this->createMock(ListBashCommand::class);
        $command->expects($this->once())->method('run')->willReturn($output);

        $container = new Container();
        $container->setSingleton(ListBashCommand::class, $command);

        self::assertSame($output, CliRoutingCliRouteProvider::listBashHandler($container, self::createStub(RouteContract::class)));
    }

    /**
     * @throws Exception
     */
    public function testHelpHandler(): void
    {
        $output  = self::createStub(OutputContract::class);
        $command = $this->createMock(HelpCommand::class);
        $command->expects($this->once())->method('run')->willReturn($output);

        $container = new Container();
        $container->setSingleton(HelpCommand::class, $command);

        self::assertSame($output, CliRoutingCliRouteProvider::helpHandler($container, self::createStub(RouteContract::class)));
    }

    /**
     * @throws Exception
     */
    public function testVersionHandler(): void
    {
        $output  = self::createStub(OutputContract::class);
        $command = $this->createMock(VersionCommand::class);
        $command->expects($this->once())->method('run')->willReturn($output);

        $container = new Container();
        $container->setSingleton(VersionCommand::class, $command);

        self::assertSame($output, CliRoutingCliRouteProvider::versionHandler($container, self::createStub(RouteContract::class)));
    }
}
