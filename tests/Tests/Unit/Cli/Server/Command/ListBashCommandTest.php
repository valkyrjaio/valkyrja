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

use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ListBashCommandTest extends TestCase
{
    public function testRunWithNoRoutes(): void
    {
        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([]);
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('hasArgument')
            ->willReturn(false);
        $route->expects($this->never())
            ->method('getArgument');

        $command = new ListBashCommand(
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        self::assertSame('', $outputFromRun->getMessages()[0]->getText());
    }

    public function testRun(): void
    {
        $listRouteName = 'Route1name';
        $listRoute     = $this->createMock(Route::class);
        $listRoute->expects($this->once())
            ->method('getName')
            ->willReturn($listRouteName);

        $listRoute2Name = 'Route2name';
        $listRoute2     = $this->createMock(Route::class);
        $listRoute2->expects($this->once())
            ->method('getName')
            ->willReturn($listRoute2Name);

        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2]);
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('hasArgument')
            ->willReturn(false);
        $route->expects($this->never())
            ->method('getArgument');

        $command = new ListBashCommand(
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        self::assertSame("$listRouteName $listRoute2Name", $outputFromRun->getMessages()[0]->getText());
    }

    public function testRunWithNamespace(): void
    {
        $namespace = 'namespace';

        $listRouteName = "$namespace:Route1name";
        $listRoute     = $this->createMock(Route::class);
        $listRoute->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($listRouteName);

        $listRoute2Name = "$namespace:Route2name";
        $listRoute2     = $this->createMock(Route::class);
        $listRoute2->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($listRoute2Name);

        $listRoute3Name = 'Route3name';
        $listRoute3     = $this->createMock(Route::class);
        $listRoute3->expects($this->once())
            ->method('getName')
            ->willReturn($listRoute3Name);

        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2, $listRoute3]);
        $argument = $this->createMock(ArgumentParameter::class);
        $argument->expects($this->once())
            ->method('getFirstValue')
            ->willReturn($namespace);
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('hasArgument')
            ->willReturn(true);
        $route->expects($this->once())
            ->method('getArgument')
            ->willReturn($argument);

        $command = new ListBashCommand(
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        self::assertSame("$listRouteName $listRoute2Name", $outputFromRun->getMessages()[0]->getText());
    }

    public function testRunWithNamespaceWithColon(): void
    {
        $namespace = 'namespace:';

        $listRouteName = 'Route1name';
        $listRoute     = $this->createMock(Route::class);
        $listRoute->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($namespace . $listRouteName);

        $listRoute2Name = 'Route2name';
        $listRoute2     = $this->createMock(Route::class);
        $listRoute2->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($namespace . $listRoute2Name);

        $listRoute3Name = 'Route3name';
        $listRoute3     = $this->createMock(Route::class);
        $listRoute3->expects($this->once())
            ->method('getName')
            ->willReturn($listRoute3Name);

        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2, $listRoute3]);
        $argument = $this->createMock(ArgumentParameter::class);
        $argument->expects($this->once())
            ->method('getFirstValue')
            ->willReturn($namespace);
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('hasArgument')
            ->willReturn(true);
        $route->expects($this->once())
            ->method('getArgument')
            ->willReturn($argument);

        $command = new ListBashCommand(
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        self::assertSame("$listRouteName $listRoute2Name", $outputFromRun->getMessages()[0]->getText());
    }

    public function testHelp(): void
    {
        $text = 'A command to list all the commands present within the Cli component for bash completion.';

        self::assertSame($text, ListBashCommand::help()->getText());
        self::assertSame($text, ListBashCommand::help()->getFormattedText());
    }
}
