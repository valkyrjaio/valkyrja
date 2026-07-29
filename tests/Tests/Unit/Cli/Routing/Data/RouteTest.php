<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Data;

use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingInvalidArgumentNameException;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingInvalidHelpTextCallableException;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingInvalidOptionNameException;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingNoHelpTextException;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ProcessExitingMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ProcessExitingMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteDispatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteDispatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\RouteMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ThrowableCaughtMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Cli\Middleware\ThrowableCaughtMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Cli\Routing\Handler\RouteHandlerFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class RouteTest extends TestCase
{
    /** @var non-empty-string */
    protected const string NAME = 'name';
    /** @var non-empty-string */
    protected const string DESCRIPTION = 'Test description';

    public function testDefaults(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $handler     = RouteHandlerFixture::handle(...);

        $route = new Route(
            name: $name,
            description: $description,
            handler: $handler
        );

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertFalse($route->hasHelpText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertFalse($route->hasArgument('test'));
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());
    }

    public function testConstructor(): void
    {
        $name                              = self::NAME;
        $description                       = self::DESCRIPTION;
        $helpText                          = [$this, 'getHelpText'];
        $handler                           = RouteHandlerFixture::handle(...);
        $options                           = [new OptionParameter(name: 'test', description: 'test description')];
        $arguments                         = [new ArgumentParameter(name: 'test', description: 'test description')];
        $routeMatchedMiddleware            = [RouteMatchedMiddlewareFixture::class];
        $routeDispatchedMiddleware         = [RouteDispatchedMiddlewareFixture::class];
        $throwableCaughtMiddleware         = [ThrowableCaughtMiddlewareFixture::class];
        $processExitingMiddleware          = [ProcessExitingMiddlewareFixture::class];

        $route = new Route(...[
            'name'                      => $name,
            'description'               => $description,
            'handler'                   => $handler,
            'helpText'                  => $helpText,
            'routeMatchedMiddleware'    => $routeMatchedMiddleware,
            'routeDispatchedMiddleware' => $routeDispatchedMiddleware,
            'throwableCaughtMiddleware' => $throwableCaughtMiddleware,
            'processExitingMiddleware'  => $processExitingMiddleware,
            'arguments'                 => $arguments,
            'options'                   => $options,
        ]);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertTrue($route->hasArguments());
        self::assertNotNull($route->getArgument('test'));
        self::assertSame($arguments, $route->getArguments());
        self::assertTrue($route->hasOptions());
        self::assertNotNull($route->getOption('test'));
        self::assertSame($options, $route->getOptions());
        self::assertSame($routeMatchedMiddleware, $route->getRouteMatchedMiddleware());
        self::assertSame($routeDispatchedMiddleware, $route->getRouteDispatchedMiddleware());
        self::assertSame($throwableCaughtMiddleware, $route->getThrowableCaughtMiddleware());
        self::assertSame($processExitingMiddleware, $route->getProcessExitingMiddleware());
    }

    public function testName(): void
    {
        $name                      = self::NAME;
        $name2                     = 'name2';
        $description               = self::DESCRIPTION;
        $helpText                  = [$this, 'getHelpText'];
        $handler                   = RouteHandlerFixture::handle(...);

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText
        );
        $route2 = $route->withName($name2);

        self::assertNotSame($route, $route2);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name2, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());
    }

    public function testDescription(): void
    {
        $name         = self::NAME;
        $description  = self::DESCRIPTION;
        $description2 = 'description2';
        $helpText     = [$this, 'getHelpText'];
        $handler      = RouteHandlerFixture::handle(...);

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText
        );
        $route2 = $route->withDescription($description2);

        self::assertNotSame($route, $route2);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description2, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());
    }

    public function testHelpText(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $helpText2   = [$this, 'getHelpText2'];
        $handler     = RouteHandlerFixture::handle(...);

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText
        );
        $route2 = $route->withHelpText($helpText2);

        self::assertNotSame($route, $route2);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText2, $route2->getHelpText());
        self::assertSame($helpText2()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());
    }

    public function testHelpTextThrowsWhenNotExists(): void
    {
        $this->expectException(CliRoutingNoHelpTextException::class);
        $this->expectExceptionMessage('No help text has been set for this route');

        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $handler     = RouteHandlerFixture::handle(...);

        $route = new Route(
            name: $name,
            description: $description,
            handler: $handler
        );

        $route->getHelpText();
    }

    public function testHelpTextMessageThrowsWhenNotExists(): void
    {
        $this->expectException(CliRoutingNoHelpTextException::class);
        $this->expectExceptionMessage('No help text has been set for this route');

        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $handler     = RouteHandlerFixture::handle(...);

        $route = new Route(
            name: $name,
            description: $description,
            handler: $handler
        );

        $route->getHelpText();
    }

    public function testHandler(): void
    {
        $name         = self::NAME;
        $description  = self::DESCRIPTION;
        $helpText     = [$this, 'getHelpText'];
        $handler      = static fn (ContainerContract $container): string => 'pie';
        $handler2     = static fn (ContainerContract $container): string => 'pie2';

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText
        );
        $route2 = $route->withHandler($handler2);

        self::assertNotSame($route, $route2);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler2, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());
    }

    public function testArguments(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $handler     = RouteHandlerFixture::handle(...);
        $argument    = new ArgumentParameter(name: 'name', description: 'description');
        $argument2   = new ArgumentParameter(name: 'name2', description: 'description');
        $argument3   = new ArgumentParameter(name: 'name3', description: 'description');

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
            arguments: [$argument]
        );
        $route2 = $route->withArguments($argument2);
        $route3 = $route->withAddedArguments($argument2);
        $route4 = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
        )->withAddedArguments($argument3);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertTrue($route->hasArguments());
        self::assertSame([$argument], $route->getArguments());
        self::assertTrue($route->hasArgument('name'));
        self::assertSame($argument, $route->getArgument('name'));
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertTrue($route2->hasArguments());
        self::assertSame([$argument2], $route2->getArguments());
        self::assertTrue($route2->hasArgument('name2'));
        self::assertSame($argument2, $route2->getArgument('name2'));
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($handler, $route3->getHandler());
        self::assertTrue($route3->hasArguments());
        self::assertSame([$argument, $argument2], $route3->getArguments());
        self::assertTrue($route3->hasArgument('name'));
        self::assertTrue($route3->hasArgument('name2'));
        self::assertSame($argument, $route3->getArgument('name'));
        self::assertSame($argument2, $route3->getArgument('name2'));
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getProcessExitingMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($handler, $route4->getHandler());
        self::assertTrue($route4->hasArguments());
        self::assertSame([$argument3], $route4->getArguments());
        self::assertTrue($route4->hasArgument('name3'));
        self::assertSame($argument3, $route4->getArgument('name3'));
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getProcessExitingMiddleware());
    }

    public function testGetArgumentThrowsWhenNonExistent(): void
    {
        $this->expectException(CliRoutingInvalidArgumentNameException::class);
        $this->expectExceptionMessage('The argument `name` was not found');

        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $handler     = RouteHandlerFixture::handle(...);

        $route = new Route(
            name: $name,
            description: $description,
            handler: $handler,
        );

        $route->getArgument('name');
    }

    public function testOptions(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $handler     = RouteHandlerFixture::handle(...);
        $option      = new OptionParameter(name: 'name', description: 'description');
        $option2     = new OptionParameter(name: 'name2', description: 'description');
        $option3     = new OptionParameter(name: 'name3', description: 'description');

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
            options: [$option]
        );
        $route2 = $route->withOptions($option2);
        $route3 = $route->withAddedOptions($option2);
        $route4 = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
        )->withAddedOptions($option3);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertTrue($route->hasOptions());
        self::assertSame([$option], $route->getOptions());
        self::assertTrue($route->hasOption('name'));
        self::assertSame($option, $route->getOption('name'));
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertTrue($route2->hasOptions());
        self::assertSame([$option2], $route2->getOptions());
        self::assertTrue($route2->hasOption('name2'));
        self::assertSame($option2, $route2->getOption('name2'));
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($handler, $route3->getHandler());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertTrue($route3->hasOptions());
        self::assertSame([$option, $option2], $route3->getOptions());
        self::assertTrue($route3->hasOption('name'));
        self::assertTrue($route3->hasOption('name2'));
        self::assertSame($option, $route3->getOption('name'));
        self::assertSame($option2, $route3->getOption('name2'));
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getProcessExitingMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($handler, $route4->getHandler());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertTrue($route4->hasOptions());
        self::assertSame([$option3], $route4->getOptions());
        self::assertTrue($route4->hasOption('name3'));
        self::assertSame($option3, $route4->getOption('name3'));
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getProcessExitingMiddleware());
    }

    public function testGetOptionThrowsWhenNonExistent(): void
    {
        $this->expectException(CliRoutingInvalidOptionNameException::class);
        $this->expectExceptionMessage('The option `name` was not found');

        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $handler     = RouteHandlerFixture::handle(...);

        $route = new Route(
            name: $name,
            description: $description,
            handler: $handler,
        );

        $route->getOption('name');
    }

    public function testRouteMatchedMiddleware(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $handler     = RouteHandlerFixture::handle(...);
        $middleware  = RouteMatchedMiddlewareFixture::class;
        $middleware2 = RouteMatchedMiddlewareChangedFixture::class;

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
            routeMatchedMiddleware: [$middleware]
        );
        $route2 = $route->withRouteMatchedMiddleware($middleware2);
        $route3 = $route->withAddedRouteMatchedMiddleware($middleware2);
        $route4 = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
        )->withAddedRouteMatchedMiddleware($middleware);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertSame([$middleware], $route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertSame([$middleware2], $route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($handler, $route3->getHandler());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertSame([$middleware, $middleware2], $route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getProcessExitingMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($handler, $route4->getHandler());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertSame([$middleware], $route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getProcessExitingMiddleware());
    }

    public function testRouteDispatchedMiddleware(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $handler     = RouteHandlerFixture::handle(...);
        $middleware  = RouteDispatchedMiddlewareFixture::class;
        $middleware2 = RouteDispatchedMiddlewareChangedFixture::class;

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
            routeDispatchedMiddleware: [$middleware]
        );
        $route2 = $route->withRouteDispatchedMiddleware($middleware2);
        $route3 = $route->withAddedRouteDispatchedMiddleware($middleware2);
        $route4 = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
        )->withAddedRouteDispatchedMiddleware($middleware);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertSame([$middleware], $route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertSame([$middleware2], $route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($handler, $route3->getHandler());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getProcessExitingMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($handler, $route4->getHandler());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertSame([$middleware], $route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getProcessExitingMiddleware());
    }

    public function testThrowableCaughtMiddleware(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $handler     = RouteHandlerFixture::handle(...);
        $middleware  = ThrowableCaughtMiddlewareFixture::class;
        $middleware2 = ThrowableCaughtMiddlewareChangedFixture::class;

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
            throwableCaughtMiddleware: [$middleware]
        );
        $route2 = $route->withThrowableCaughtMiddleware($middleware2);
        $route3 = $route->withAddedThrowableCaughtMiddleware($middleware2);
        $route4 = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
        )->withAddedThrowableCaughtMiddleware($middleware);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertSame([$middleware], $route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertSame([$middleware2], $route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getProcessExitingMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($handler, $route3->getHandler());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getProcessExitingMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($handler, $route4->getHandler());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertSame([$middleware], $route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getProcessExitingMiddleware());
    }

    public function testProcessExitingMiddleware(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $handler     = RouteHandlerFixture::handle(...);
        $middleware  = ProcessExitingMiddlewareFixture::class;
        $middleware2 = ProcessExitingMiddlewareChangedFixture::class;

        $route  = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
            processExitingMiddleware: [$middleware]
        );
        $route2 = $route->withProcessExitingMiddleware($middleware2);
        $route3 = $route->withAddedProcessExitingMiddleware($middleware2);
        $route4 = new Route(
            name: $name,
            description: $description,
            handler: $handler,
            helpText: $helpText,
        )->withAddedProcessExitingMiddleware($middleware);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($handler, $route->getHandler());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertSame([$middleware], $route->getProcessExitingMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($handler, $route2->getHandler());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertSame([$middleware2], $route2->getProcessExitingMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($handler, $route3->getHandler());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getProcessExitingMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($handler, $route4->getHandler());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertSame([$middleware], $route4->getProcessExitingMiddleware());
    }

    /**
     * Get help text.
     */
    public function getHelpText(): MessageContract
    {
        return new Message('help text');
    }

    /**
     * Get help text 2.
     */
    public function getHelpText2(): MessageContract
    {
        return new Message('help text 2');
    }

    public function testHelpTextWithClosureThrowsException(): void
    {
        $this->expectException(CliRoutingInvalidHelpTextCallableException::class);
        $this->expectExceptionMessage('Help text must be a callable array');

        new Route(
            name: self::NAME,
            description: self::DESCRIPTION,
            handler: RouteHandlerFixture::handle(...),
            helpText: static fn (): MessageContract => new Message('closure help text')
        );
    }
}
