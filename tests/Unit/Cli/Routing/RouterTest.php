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

namespace Valkyrja\Tests\Unit\Cli\Routing;

use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\CommandNotMatchedHandler;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Data\Command;
use Valkyrja\Cli\Routing\Router;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Tests\Classes\Cli\Middleware\CommandMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\CommandNotMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Router service.
 *
 * @author Melech Mizrachi
 */
class RouterTest extends TestCase
{
    public static function dispatch(): Output
    {
        return new Output(exitCode: ExitCode::SUCCESS);
    }

    public static function invalidDispatch(): string
    {
        return 'invalid';
    }

    public function testCommandNotFound(): void
    {
        $router = new Router();
        $input  = new Input(commandName: 'non-existing-command');

        $output = $router->dispatch($input);

        self::assertSame(ExitCode::ERROR, $output->getExitCode());
    }

    public function testCommandNotFoundWithCommandNotMatchedMiddleware(): void
    {
        CommandNotMatchedMiddlewareChangedClass::resetCounter();

        $commandNotMatchedHandler = new CommandNotMatchedHandler();
        $commandNotMatchedHandler->add(CommandNotMatchedMiddlewareChangedClass::class);

        $router = new Router(commandNotMatchedHandler: $commandNotMatchedHandler);
        $input  = new Input(commandName: 'non-existing-command');

        $router->dispatch($input);

        self::assertSame(1, CommandNotMatchedMiddlewareChangedClass::getAndResetCounter());
    }

    public function testCommandFound(): void
    {
        $collection = new Collection();
        $router     = new Router(collection: $collection);
        $input      = new Input(commandName: 'test-command');

        $command = new Command(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $output = $router->dispatch($input);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testCommandFoundWithCommandMatchedMiddleware(): void
    {
        CommandMatchedMiddlewareChangedClass::resetCounter();

        $commandMatchedHandler = new CommandMatchedHandler();
        $commandMatchedHandler->add(CommandMatchedMiddlewareChangedClass::class);

        $collection = new Collection();
        $router     = new Router(
            collection: $collection,
            commandMatchedHandler: $commandMatchedHandler
        );
        $input      = new Input(commandName: 'test-command');

        $command = new Command(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $router->dispatch($input);

        self::assertSame(1, CommandMatchedMiddlewareChangedClass::getAndResetCounter());
    }

    public function testDispatchCommand(): void
    {
        $router = new Router();
        $input  = new Input(commandName: 'test-command');

        $command = new Command(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );

        $output = $router->dispatchCommand($input, $command);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testDispatchCommandWithArguments(): void
    {
        $router = new Router();
        $input  = new Input(commandName: 'test-command', arguments: [new Argument(value: 'arg1')]);

        $command = new Command(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );

        $output = $router->dispatchCommand($input, $command);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testDispatchCommandWithOptions(): void
    {
        $router = new Router();
        $input  = new Input(commandName: 'test-command', options: [new Option(name: 'option', value: 'value')]);

        $command = new Command(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );

        $output = $router->dispatchCommand($input, $command);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testHelpCommand(): void
    {
        $collection = new Collection();
        $router     = new Router(collection: $collection);
        $input      = new Input(commandName: 'help');

        $command = new Command(
            name: 'help',
            description: 'Help Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $output = $router->dispatch($input);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testHelpCommandWithSpecificCommand(): void
    {
        $collection = new Collection();
        $router     = new Router(collection: $collection);
        $input      = new Input(commandName: 'help', arguments: [new Argument(value: 'test-command')]);

        $command = new Command(
            name: 'help',
            description: 'Help Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $command2 = new Command(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command2);

        $output = $router->dispatch($input);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }
}
