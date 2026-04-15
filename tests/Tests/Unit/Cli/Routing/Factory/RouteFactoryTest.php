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

namespace Valkyrja\Tests\Unit\Cli\Routing\Factory;

use Valkyrja\Cli\Routing\Factory\RouteFactory;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Data\DynamicRoute;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Classes\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RouteFactory service.
 */
final class RouteFactoryTest extends TestCase
{
    public function testFromRouteReturnsRouteForStaticPath(): void
    {
        $handler = static fn (): null => null;
        $route   = new Route(
            path: '/users',
            name: 'users.index',
            handler: $handler,
        );

        $result = RouteFactory::fromRoute($route);

        self::assertInstanceOf(Route::class, $result);
        self::assertNotInstanceOf(DynamicRoute::class, $result);
        self::assertSame('/users', $result->getPath());
        self::assertSame('users.index', $result->getName());
        self::assertSame($handler, $result->getHandler());
    }

    public function testFromRouteReturnsDynamicRouteForPathWithParam(): void
    {
        $handler = static fn (): null => null;
        $route   = new Route(
            path: '/users/{id}',
            name: 'users.show',
            handler: $handler,
        );

        $result = RouteFactory::fromRoute($route);

        self::assertInstanceOf(DynamicRoute::class, $result);
        self::assertSame('/users/{id}', $result->getPath());
        self::assertSame('users.show', $result->getName());
        self::assertSame($handler, $result->getHandler());
    }

    public function testFromRouteCopiesParametersFromDynamicRoute(): void
    {
        $handler    = static fn (): null => null;
        $parameter  = new Parameter(name: 'id', regex: '\d+');
        $route      = new DynamicRoute(
            path: '/users/{id}',
            name: 'users.show',
            regex: '',
            parameters: [$parameter],
            handler: $handler,
        );

        $result = RouteFactory::fromRoute($route);

        self::assertInstanceOf(DynamicRoute::class, $result);
        self::assertSame([$parameter], $result->getParameters());
    }

    public function testFromRouteDynamicPathWithNonDynamicRouteHasEmptyParameters(): void
    {
        $route = new Route(
            path: '/users/{id}',
            name: 'users.show',
            handler: static fn (): null => null,
        );

        $result = RouteFactory::fromRoute($route);

        self::assertInstanceOf(DynamicRoute::class, $result);
        self::assertEmpty($result->getParameters());
    }

    public function testFromRoutePreservesAllProperties(): void
    {
        $handler               = static fn (): null => null;
        $requestStruct         = IndexedJsonRequestStructEnum::first;
        $responseStruct        = ResponseStructEnum::first;

        $route = new Route(
            path: '/posts',
            name: 'posts.index',
            handler: $handler,
            requestMethods: [RequestMethod::POST],
            requestStruct: $requestStruct,
            responseStruct: $responseStruct,
        );

        $result = RouteFactory::fromRoute($route);

        self::assertSame('/posts', $result->getPath());
        self::assertSame('posts.index', $result->getName());
        self::assertSame($handler, $result->getHandler());
        self::assertSame([RequestMethod::POST], $result->getRequestMethods());
        self::assertSame($requestStruct, $result->getRequestStruct());
        self::assertSame($responseStruct, $result->getResponseStruct());
    }

    public function testGetRequestStructFromRouteReturnsNullWhenNone(): void
    {
        $route = new Route(
            path: '/users',
            name: 'users.index',
            handler: static fn (): null => null,
        );

        self::assertNull(RouteFactory::getRequestStructFromRoute($route));
    }

    public function testGetRequestStructFromRouteReturnsStructWhenPresent(): void
    {
        $requestStruct = IndexedJsonRequestStructEnum::first;

        $route = new Route(
            path: '/users',
            name: 'users.store',
            handler: static fn (): null => null,
            requestStruct: $requestStruct,
        );

        self::assertSame($requestStruct, RouteFactory::getRequestStructFromRoute($route));
    }

    public function testGetResponseStructFromRouteReturnsNullWhenNone(): void
    {
        $route = new Route(
            path: '/users',
            name: 'users.index',
            handler: static fn (): null => null,
        );

        self::assertNull(RouteFactory::getResponseStructFromRoute($route));
    }

    public function testGetResponseStructFromRouteReturnsStructWhenPresent(): void
    {
        $responseStruct = ResponseStructEnum::first;

        $route = new Route(
            path: '/users',
            name: 'users.index',
            handler: static fn (): null => null,
            responseStruct: $responseStruct,
        );

        self::assertSame($responseStruct, RouteFactory::getResponseStructFromRoute($route));
    }
}
