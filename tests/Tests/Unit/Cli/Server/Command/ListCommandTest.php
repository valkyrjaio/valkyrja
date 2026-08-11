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

use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\PlainOutput;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_get_clean;
use function ob_start;

final class ListCommandTest extends TestCase
{
    public function testRunWithNoRoutes(): void
    {
        $outputFactory = $this->makeOutputFactory();
        $collection    = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $command = new ListCommand(
            config: new CliConfig(),
            route: $this->makeRoute(),
            collection: $collection,
            outputFactory: $outputFactory,
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString('No routes found.', $obOutput);
    }

    public function testRunNonExistentNamespace(): void
    {
        $outputFactory = $this->makeOutputFactory();
        $collection    = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $command = new ListCommand(
            config: new CliConfig(),
            route: $this->makeRoute('non-existent namespace'),
            collection: $collection,
            outputFactory: $outputFactory,
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString('Namespace `non-existent namespace` was not found.', $obOutput);
    }

    public function testRun(): void
    {
        $appName    = 'TestApp';
        $appVersion = '1.0.0';

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

        $outputFactory = $this->makeOutputFactory();
        $collection    = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2]);

        $command = new ListCommand(
            config: new CliConfig(namespace: $appName, version: $appVersion),
            route: $this->makeRoute(),
            collection: $collection,
            outputFactory: $outputFactory,
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString("╭── $appName v$appVersion", $obOutput);
        self::assertStringContainsString('Commands:', $obOutput);
        self::assertStringContainsString($listRouteName, $obOutput);
        self::assertStringContainsString($listRouteDescription, $obOutput);
        self::assertStringContainsString($listRoute2Name, $obOutput);
        self::assertStringContainsString($listRoute2Description, $obOutput);
    }

    public function testRunWithNamespace(): void
    {
        $appName    = 'TestApp';
        $appVersion = '1.0.0';
        $namespace  = 'namespace';

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

        $outputFactory = $this->makeOutputFactory();
        $collection    = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute, $listRoute2, $listRoute3]);

        $command = new ListCommand(
            config: new CliConfig(namespace: $appName, version: $appVersion),
            route: $this->makeRoute($namespace),
            collection: $collection,
            outputFactory: $outputFactory,
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString("╭── $appName v$appVersion", $obOutput);
        self::assertStringContainsString("Commands [$namespace]:", $obOutput);
        self::assertStringContainsString($listRouteName, $obOutput);
        self::assertStringContainsString($listRouteDescription, $obOutput);
        self::assertStringContainsString($listRoute2Name, $obOutput);
        self::assertStringContainsString($listRoute2Description, $obOutput);
    }

    /**
     * A route that declares no namespace option filters nothing instead of throwing.
     */
    public function testRunWithARouteThatDeclaresNoOptions(): void
    {
        $listRouteName = 'Route1name';
        $listRoute     = $this->createMock(Route::class);
        $listRoute->expects($this->once())
            ->method('getName')
            ->willReturn($listRouteName);
        $listRoute->expects($this->once())
            ->method('getDescription')
            ->willReturn('Route 1 description');

        $outputFactory = $this->makeOutputFactory();
        $collection    = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('all')
            ->willReturn([$listRoute]);

        $command = new ListCommand(
            config: new CliConfig(),
            route: $this->makeRouteWithoutOptions(),
            collection: $collection,
            outputFactory: $outputFactory,
        );

        $outputFromRun = $command->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertStringContainsString('Commands:', $obOutput);
        self::assertStringContainsString($listRouteName, $obOutput);
    }

    public function testHelp(): void
    {
        $text = 'A command to list all the commands present within the Cli component.';

        self::assertSame($text, ListCommand::help()->getText());
        self::assertSame($text, ListCommand::help()->getFormattedText());
    }

    /**
     * A route that declares the namespace option, and carries a value only when one was spelled out.
     */
    private function makeRoute(string $namespace = ''): RouteContract
    {
        $option = new OptionParameter(
            name: 'namespace',
            description: 'An optional namespace to filter commands by'
        );

        if ($namespace !== '') {
            $option = $option->withOptions(new Option('namespace', $namespace));
        }

        return new Route(
            name: 'list',
            description: 'List all commands',
            handler: static fn (): OutputContract => new PlainOutput(),
            options: [$option],
        );
    }

    /**
     * A route that declares no option, as a narrowed route or a stale compiled route can.
     */
    private function makeRouteWithoutOptions(): RouteContract
    {
        return new Route(
            name: 'list',
            description: 'List all commands',
            handler: static fn (): OutputContract => new PlainOutput(),
        );
    }

    private function makeOutputFactory(): OutputFactoryContract
    {
        $outputFactory = $this->createMock(OutputFactoryContract::class);
        $outputFactory->expects($this->once())->method('createOutput')->willReturn(new PlainOutput());

        return $outputFactory;
    }
}
