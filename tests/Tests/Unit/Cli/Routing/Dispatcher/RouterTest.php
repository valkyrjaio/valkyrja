<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Dispatcher;

use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Routing\Collection\RouteCollection;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingArgumentValuesValidationException;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingInvalidOptionWithValueException;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingOptionValuesValidationException;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Router service.
 */
final class RouterTest extends TestCase
{
    public static function dispatch(ContainerContract $container, RouteContract $route): Output
    {
        return new Output(exitCode: ExitCode::SUCCESS);
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
        $collection = new RouteCollection();
        $router     = new Router(collection: $collection);
        $input      = new Input(commandName: 'test-command');

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch']
        );
        $collection->add($command);

        $output = $router->dispatch($input);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testRouteFoundWithRouteMatchedMiddleware(): void
    {
        $collection = new RouteCollection();
        $input      = new Input(commandName: 'test-command');

        $handler = $this->createMock(RouteMatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeMatched')
            ->with($input, self::anything())
            ->willReturnArgument(1);

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch']
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
        $collection = new RouteCollection();
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
            handler: [self::class, 'dispatch']
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
        $collection = new RouteCollection();
        $input      = new Input(commandName: 'test-command');

        $handler = $this->createMock(RouteDispatchedHandlerContract::class);
        $handler->expects($this->once())
            ->method('routeDispatched')
            ->with($input, self::anything(), self::anything())
            ->willReturnArgument(1);

        $command = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch']
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
            handler: [self::class, 'dispatch']
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
            handler: [self::class, 'dispatch'],
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
            handler: [self::class, 'dispatch'],
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

    public function testDispatchRouteBindsOptionByShortName(): void
    {
        $container = new Container();
        $router    = new Router(container: $container);
        $option    = new Option(name: 'f');
        $input     = new Input(commandName: 'test-command', options: [$option]);

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            options: [
                new OptionParameter(
                    name: 'force',
                    description: 'Force the command',
                    shortNames: ['f'],
                ),
            ]
        );

        $router->dispatchRoute($input, $route);

        $routeAfterOutput = $container->get(RouteContract::class);

        self::assertSame([$option], $routeAfterOutput->getOption('force')->getOptions());
    }

    public function testDispatchRouteBindsMultipleOptionsToArrayOption(): void
    {
        $container = new Container();
        $router    = new Router(container: $container);
        $optionA   = new Option(name: 'tag', value: 'a');
        $optionB   = new Option(name: 'tag', value: 'b');
        $input     = new Input(commandName: 'test-command', options: [$optionA, $optionB]);

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            options: [
                new OptionParameter(
                    name: 'tag',
                    description: 'A repeatable tag',
                    valueMode: OptionValueMode::ARRAY,
                ),
            ]
        );

        $router->dispatchRoute($input, $route);

        $routeAfterOutput = $container->get(RouteContract::class);

        self::assertSame([$optionA, $optionB], $routeAfterOutput->getOption('tag')->getOptions());
    }

    public function testDispatchRouteLeavesUnmatchedOptionParameterEmpty(): void
    {
        $container = new Container();
        $router    = new Router(container: $container);
        $input     = new Input(commandName: 'test-command', options: [new Option(name: 'other')]);

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            options: [
                new OptionParameter(
                    name: 'unused',
                    description: 'Never provided',
                ),
            ]
        );

        $router->dispatchRoute($input, $route);

        $routeAfterOutput = $container->get(RouteContract::class);

        self::assertSame([], $routeAfterOutput->getOption('unused')->getOptions());
    }

    public function testDispatchRouteThrowsWhenNoneFlagReceivesValue(): void
    {
        $this->expectException(CliRoutingInvalidOptionWithValueException::class);

        $router = new Router();
        $input  = new Input(commandName: 'test-command', options: [new Option(name: 'flag', value: 'nope')]);

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            options: [
                new OptionParameter(
                    name: 'flag',
                    description: 'A valueless flag',
                    valueMode: OptionValueMode::NONE,
                ),
            ]
        );

        $router->dispatchRoute($input, $route);
    }

    public function testDispatchRouteThrowsWhenRequiredOptionMissing(): void
    {
        $this->expectException(CliRoutingOptionValuesValidationException::class);

        $router = new Router();
        $input  = new Input(commandName: 'test-command');

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            options: [
                new OptionParameter(
                    name: 'required',
                    description: 'A required option',
                    mode: OptionMode::REQUIRED,
                ),
            ]
        );

        $router->dispatchRoute($input, $route);
    }

    public function testDispatchRouteThrowsWhenDefaultOptionReceivesMultiple(): void
    {
        $this->expectException(CliRoutingOptionValuesValidationException::class);

        $router = new Router();
        $input  = new Input(
            commandName: 'test-command',
            options: [new Option(name: 'single', value: 'a'), new Option(name: 'single', value: 'b')]
        );

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            options: [
                new OptionParameter(
                    name: 'single',
                    description: 'A single-value option',
                    valueMode: OptionValueMode::DEFAULT,
                ),
            ]
        );

        $router->dispatchRoute($input, $route);
    }

    public function testDispatchRouteWithFewerArgumentsThanParameters(): void
    {
        $container = new Container();
        $router    = new Router(container: $container);
        $argument  = new Argument(value: 'only');
        $input     = new Input(commandName: 'test-command', arguments: [$argument]);

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            arguments: [
                new ArgumentParameter(name: 'first', description: 'First argument'),
                new ArgumentParameter(name: 'second', description: 'Second argument'),
            ]
        );

        $router->dispatchRoute($input, $route);

        $routeAfterOutput = $container->get(RouteContract::class);

        self::assertSame([$argument], $routeAfterOutput->getArgument('first')->getArguments());
        self::assertSame([], $routeAfterOutput->getArgument('second')->getArguments());
    }

    public function testDispatchRouteThrowsWhenRequiredArgumentMissing(): void
    {
        $this->expectException(CliRoutingArgumentValuesValidationException::class);

        $router = new Router();
        $input  = new Input(commandName: 'test-command');

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            arguments: [
                new ArgumentParameter(
                    name: 'required',
                    description: 'A required argument',
                    mode: ArgumentMode::REQUIRED,
                ),
            ]
        );

        $router->dispatchRoute($input, $route);
    }

    public function testDispatchRouteArrayArgumentConsumesRemaining(): void
    {
        $container = new Container();
        $router    = new Router(container: $container);
        $first     = new Argument(value: 'a');
        $restB     = new Argument(value: 'b');
        $restC     = new Argument(value: 'c');
        $input     = new Input(commandName: 'test-command', arguments: [$first, $restB, $restC]);

        $route = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch'],
            arguments: [
                new ArgumentParameter(name: 'first', description: 'First argument'),
                new ArgumentParameter(
                    name: 'rest',
                    description: 'Remaining arguments',
                    valueMode: ArgumentValueMode::ARRAY
                ),
            ]
        );

        $router->dispatchRoute($input, $route);

        $routeAfterOutput = $container->get(RouteContract::class);

        self::assertSame([$first], $routeAfterOutput->getArgument('first')->getArguments());
        self::assertSame([$restB, $restC], $routeAfterOutput->getArgument('rest')->getArguments());
    }

    public function testHelpRoute(): void
    {
        $collection = new RouteCollection();
        $router     = new Router(collection: $collection);
        $input      = new Input(commandName: 'help');

        $command = new Route(
            name: 'help',
            description: 'Help Command',
            handler: [self::class, 'dispatch']
        );
        $collection->add($command);

        $output = $router->dispatch($input);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }

    public function testHelpRouteWithSpecificRoute(): void
    {
        $collection = new RouteCollection();
        $router     = new Router(collection: $collection);
        $input      = new Input(commandName: 'help', arguments: [new Argument(value: 'test-command')]);

        $command = new Route(
            name: 'help',
            description: 'Help Command',
            handler: [self::class, 'dispatch']
        );
        $collection->add($command);

        $command2 = new Route(
            name: 'test-command',
            description: 'Test Command',
            handler: [self::class, 'dispatch']
        );
        $collection->add($command2);

        $output = $router->dispatch($input);

        self::assertSame(ExitCode::SUCCESS, $output->getExitCode());
    }
}
