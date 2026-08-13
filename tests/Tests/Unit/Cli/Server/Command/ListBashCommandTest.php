<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Server\Command;

use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
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
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $command = new ListBashCommand(
            route: $this->makeRoute(),
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
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2]);

        $command = new ListBashCommand(
            route: $this->makeRoute(),
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
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2, $listRoute3]);

        $command = new ListBashCommand(
            route: $this->makeRoute($namespace),
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
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2, $listRoute3]);

        $command = new ListBashCommand(
            route: $this->makeRoute($namespace),
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        self::assertSame("$listRouteName $listRoute2Name", $outputFromRun->getMessages()[0]->getText());
    }

    /**
     * A route that declares no namespace argument filters nothing instead of throwing.
     */
    public function testRunWithARouteThatDeclaresNoArguments(): void
    {
        $listRouteName = 'Route1name';
        $listRoute     = $this->createMock(Route::class);
        $listRoute->expects($this->once())
            ->method('getName')
            ->willReturn($listRouteName);

        $output        = new Output();
        $outputFactory = $this->createMock(OutputFactory::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute]);

        $command = new ListBashCommand(
            route: $this->makeRouteWithoutArguments(),
            collection: $collection,
            outputFactory: $outputFactory
        );

        $outputFromRun = $command->run();

        self::assertSame($listRouteName, $outputFromRun->getMessages()[0]->getText());
    }

    public function testHelp(): void
    {
        $text = 'A command to list all the commands present within the Cli component for bash completion.';

        self::assertSame($text, ListBashCommand::help()->getText());
        self::assertSame($text, ListBashCommand::help()->getFormattedText());
    }

    /**
     * A route that declares the namespace argument, and carries a value only when one was spelled out.
     */
    private function makeRoute(string $namespace = ''): RouteContract
    {
        $applicationName = new ArgumentParameter(
            name: 'applicationName',
            description: 'The application name'
        );
        $namespaceArgument = new ArgumentParameter(
            name: 'namespace',
            description: 'An optional namespace to filter commands by'
        );

        if ($namespace !== '') {
            $namespaceArgument = $namespaceArgument->withArguments(new Argument($namespace));
        }

        return new Route(
            name: 'list:bash',
            description: 'List all commands for bash completion',
            handler: static fn (): OutputContract => new Output(),
            arguments: [$applicationName, $namespaceArgument],
        );
    }

    /**
     * A route that declares no argument, as a narrowed route or a stale compiled route can.
     */
    private function makeRouteWithoutArguments(): RouteContract
    {
        return new Route(
            name: 'list:bash',
            description: 'List all commands for bash completion',
            handler: static fn (): OutputContract => new Output(),
        );
    }
}
