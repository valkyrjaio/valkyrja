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

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\OptionParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class HelpCommandTest extends TestCase
{
    public function testRunWithInvalidCommandName(): void
    {
        $output = new Output();
        $route  = $this->createMock(RouteContract::class);
        $route->expects($this->once())
            ->method('getOption')
            ->with('command')
            ->willReturn(null);
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->never())
            ->method('get');
        $version = $this->createMock(VersionCommand::class);
        $version->expects($this->never())
            ->method('run');
        $outputFactory = $this->createMock(OutputFactoryContract::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);

        $helpCommand   = new HelpCommand(
            version: $version,
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );
        $outputFromRun = $helpCommand->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertSame(ExitCode::ERROR, $outputFromRun->getExitCode());
        self::assertStringContainsString('Command name is required', $obOutput);
    }

    public function testRunWithNonExistentCommandName(): void
    {
        $commandName = 'foo';

        $output = new Output();
        $option = $this->createMock(OptionParameterContract::class);
        $option->expects($this->once())
            ->method('getFirstValue')
            ->willReturn($commandName);
        $route = $this->createMock(RouteContract::class);
        $route->expects($this->once())
            ->method('getOption')
            ->with('command')
            ->willReturn($option);
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('get')
            ->with($commandName)
            ->willReturn(null);
        $version = $this->createMock(VersionCommand::class);
        $version->expects($this->never())
            ->method('run');
        $outputFactory = $this->createMock(OutputFactoryContract::class);
        $outputFactory->expects($this->once())
            ->method('createOutput')
            ->willReturn($output);

        $helpCommand   = new HelpCommand(
            version: $version,
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
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
        $commandName = 'foo';
        $description = 'description here';
        $versionText = 'Version Command Output';
        $helpText    = 'Help Command Output';
        $helpRoute   = new Route(
            name: $commandName,
            description: $description,
            helpText: new Message(text: $helpText),
            dispatch: new MethodDispatch(class: self::class, method: '__construct'),
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

        $output = new Output();
        $option = $this->createMock(OptionParameterContract::class);
        $option->expects($this->once())
            ->method('getFirstValue')
            ->willReturn($commandName);
        $route = $this->createMock(RouteContract::class);
        $route->expects($this->once())
            ->method('getOption')
            ->with('command')
            ->willReturn($option);
        $collection = $this->createMock(CollectionContract::class);
        $collection->expects($this->once())
            ->method('get')
            ->with($commandName)
            ->willReturn($helpRoute);
        $version = $this->createMock(VersionCommand::class);
        $version->expects($this->once())
            ->method('run')
            ->willReturn($output->withMessages(new Message($versionText)));
        $outputFactory = $this->createMock(OutputFactoryContract::class);
        $outputFactory->expects($this->never())
            ->method('createOutput');

        $helpCommand   = new HelpCommand(
            version: $version,
            route: $route,
            collection: $collection,
            outputFactory: $outputFactory
        );
        $outputFromRun = $helpCommand->run();

        ob_start();
        $outputFromRun->writeMessages();
        $obOutput = ob_get_clean();

        self::assertSame(ExitCode::SUCCESS, $outputFromRun->getExitCode());
        self::assertStringContainsString('foo [options] [global options] [argument1] [argument2...]', $obOutput);
        self::assertStringContainsString($versionText, $obOutput);
        self::assertStringContainsString($commandName, $obOutput);
        self::assertStringContainsString($description, $obOutput);
        self::assertStringContainsString($helpText, $obOutput);
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
}
