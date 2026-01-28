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

use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRoutePathException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Class ProcessorTest.
 */
class ProcessorTest extends TestCase
{
    public function testStaticRoute(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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
        self::assertSame('/^\/(?<value>[a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteInvalidPath(): void
    {
        $this->expectException(InvalidRoutePathException::class);

        $processor = new Processor();

        $route = new Route(
            path: '/{val}',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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
        self::assertSame('/^(?:\/)?(?<optional>[a-zA-Z]+)?$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteWithNonCaptureParam(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{noncapture}',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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
}
