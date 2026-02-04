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

namespace Valkyrja\Tests\Unit\Cli\Routing\Data;

use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Tests\Classes\Cli\Middleware\ExitedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ExitedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteDispatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ThrowableCaughtMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Cli\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class RouteTest extends TestCase
{
    /** @var non-empty-string */
    protected const string NAME = 'name';
    /** @var non-empty-string */
    protected const string DESCRIPTION = 'Test description';

    public function testDefaults(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');

        $route = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch
        );

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertNull($route->getHelpText());
        self::assertNull($route->getHelpTextMessage());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertNull($route->getArgument('test'));
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());
    }

    public function testConstructor(): void
    {
        $name                      = self::NAME;
        $description               = self::DESCRIPTION;
        $helpText                  = [$this, 'getHelpText'];
        $dispatch                  = new MethodDispatch(class: self::class, method: '__construct');
        $options                   = [new OptionParameter(name: 'test', description: 'test description')];
        $arguments                 = [new ArgumentParameter(name: 'test', description: 'test description')];
        $routeMatchedMiddleware    = [RouteMatchedMiddlewareClass::class];
        $routeDispatchedMiddleware = [RouteDispatchedMiddlewareClass::class];
        $throwableCaughtMiddleware = [ThrowableCaughtMiddlewareClass::class];
        $exitedMiddleware          = [ExitedMiddlewareClass::class];

        $route = new Route(...[
            'name'                      => $name,
            'description'               => $description,
            'dispatch'                  => $dispatch,
            'helpText'                  => $helpText,
            'routeMatchedMiddleware'    => $routeMatchedMiddleware,
            'routeDispatchedMiddleware' => $routeDispatchedMiddleware,
            'throwableCaughtMiddleware' => $throwableCaughtMiddleware,
            'exitedMiddleware'          => $exitedMiddleware,
            'arguments'                 => $arguments,
            'options'                   => $options,
        ]);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertTrue($route->hasArguments());
        self::assertNotNull($route->getArgument('test'));
        self::assertSame($arguments, $route->getArguments());
        self::assertTrue($route->hasOptions());
        self::assertNotNull($route->getOption('test'));
        self::assertSame($options, $route->getOptions());
        self::assertSame($routeMatchedMiddleware, $route->getRouteMatchedMiddleware());
        self::assertSame($routeDispatchedMiddleware, $route->getRouteDispatchedMiddleware());
        self::assertSame($throwableCaughtMiddleware, $route->getThrowableCaughtMiddleware());
        self::assertSame($exitedMiddleware, $route->getExitedMiddleware());
    }

    public function testName(): void
    {
        $name        = self::NAME;
        $name2       = 'name2';
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText
        );
        $route2 = $route->withName($name2);

        self::assertNotSame($route, $route2);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name2, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());
    }

    public function testDescription(): void
    {
        $name         = self::NAME;
        $description  = self::DESCRIPTION;
        $description2 = 'description2';
        $helpText     = [$this, 'getHelpText'];
        $dispatch     = new MethodDispatch(class: self::class, method: '__construct');

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText
        );
        $route2 = $route->withDescription($description2);

        self::assertNotSame($route, $route2);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description2, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());
    }

    public function testHelpText(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $helpText2   = [$this, 'getHelpText2'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText
        );
        $route2 = $route->withHelpText($helpText2);

        self::assertNotSame($route, $route2);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText2, $route2->getHelpText());
        self::assertSame($helpText2()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());
    }

    public function testDispatch(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');
        $dispatch2   = new MethodDispatch(class: self::class, method: 'setUp');

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText
        );
        $route2 = $route->withDispatch($dispatch2);

        self::assertNotSame($route, $route2);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch2, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());
    }

    public function testArguments(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');
        $argument    = new ArgumentParameter(name: 'name', description: 'description');
        $argument2   = new ArgumentParameter(name: 'name2', description: 'description');
        $argument3   = new ArgumentParameter(name: 'name3', description: 'description');

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
            arguments: [$argument]
        );
        $route2 = $route->withArguments($argument2);
        $route3 = $route->withAddedArguments($argument2);
        $route4 = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
        )->withAddedArguments($argument3);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertTrue($route->hasArguments());
        self::assertSame([$argument], $route->getArguments());
        self::assertSame($argument, $route->getArgument('name'));
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertTrue($route2->hasArguments());
        self::assertSame([$argument2], $route2->getArguments());
        self::assertSame($argument2, $route2->getArgument('name2'));
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route3->getDispatch());
        self::assertTrue($route3->hasArguments());
        self::assertSame([$argument, $argument2], $route3->getArguments());
        self::assertSame($argument, $route3->getArgument('name'));
        self::assertSame($argument2, $route3->getArgument('name2'));
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getExitedMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route4->getDispatch());
        self::assertTrue($route4->hasArguments());
        self::assertSame([$argument3], $route4->getArguments());
        self::assertSame($argument3, $route4->getArgument('name3'));
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getExitedMiddleware());
    }

    public function testOptions(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');
        $option      = new OptionParameter(name: 'name', description: 'description');
        $option2     = new OptionParameter(name: 'name2', description: 'description');
        $option3     = new OptionParameter(name: 'name3', description: 'description');

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
            options: [$option]
        );
        $route2 = $route->withOptions($option2);
        $route3 = $route->withAddedOptions($option2);
        $route4 = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
        )->withAddedOptions($option3);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertTrue($route->hasOptions());
        self::assertSame([$option], $route->getOptions());
        self::assertSame($option, $route->getOption('name'));
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertTrue($route2->hasOptions());
        self::assertSame([$option2], $route2->getOptions());
        self::assertSame($option2, $route2->getOption('name2'));
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route3->getDispatch());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertTrue($route3->hasOptions());
        self::assertSame([$option, $option2], $route3->getOptions());
        self::assertSame($option, $route3->getOption('name'));
        self::assertSame($option2, $route3->getOption('name2'));
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getExitedMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route4->getDispatch());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertTrue($route4->hasOptions());
        self::assertSame([$option3], $route4->getOptions());
        self::assertSame($option3, $route4->getOption('name3'));
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getExitedMiddleware());
    }

    public function testRouteMatchedMiddleware(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');
        $middleware  = RouteMatchedMiddlewareClass::class;
        $middleware2 = RouteMatchedMiddlewareChangedClass::class;

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
            routeMatchedMiddleware: [$middleware]
        );
        $route2 = $route->withRouteMatchedMiddleware($middleware2);
        $route3 = $route->withAddedRouteMatchedMiddleware($middleware2);
        $route4 = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
        )->withAddedRouteMatchedMiddleware($middleware);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertSame([$middleware], $route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertSame([$middleware2], $route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route3->getDispatch());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertSame([$middleware, $middleware2], $route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getExitedMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route4->getDispatch());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertSame([$middleware], $route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getExitedMiddleware());
    }

    public function testRouteDispatchedMiddleware(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');
        $middleware  = RouteDispatchedMiddlewareClass::class;
        $middleware2 = RouteDispatchedMiddlewareChangedClass::class;

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
            routeDispatchedMiddleware: [$middleware]
        );
        $route2 = $route->withRouteDispatchedMiddleware($middleware2);
        $route3 = $route->withAddedRouteDispatchedMiddleware($middleware2);
        $route4 = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
        )->withAddedRouteDispatchedMiddleware($middleware);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertSame([$middleware], $route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertSame([$middleware2], $route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route3->getDispatch());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getExitedMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route4->getDispatch());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertSame([$middleware], $route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getExitedMiddleware());
    }

    public function testThrowableCaughtMiddleware(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');
        $middleware  = ThrowableCaughtMiddlewareClass::class;
        $middleware2 = ThrowableCaughtMiddlewareChangedClass::class;

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
            throwableCaughtMiddleware: [$middleware]
        );
        $route2 = $route->withThrowableCaughtMiddleware($middleware2);
        $route3 = $route->withAddedThrowableCaughtMiddleware($middleware2);
        $route4 = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
        )->withAddedThrowableCaughtMiddleware($middleware);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertSame([$middleware], $route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertSame([$middleware2], $route2->getThrowableCaughtMiddleware());
        self::assertEmpty($route2->getExitedMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route3->getDispatch());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getThrowableCaughtMiddleware());
        self::assertEmpty($route3->getExitedMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route4->getDispatch());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertSame([$middleware], $route4->getThrowableCaughtMiddleware());
        self::assertEmpty($route4->getExitedMiddleware());
    }

    public function testExitedMiddleware(): void
    {
        $name        = self::NAME;
        $description = self::DESCRIPTION;
        $helpText    = [$this, 'getHelpText'];
        $dispatch    = new MethodDispatch(class: self::class, method: '__construct');
        $middleware  = ExitedMiddlewareClass::class;
        $middleware2 = ExitedMiddlewareChangedClass::class;

        $route  = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
            exitedMiddleware: [$middleware]
        );
        $route2 = $route->withExitedMiddleware($middleware2);
        $route3 = $route->withAddedExitedMiddleware($middleware2);
        $route4 = new Route(
            name: $name,
            description: $description,
            dispatch: $dispatch,
            helpText: $helpText,
        )->withAddedExitedMiddleware($middleware);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route2, $route3);

        self::assertSame($name, $route->getName());
        self::assertSame($description, $route->getDescription());
        self::assertSame($helpText, $route->getHelpText());
        self::assertSame($helpText()->getText(), $route->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route->getDispatch());
        self::assertFalse($route->hasArguments());
        self::assertEmpty($route->getArguments());
        self::assertFalse($route->hasOptions());
        self::assertEmpty($route->getOptions());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertSame([$middleware], $route->getExitedMiddleware());

        self::assertSame($name, $route2->getName());
        self::assertSame($description, $route2->getDescription());
        self::assertSame($helpText, $route2->getHelpText());
        self::assertSame($helpText()->getText(), $route2->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route2->getDispatch());
        self::assertFalse($route2->hasArguments());
        self::assertEmpty($route2->getArguments());
        self::assertFalse($route2->hasOptions());
        self::assertEmpty($route2->getOptions());
        self::assertEmpty($route2->getRouteMatchedMiddleware());
        self::assertEmpty($route2->getRouteDispatchedMiddleware());
        self::assertEmpty($route2->getThrowableCaughtMiddleware());
        self::assertSame([$middleware2], $route2->getExitedMiddleware());

        self::assertSame($name, $route3->getName());
        self::assertSame($description, $route3->getDescription());
        self::assertSame($helpText, $route3->getHelpText());
        self::assertSame($helpText()->getText(), $route3->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route3->getDispatch());
        self::assertFalse($route3->hasArguments());
        self::assertEmpty($route3->getArguments());
        self::assertFalse($route3->hasOptions());
        self::assertEmpty($route3->getOptions());
        self::assertEmpty($route3->getRouteMatchedMiddleware());
        self::assertEmpty($route3->getRouteDispatchedMiddleware());
        self::assertEmpty($route3->getThrowableCaughtMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getExitedMiddleware());

        self::assertSame($name, $route4->getName());
        self::assertSame($description, $route4->getDescription());
        self::assertSame($helpText, $route4->getHelpText());
        self::assertSame($helpText()->getText(), $route4->getHelpTextMessage()->getText());
        self::assertSame($dispatch, $route4->getDispatch());
        self::assertFalse($route4->hasArguments());
        self::assertEmpty($route4->getArguments());
        self::assertFalse($route4->hasOptions());
        self::assertEmpty($route4->getOptions());
        self::assertEmpty($route4->getRouteMatchedMiddleware());
        self::assertEmpty($route4->getRouteDispatchedMiddleware());
        self::assertEmpty($route4->getThrowableCaughtMiddleware());
        self::assertSame([$middleware], $route4->getExitedMiddleware());
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
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Help text must be a callable array');

        new Route(
            name: self::NAME,
            description: self::DESCRIPTION,
            dispatch: new MethodDispatch(class: self::class, method: '__construct'),
            helpText: fn (): MessageContract => new Message('closure help text')
        );
    }
}
