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

namespace Valkyrja\Tests\Unit\Http\Routing\Processor;

use Valkyrja\Auth\Entity\User;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Orm\Data\EntityCast;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Data\Cast;

/**
 * Class ProcessorTest.
 *
 * @author Melech Mizrachi
 */
class ProcessorTest extends TestCase
{
    public function testStaticRoute(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/',
            name: 'route'
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
    }

    public function testStaticRouteNoPreceedingSlash(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: 'some/path',
            name: 'route'
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame('/some/path', $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
    }

    public function testDynamicRoute(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{value}',
            name: 'route',
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ]
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/([a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteInvalidPath(): void
    {
        $this->expectException(InvalidRoutePathException::class);

        $processor = new Processor();

        $route = new Route(
            path: '/{val}',
            name: 'route',
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ]
        );

        $processor->route($route);
    }

    public function testDynamicRouteWithRegexAlreadySet(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{value}',
            name: 'route',
            regex: Regex::ALPHA,
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ]
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        // Shouldn't change, even if it's wrong
        self::assertSame($route->getRegex(), $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteWithOptionalParam(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{optional?}',
            name: 'route',
            parameters: [
                new Parameter(
                    name: 'optional',
                    regex: Regex::ALPHA,
                    isOptional: true
                ),
            ]
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^(?:\/)?([a-zA-Z]+)?$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteWithNonCaptureParam(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{noncapture}',
            name: 'route',
            parameters: [
                new Parameter(
                    name: 'noncapture',
                    regex: Regex::ALPHA,
                    shouldCapture: false
                ),
            ]
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/(?:[a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteEntityParameter(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{value}',
            name: 'route',
            dispatch: new MethodDispatch(
                class: Route::class,
                method: 'test',
                dependencies: [
                    User::class,
                ]
            ),
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA,
                    cast: new Cast(type: User::class, convert: false)
                ),
            ],
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/([a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
        self::assertEmpty($routeAfterProcessing->getDispatch()->getDependencies());
    }

    public function testDynamicRouteEntityParameterWithNoDependencies(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{value}',
            name: 'route',
            dispatch: new MethodDispatch(
                class: Route::class,
                method: 'test',
                dependencies: [
                    Route::class,
                    User::class,
                ]
            ),
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA,
                    cast: new Cast(type: User::class, convert: false)
                ),
            ],
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/([a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
        self::assertSame([Route::class], $routeAfterProcessing->getDispatch()->getDependencies());
    }

    public function testDynamicRouteEntityParameterWithDependencies(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{value}',
            name: 'route',
            dispatch: new MethodDispatch(
                class: Route::class,
                method: 'test',
                dependencies: []
            ),
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA,
                    cast: new Cast(type: User::class, convert: false)
                ),
            ],
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/([a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
        self::assertEmpty($routeAfterProcessing->getDispatch()->getDependencies());
    }

    public function testDynamicRouteEntityParameterWithEntityCast(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{value}',
            name: 'route',
            dispatch: new MethodDispatch(
                class: Route::class,
                method: 'test',
                dependencies: [
                    User::class,
                ]
            ),
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA,
                    cast: new EntityCast(type: User::class, column: 'id', convert: false)
                ),
            ]
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/([a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
        self::assertEmpty($routeAfterProcessing->getDispatch()->getDependencies());
    }

    public function testDynamicRouteEntityParameterWithEntityCastWithInvalidColumn(): void
    {
        $this->expectException(InvalidRoutePathException::class);

        $processor = new Processor();

        $route = new Route(
            path: '/{value}',
            name: 'route',
            dispatch: new MethodDispatch(
                class: Route::class,
                method: 'test',
                dependencies: [
                    User::class,
                ]
            ),
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA,
                    cast: new EntityCast(type: User::class, column: 'invalidcolumn', convert: false)
                ),
            ]
        );

        $processor->route($route);
    }
}
