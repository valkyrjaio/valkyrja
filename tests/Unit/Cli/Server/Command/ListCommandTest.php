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

namespace Valkyrja\Tests\Unit\Cli\Server\Command;

use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_get_clean;
use function ob_start;

class ListCommandTest extends TestCase
{
    public function testRunWithNoRoutes(): void
    {
        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);
        $versionCommand = $this->createMock(VersionCommand::class);
        $versionCommand->expects($this->never())
            ->method('run');
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([]);
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('getOption')
            ->willReturn(null);

        $command = new ListCommand(
            version: $versionCommand,
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString('No routes found.', $obOutput);
    }

    public function testRunNonExistentNamespace(): void
    {
        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);
        $versionCommand = $this->createMock(VersionCommand::class);
        $versionCommand->expects($this->never())
            ->method('run');
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([]);
        $option = $this->createMock(OptionParameter::class);
        $option->expects($this->once())
            ->method('getFirstValue')
            ->willReturn('non-existent namespace');
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('getOption')
            ->willReturn($option);

        $command = new ListCommand(
            version: $versionCommand,
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString('Namespace `non-existent namespace` was not found.', $obOutput);
    }

    public function testRun(): void
    {
        $listRouteName        = 'Route1name';
        $listRouteDescription = 'Route 1 description';
        $listRoute            = $this->createMock(Route::class);
        $listRoute->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($listRouteName);
        $listRoute->expects($this->once())
            ->method('getDescription')
            ->willReturn($listRouteDescription);

        $listRoute2Name        = 'Route2name';
        $listRoute2Description = 'Route 2 description';
        $listRoute2            = $this->createMock(Route::class);
        $listRoute2->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($listRoute2Name);
        $listRoute2->expects($this->once())
            ->method('getDescription')
            ->willReturn($listRoute2Description);

        $versionText   = 'Version Command Output';
        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->never())
            ->method('createOutput');
        $versionCommand = $this->createMock(VersionCommand::class);
        $versionCommand->expects($this->once())
            ->method('run')
            ->willReturn($output->withMessages(new Message($versionText)));
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2]);
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('getOption')
            ->willReturn(null);

        $command = new ListCommand(
            version: $versionCommand,
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString($versionText, $obOutput);
        self::assertStringContainsString('Commands:', $obOutput);
        self::assertStringContainsString($listRouteName, $obOutput);
        self::assertStringContainsString($listRouteDescription, $obOutput);
        self::assertStringContainsString($listRoute2Name, $obOutput);
        self::assertStringContainsString($listRoute2Description, $obOutput);
    }

    public function testRunWithNamespace(): void
    {
        $namespace = 'namespace';

        $listRouteName        = "$namespace:Route1name";
        $listRouteDescription = 'Route 1 description';
        $listRoute            = $this->createMock(Route::class);
        $listRoute->expects($this->exactly(3))
            ->method('getName')
            ->willReturn($listRouteName);
        $listRoute->expects($this->once())
            ->method('getDescription')
            ->willReturn($listRouteDescription);

        $listRoute2Name        = "$namespace:Route2name";
        $listRoute2Description = 'Route 2 description';
        $listRoute2            = $this->createMock(Route::class);
        $listRoute2->expects($this->exactly(3))
            ->method('getName')
            ->willReturn($listRoute2Name);
        $listRoute2->expects($this->once())
            ->method('getDescription')
            ->willReturn($listRoute2Description);

        $listRoute3Name = 'Route3name';
        $listRoute3     = $this->createMock(Route::class);
        $listRoute3->expects($this->once())
            ->method('getName')
            ->willReturn($listRoute3Name);
        $listRoute3->expects($this->never())
            ->method('getDescription');

        $versionText   = 'Version Command Output';
        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->never())
            ->method('createOutput');
        $versionCommand = $this->createMock(VersionCommand::class);
        $versionCommand->expects($this->once())
            ->method('run')
            ->willReturn($output->withMessages(new Message($versionText)));
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2, $listRoute3]);
        $option = $this->createMock(OptionParameter::class);
        $option->expects($this->once())
            ->method('getFirstValue')
            ->willReturn($namespace);
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('getOption')
            ->willReturn($option);

        $command = new ListCommand(
            version: $versionCommand,
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString($versionText, $obOutput);
        self::assertStringContainsString("Commands [$namespace]:", $obOutput);
        self::assertStringContainsString($listRouteName, $obOutput);
        self::assertStringContainsString($listRouteDescription, $obOutput);
        self::assertStringContainsString($listRoute2Name, $obOutput);
        self::assertStringContainsString($listRoute2Description, $obOutput);
    }
}
