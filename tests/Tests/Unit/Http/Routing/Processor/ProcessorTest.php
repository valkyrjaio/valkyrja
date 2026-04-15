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

use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\DynamicRoute;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRoutePathException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Class ProcessorTest.
 */
final class ProcessorTest extends TestCase
{
    public function testStaticRoute(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/',
            name: 'route',
            handler: static fn (): null => null,
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
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame('/some/path', $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
    }

    public function testDynamicRoute(): void
    {
        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{value}',
            name: 'route',
            regex: '',
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ],
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/(?<value>[a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteInvalidPath(): void
    {
        $this->expectException(HttpRoutingInvalidRoutePathException::class);

        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{val}',
            name: 'route',
            regex: '',
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ],
            handler: static fn (): null => null,
        );

        $processor->route($route);
    }

    public function testDynamicRouteWithRegexAlreadySet(): void
    {
        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{value}',
            name: 'route',
            regex: Regex::ALPHA,
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ],
            handler: static fn (): null => null,
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

        $route = new DynamicRoute(
            path: '/{optional?}',
            name: 'route',
            regex: '',
            parameters: [
                new Parameter(
                    name: 'optional',
                    regex: Regex::ALPHA,
                    isOptional: true
                ),
            ],
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^(?:\/)?(?<optional>[a-zA-Z]+)?$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteWithNonCaptureParam(): void
    {
        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{noncapture}',
            name: 'route',
            regex: '',
            parameters: [
                new Parameter(
                    name: 'noncapture',
                    regex: Regex::ALPHA,
                    shouldCapture: false
                ),
            ],
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/(?:[a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
    }
}
