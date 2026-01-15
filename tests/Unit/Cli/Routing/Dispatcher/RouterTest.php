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

namespace Valkyrja\Tests\Unit\Cli\Routing\Dispatcher;

use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteNotMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Router service.
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
        RouteNotMatchedMiddlewareChangedClass::resetCounter();

        $commandNotMatchedHandler = new RouteNotMatchedHandler();
        $commandNotMatchedHandler->add(RouteNotMatchedMiddlewareChangedClass::class);

        $router = new Router(routeNotMatchedHandler: $commandNotMatchedHandler);
        $input  = new Input(commandName: 'non-existing-command');

        $router->dispatch($input);

        self::assertSame(1, RouteNotMatchedMiddlewareChangedClass::getAndResetCounter());
    }

    public function testCommandFound(): void
    {
        $collection = new Collection();
        $router     = new Router(collection: $collection);
        $input      = new Input(commandName: 'test-command');

        $command = new Route(
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
        RouteMatchedMiddlewareChangedClass::resetCounter();

        $commandMatchedHandler = new RouteMatchedHandler();
        $commandMatchedHandler->add(RouteMatchedMiddlewareChangedClass::class);

        $collection = new Collection();
        $router     = new Router(
            collection: $collection,
            routeMatchedHandler: $commandMatchedHandler
        );
        $input      = new Input(commandName: 'test-command');

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $router->dispatch($input);

        self::assertSame(1, RouteMatchedMiddlewareChangedClass::getAndResetCounter());
    }

    public function testDispatchCommand(): void
    {
        $router = new Router();
        $input  = new Input(commandName: 'test-command');

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );

        $output = $router->dispatchRoute($input, $command);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testDispatchCommandWithArguments(): void
    {
        $router = new Router();
        $input  = new Input(commandName: 'test-command', arguments: [new Argument(value: 'arg1')]);

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );

        $output = $router->dispatchRoute($input, $command);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testDispatchCommandWithOptions(): void
    {
        $router = new Router();
        $input  = new Input(commandName: 'test-command', options: [new Option(name: 'option', value: 'value')]);

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );

        $output = $router->dispatchRoute($input, $command);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testHelpCommand(): void
    {
        $collection = new Collection();
        $router     = new Router(collection: $collection);
        $input      = new Input(commandName: 'help');

        $command = new Route(
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

        $command = new Route(
            name: 'help',
            description: 'Help Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $command2 = new Route(
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
