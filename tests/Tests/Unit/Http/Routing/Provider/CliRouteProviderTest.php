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

namespace Valkyrja\Tests\Unit\Http\Routing\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Http\Routing\Cli\Command\GenerateDataCommand;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliRouteProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the cli route provider service.
 */
final class CliRouteProviderTest extends TestCase
{
    public function testGetRoutes(): void
    {
        self::assertEmpty(HttpRoutingCliRouteProvider::getRoutes());
    }

    public function testGetControllerClasses(): void
    {
        self::assertContains(ListCommand::class, HttpRoutingCliRouteProvider::getControllerClasses());
        self::assertContains(GenerateDataCommand::class, HttpRoutingCliRouteProvider::getControllerClasses());
    }

    /**
     * @throws Exception
     */
    public function testListHandler(): void
    {
        $output          = self::createStub(OutputContract::class);
        $versionCommand  = self::createStub(VersionCommand::class);
        $collection      = self::createStub(RouteCollectionContract::class);
        $outputFactory   = self::createStub(OutputFactoryContract::class);
        $command         = $this->createMock(ListCommand::class);

        $command
            ->expects($this->once())
            ->method('run')
            ->with($versionCommand, $collection, $outputFactory)
            ->willReturn($output);

        $container = new Container();
        $container->setSingleton(ListCommand::class, $command);
        $container->setSingleton(VersionCommand::class, $versionCommand);
        $container->setSingleton(RouteCollectionContract::class, $collection);
        $container->setSingleton(OutputFactoryContract::class, $outputFactory);

        self::assertSame($output, HttpRoutingCliRouteProvider::listHandler($container));
    }

    /**
     * @throws Exception
     */
    public function testGenerateDataHandler(): void
    {
        $output  = self::createStub(OutputContract::class);
        $command = $this->createMock(GenerateDataCommand::class);
        $command->expects($this->once())->method('run')->willReturn($output);

        $container = new Container();
        $container->setSingleton(GenerateDataCommand::class, $command);

        self::assertSame($output, HttpRoutingCliRouteProvider::generateDataHandler($container));
    }
}
