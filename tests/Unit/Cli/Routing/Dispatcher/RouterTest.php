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
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Throwable\Exception\RuntimeException;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Dispatch\Data\MethodDispatch;
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

    public function testRouteNotFound(): void
    {
        $router = new Router();
        $input  = new Input(commandName: 'non-existing-command');

        $output = $router->dispatch($input);

        self::assertSame(ExitCode::ERROR, $output->getExitCode());
    }

    public function testRouteNotFoundWithRouteNotMatchedMiddleware(): void
    {
        $input   = new Input(commandName: 'non-existing-command');
        $handler = $this->createMock(RouteNotMatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeNotMatched')
            ->with($input, self::anything())
            ->willReturnArgument(1);

        $router = new Router(routeNotMatchedHandler: $handler);

        $router->dispatch($input);
    }

    public function testRouteFound(): void
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

    public function testRouteFoundWithRouteMatchedMiddleware(): void
    {
        $collection = new Collection();
        $input      = new Input(commandName: 'test-command');

        $handler = $this->createMock(RouteMatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeMatched')
            ->with($input, self::anything())
            ->willReturnArgument(1);

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $router = new Router(
            collection: $collection,
            routeMatchedHandler: $handler
        );
        $router->dispatch($input);
    }

    public function testRouteFoundWithRouteMatchedMiddlewareReturningOutput(): void
    {
        $collection = new Collection();
        $input      = new Input(commandName: 'test-command');
        $output     = new Output(exitCode: ExitCode::SUCCESS);

        $handler = $this->createMock(RouteMatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeMatched')
            ->with($input, self::anything())
            ->willReturn($output);

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $router              = new Router(
            collection: $collection,
            routeMatchedHandler: $handler
        );
        $outputAfterDispatch = $router->dispatch($input);

        self::assertSame($output, $outputAfterDispatch);
    }

    public function testRouteFoundWithRouteDispatchedMiddleware(): void
    {
        $collection = new Collection();
        $input      = new Input(commandName: 'test-command');

        $handler = $this->createMock(RouteDispatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeDispatched')
            ->with($input, self::anything(), self::anything())
            ->willReturnArgument(1);

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($command);

        $router = new Router(
            collection: $collection,
            routeDispatchedHandler: $handler
        );
        $router->dispatch($input);
    }

    public function testDispatchRoute(): void
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

    public function testDispatchRouteWithArguments(): void
    {
        $container = new Container();
        $router    = new Router(container: $container);
        $arg1      = new Argument(value: 'arg1');
        $arg2      = new Argument(value: 'arg2');
        $arg3      = new Argument(value: 'arg3');
        $inputArgs = [$arg1, $arg2, $arg3];
        $input     = new Input(commandName: 'test-command', arguments: $inputArgs);

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch']),
            arguments: [
                new ArgumentParameter(
                    name: 'arg1',
                    description: 'description',
                ),
                new ArgumentParameter(
                    name: 'argArray',
                    description: 'description',
                    valueMode: ArgumentValueMode::ARRAY
                ),
            ]
        );

        $output = $router->dispatchRoute($input, $command);

        $routeAfterOutput = $container->get(RouteContract::class);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertSame([$arg1], $routeAfterOutput->getArgument('arg1')->getArguments());
        self::assertSame([$arg2, $arg3], $routeAfterOutput->getArgument('argArray')->getArguments());
    }

    public function testDispatchRouteWithOptions(): void
    {
        $container    = new Container();
        $router       = new Router(container: $container);
        $inputOptions = [new Option(name: 'option', value: 'value')];
        $input        = new Input(commandName: 'test-command', options: $inputOptions);

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch']),
            options: [
                new OptionParameter(
                    name: 'option',
                    description: 'option description',
                ),
            ]
        );

        $output = $router->dispatchRoute($input, $route);

        $routeAfterOutput = $container->get(RouteContract::class);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
        self::assertSame($inputOptions, $routeAfterOutput->getOption('option')->getOptions());
    }

    public function testHelpRoute(): void
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

    public function testHelpRouteWithSpecificRoute(): void
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

    public function testInvalidOutput(): void
    {
        $this->expectException(RuntimeException::class);

        $router = new Router();
        $input  = new Input(commandName: 'test-command');

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            helpText: new Message('Help text'),
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'invalidDispatch'])
        );

        $router->dispatchRoute($input, $route);
    }
}
