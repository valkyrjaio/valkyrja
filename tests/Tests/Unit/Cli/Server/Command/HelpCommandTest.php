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
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\PlainOutput;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function ob_get_clean;
use function ob_start;

final class HelpCommandTest extends TestCase
{
    public function testRunWithNonExistentCommandName(): void
    {
        $commandName = 'foo';

        $route      = $this->makeHelpRoute($commandName);
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('has')
            ->with($commandName)
            ->willReturn(false);
        $collection->expects($this->never())
            ->method('get');

        $helpCommand   = new HelpCommand(
            config: new CliConfig(),
            route: $route,
            collection: $collection,
            outputFactory: $this->makeOutputFactory(),
        );
        $outputFromRun = $helpCommand->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertSame(ExitCode::ERROR, $outputFromRun->getExitCode());
        self::assertStringContainsString("Command `$commandName` was not found.", $obOutput);
    }

    public function testRun(): void
    {
        $appName     = 'TestApp';
        $appVersion  = '1.0.0';
        $commandName = 'foo';
        $description = 'description here';
        $helpRoute   = new Route(
            name: $commandName,
            description: $description,
            handler: static fn (): null => null,
            helpText: [$this, 'getHelpText'],
            arguments: [
                new ArgumentParameter(
                    name: 'argument1',
                    description: 'Argument 1 description',
                ),
                new ArgumentParameter(
                    name: 'argument2',
                    description: 'Argument 2 description',
                    valueMode: ArgumentValueMode::ARRAY
                ),
            ],
            options: [
                new OptionParameter(
                    name: 'option1',
                    description: 'Option 1 description',
                ),
                new OptionParameter(
                    name: 'option2',
                    description: 'Option 2 description',
                    valueDisplayName: 'option2value',
                    valueMode: OptionValueMode::ARRAY
                ),
                new OptionParameter(
                    name: 'option3',
                    description: 'Option 3 description',
                    valueDisplayName: 'option3value',
                    defaultValue: 'value4',
                    validValues: ['value1', 'value2', 'value3', 'value4'],
                    mode: OptionMode::REQUIRED,
                ),
                new OptionParameter(
                    name: 'option4',
                    description: 'Option 4 description',
                    valueDisplayName: 'option4value'
                ),
            ]
        );

        $route      = $this->makeHelpRoute($commandName);
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('has')
            ->with($commandName)
            ->willReturn(true);
        $collection->expects($this->once())
            ->method('get')
            ->with($commandName)
            ->willReturn($helpRoute);

        $helpCommand   = new HelpCommand(
            config: new CliConfig(namespace: $appName, version: $appVersion),
            route: $route,
            collection: $collection,
            outputFactory: $this->makeOutputFactory(),
        );
        $outputFromRun = $helpCommand->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertSame(ExitCode::SUCCESS, $outputFromRun->getExitCode());
        self::assertStringContainsString("╭── $appName v$appVersion", $obOutput);
        self::assertStringContainsString('foo [options] [global options] [argument1] [argument2...]', $obOutput);
        self::assertStringContainsString($commandName, $obOutput);
        self::assertStringContainsString($description, $obOutput);
        self::assertStringContainsString('Help Command Output', $obOutput);
        self::assertStringContainsString('argument1', $obOutput);
        self::assertStringContainsString('Argument 1 description', $obOutput);
        self::assertStringContainsString('argument2', $obOutput);
        self::assertStringContainsString('Argument 2 description', $obOutput);
        self::assertStringContainsString('--option1', $obOutput);
        self::assertStringContainsString('Option 1 description', $obOutput);
        self::assertStringContainsString('--option2', $obOutput);
        self::assertStringContainsString('...[=option2value]', $obOutput);
        self::assertStringContainsString('Option 2 description', $obOutput);
        self::assertStringContainsString('--option3', $obOutput);
        self::assertStringContainsString('- `value1`', $obOutput);
        self::assertStringContainsString('- `value2`', $obOutput);
        self::assertStringContainsString('- `value3`', $obOutput);
        self::assertStringContainsString('- `value4`', $obOutput);
        self::assertStringContainsString('(default)', $obOutput);
        self::assertStringContainsString('=option3value', $obOutput);
        self::assertStringContainsString('Option 3 description', $obOutput);
        self::assertStringContainsString('--option4', $obOutput);
        self::assertStringContainsString('[=option4value]', $obOutput);
        self::assertStringContainsString('Option 4 description', $obOutput);
    }

    public function testRunWithNoHelpText(): void
    {
        $appName     = 'TestApp';
        $appVersion  = '1.0.0';
        $commandName = 'bar';
        $description = 'command without help text';

        $helpRoute = new Route(
            name: $commandName,
            description: $description,
            handler: static fn (): null => null,
        );

        $route      = $this->makeHelpRoute($commandName);
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('has')
            ->with($commandName)
            ->willReturn(true);
        $collection->expects($this->once())
            ->method('get')
            ->with($commandName)
            ->willReturn($helpRoute);

        $helpCommand   = new HelpCommand(
            config: new CliConfig(namespace: $appName, version: $appVersion),
            route: $route,
            collection: $collection,
            outputFactory: $this->makeOutputFactory(),
        );
        $outputFromRun = $helpCommand->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertSame(ExitCode::SUCCESS, $outputFromRun->getExitCode());
        self::assertStringContainsString("╭── $appName v$appVersion", $obOutput);
        self::assertStringContainsString($commandName, $obOutput);
        self::assertStringContainsString($description, $obOutput);
        self::assertStringNotContainsString('Help:', $obOutput);
    }

    public function testHelp(): void
    {
        $text = 'A command to get help for a specific command.';

        self::assertSame($text, HelpCommand::help()->getText());
        self::assertSame($text, HelpCommand::help()->getFormattedText());
    }

    /**
     * The help text.
     */
    public function getHelpText(): MessageContract
    {
        return new Message(text: 'Help Command Output');
    }

    public function testRunWithARouteThatDeclaresNoCommandOption(): void
    {
        $collection = $this->createMock(RouteCollectionContract::class);
        $collection->expects($this->once())
            ->method('has')
            ->with('')
            ->willReturn(false);

        $route = new Route(
            name: 'help',
            description: 'Help for a command',
            handler: static fn (): OutputContract => new PlainOutput(),
        );

        $command = new HelpCommand(
            config: new CliConfig(),
            route: $route,
            collection: $collection,
            outputFactory: $this->makeOutputFactory(),
        );

        $output = $command->run();

        self::assertSame(ExitCode::ERROR, $output->getExitCode());
    }

    private function makeOutputFactory(): OutputFactoryContract
    {
        $outputFactory = $this->createMock(OutputFactoryContract::class);
        $outputFactory->expects($this->once())->method('createOutput')->willReturn(new PlainOutput());

        return $outputFactory;
    }

    /**
     * Build a help route that declares the command option and carries the provided value.
     */
    private function makeHelpRoute(string $commandName): RouteContract
    {
        return new Route(
            name: 'help',
            description: 'Help for a command',
            handler: static fn (): OutputContract => new PlainOutput(),
            options: [
                new OptionParameter(name: 'command', description: 'The name of the command')
                    ->withOptions(new Option('command', $commandName)),
            ],
        );
    }
}
