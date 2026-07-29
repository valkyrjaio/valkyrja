<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Data;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Fixtures\Http\Middleware\ResponseSentMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ResponseSentMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteDispatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteDispatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteMatchedMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\SendingResponseMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\SendingResponseMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ThrowableCaughtMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\ThrowableCaughtMiddlewareFixture;
use Valkyrja\Tests\Fixtures\Http\Routing\Handler\RouteHandlerFixture;
use Valkyrja\Tests\Fixtures\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Fixtures\Http\Struct\IndexedParsedBodyRequestStructEnum;
use Valkyrja\Tests\Fixtures\Http\Struct\IndexedResponseStructEnum;
use Valkyrja\Tests\Fixtures\Http\Struct\ResponseStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Route service.
 */
final class RouteTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $path = '/';
        $name = 'route';

        $route = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
        );

        self::assertSame($path, $route->getPath());
        self::assertSame($name, $route->getName());
        self::assertSame([RequestMethod::HEAD, RequestMethod::GET], $route->getRequestMethods());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getSendingResponseMiddleware());
        self::assertEmpty($route->getResponseSentMiddleware());
        self::assertFalse($route->hasRequestStruct());
        self::assertFalse($route->hasResponseStruct());
    }

    public function testConstructor(): void
    {
        $path                        = '/';
        $name                        = 'route';
        $handler                     = RouteHandlerFixture::handle(...);
        $methods                     = [RequestMethod::HEAD, RequestMethod::POST];
        $routeMatchedMiddleware      = [RouteMatchedMiddlewareFixture::class];
        $routeDispatchedMiddleware   = [RouteDispatchedMiddlewareFixture::class];
        $throwableCaughtMiddleware   = [ThrowableCaughtMiddlewareFixture::class];
        $sendingResponseMiddleware   = [SendingResponseMiddlewareFixture::class];
        $responseSentMiddleware      = [ResponseSentMiddlewareFixture::class];
        $requestStruct               = IndexedJsonRequestStructEnum::first;
        $responseStruct              = ResponseStructEnum::first;

        $route = new Route(
            path: $path,
            name: $name,
            handler: $handler,
            requestMethods: $methods,
            routeMatchedMiddleware: $routeMatchedMiddleware,
            routeDispatchedMiddleware: $routeDispatchedMiddleware,
            throwableCaughtMiddleware: $throwableCaughtMiddleware,
            sendingResponseMiddleware: $sendingResponseMiddleware,
            responseSentMiddleware: $responseSentMiddleware,
            requestStruct: $requestStruct,
            responseStruct: $responseStruct,
        );

        self::assertSame($path, $route->getPath());
        self::assertSame($name, $route->getName());
        self::assertSame($handler, $route->getHandler());
        self::assertSame($methods, $route->getRequestMethods());
        self::assertSame($routeMatchedMiddleware, $route->getRouteMatchedMiddleware());
        self::assertSame($routeDispatchedMiddleware, $route->getRouteDispatchedMiddleware());
        self::assertSame($throwableCaughtMiddleware, $route->getThrowableCaughtMiddleware());
        self::assertSame($sendingResponseMiddleware, $route->getSendingResponseMiddleware());
        self::assertSame($responseSentMiddleware, $route->getResponseSentMiddleware());
        self::assertSame($requestStruct, $route->getRequestStruct());
        self::assertSame($responseStruct, $route->getResponseStruct());
    }

    public function testPath(): void
    {
        $path  = '/';
        $path2 = '/another';
        $name  = 'route';

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
        );
        $route2 = $route->withPath($path2);
        $route3 = $route->withAddedPath('version');
        $route4 = $route2->withAddedPath('/more');
        $route5 = $route->withPath($path2);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertNotSame($route2, $route4);
        self::assertNotSame($route, $route5);
        self::assertNotSame($route2, $route5);
        self::assertSame($path, $route->getPath());
        self::assertSame($path2, $route2->getPath());
        self::assertSame('/version', $route3->getPath());
        self::assertSame("$path2/more", $route4->getPath());
        self::assertSame($path2, $route5->getPath());
    }

    public function testName(): void
    {
        $path  = '/';
        $name  = 'route';
        $name2 = 'route2';

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
        );
        $route2 = $route->withName($name2);
        $route3 = $route->withAddedName('.version');
        $route4 = $route2->withAddedName('.more');
        $route5 = $route->withName($name2);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertNotSame($route2, $route4);
        self::assertNotSame($route, $route5);
        self::assertNotSame($route2, $route5);
        self::assertSame($name, $route->getName());
        self::assertSame($name2, $route2->getName());
        self::assertSame("$name.version", $route3->getName());
        self::assertSame("$name2.more", $route4->getName());
        self::assertSame($name2, $route5->getName());
    }

    public function testHandler(): void
    {
        $path = '/';
        $name = 'route';

        $handler  = RouteHandlerFixture::handle(...);
        $handler2 = static fn (): string => 'test2';
        $handler3 = static fn (): string => 'test3';

        $route  = new Route(path: $path, name: $name, handler: $handler);
        $route2 = $route->withHandler($handler2);
        $route3 = $route2->withHandler($handler3);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertNotSame($route2, $route3);
        self::assertSame($handler, $route->getHandler());
        self::assertSame($handler2, $route2->getHandler());
        self::assertSame($handler3, $route3->getHandler());
    }

    public function testRequestMethods(): void
    {
        $path           = '/';
        $name           = 'route';
        $defaultMethods = [RequestMethod::HEAD, RequestMethod::GET];
        $methods        = [RequestMethod::GET, RequestMethod::POST];
        $methods2       = [RequestMethod::PUT, RequestMethod::POST];

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
        );
        $route2 = $route->withRequestMethods(...$methods);
        $route3 = $route->withRequestMethods(...$methods2);
        $route4 = $route->withRequestMethods(RequestMethod::DELETE);
        $route5 = $route->withAddedRequestMethods(RequestMethod::DELETE);
        $route6 = $route->withAddedRequestMethods(...$methods);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertNotSame($route, $route4);
        self::assertNotSame($route, $route5);
        self::assertNotSame($route, $route6);
        self::assertTrue($route->hasRequestMethod(RequestMethod::HEAD));
        self::assertTrue($route->hasRequestMethod(RequestMethod::GET));
        self::assertFalse($route->hasRequestMethod(RequestMethod::POST));
        self::assertFalse($route->hasRequestMethod(RequestMethod::PUT));
        self::assertFalse($route->hasRequestMethod(RequestMethod::DELETE));
        self::assertFalse($route->hasRequestMethod(RequestMethod::OPTIONS));
        self::assertFalse($route->hasRequestMethod(RequestMethod::TRACE));
        self::assertFalse($route->hasRequestMethod(RequestMethod::CONNECT));
        self::assertFalse($route->hasRequestMethod(RequestMethod::PATCH));
        self::assertTrue($route2->hasRequestMethod(RequestMethod::POST));
        self::assertTrue($route2->hasRequestMethod(RequestMethod::GET));
        self::assertFalse($route2->hasRequestMethod(RequestMethod::HEAD));
        self::assertFalse($route2->hasRequestMethod(RequestMethod::PUT));
        self::assertFalse($route2->hasRequestMethod(RequestMethod::DELETE));
        self::assertFalse($route2->hasRequestMethod(RequestMethod::OPTIONS));
        self::assertFalse($route2->hasRequestMethod(RequestMethod::TRACE));
        self::assertFalse($route2->hasRequestMethod(RequestMethod::CONNECT));
        self::assertFalse($route2->hasRequestMethod(RequestMethod::PATCH));
        self::assertSame($defaultMethods, $route->getRequestMethods());
        self::assertSame($methods, $route2->getRequestMethods());
        self::assertSame($methods2, $route3->getRequestMethods());
        self::assertSame([RequestMethod::DELETE], $route4->getRequestMethods());
        self::assertSame([RequestMethod::HEAD, RequestMethod::GET, RequestMethod::DELETE], $route5->getRequestMethods());
        self::assertSame([RequestMethod::HEAD, RequestMethod::GET, RequestMethod::POST], $route6->getRequestMethods());
    }

    public function testRouteMatchedMiddleware(): void
    {
        $path = '/';
        $name = 'route';

        $middleware  = RouteMatchedMiddlewareFixture::class;
        $middleware2 = RouteMatchedMiddlewareChangedFixture::class;

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
            routeMatchedMiddleware: [$middleware]
        );
        $route2 = $route->withRouteMatchedMiddleware($middleware2);
        $route3 = $route->withAddedRouteMatchedMiddleware($middleware2);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertSame([$middleware], $route->getRouteMatchedMiddleware());
        self::assertSame([$middleware2], $route2->getRouteMatchedMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getRouteMatchedMiddleware());
    }

    public function testRouteDispatchedMiddleware(): void
    {
        $path = '/';
        $name = 'route';

        $middleware  = RouteDispatchedMiddlewareFixture::class;
        $middleware2 = RouteDispatchedMiddlewareChangedFixture::class;

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
            routeDispatchedMiddleware: [$middleware]
        );
        $route2 = $route->withRouteDispatchedMiddleware($middleware2);
        $route3 = $route->withAddedRouteDispatchedMiddleware($middleware2);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertSame([$middleware], $route->getRouteDispatchedMiddleware());
        self::assertSame([$middleware2], $route2->getRouteDispatchedMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getRouteDispatchedMiddleware());
    }

    public function testThrowableCaughtMiddleware(): void
    {
        $path = '/';
        $name = 'route';

        $middleware  = ThrowableCaughtMiddlewareFixture::class;
        $middleware2 = ThrowableCaughtMiddlewareChangedFixture::class;

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
            throwableCaughtMiddleware: [$middleware]
        );
        $route2 = $route->withThrowableCaughtMiddleware($middleware2);
        $route3 = $route->withAddedThrowableCaughtMiddleware($middleware2);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertSame([$middleware], $route->getThrowableCaughtMiddleware());
        self::assertSame([$middleware2], $route2->getThrowableCaughtMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getThrowableCaughtMiddleware());
    }

    public function testSendingResponseMiddleware(): void
    {
        $path = '/';
        $name = 'route';

        $middleware  = SendingResponseMiddlewareFixture::class;
        $middleware2 = SendingResponseMiddlewareChangedFixture::class;

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
            sendingResponseMiddleware: [$middleware]
        );
        $route2 = $route->withSendingResponseMiddleware($middleware2);
        $route3 = $route->withAddedSendingResponseMiddleware($middleware2);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertSame([$middleware], $route->getSendingResponseMiddleware());
        self::assertSame([$middleware2], $route2->getSendingResponseMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getSendingResponseMiddleware());
    }

    public function testResponseSentMiddleware(): void
    {
        $path = '/';
        $name = 'route';

        $middleware  = ResponseSentMiddlewareFixture::class;
        $middleware2 = ResponseSentMiddlewareChangedFixture::class;

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
            responseSentMiddleware: [$middleware]
        );
        $route2 = $route->withResponseSentMiddleware($middleware2);
        $route3 = $route->withAddedResponseSentMiddleware($middleware2);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertSame([$middleware], $route->getResponseSentMiddleware());
        self::assertSame([$middleware2], $route2->getResponseSentMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getResponseSentMiddleware());
    }

    public function testRequestStruct(): void
    {
        $path = '/';
        $name = 'route';

        $requestStruct  = IndexedJsonRequestStructEnum::first;
        $requestStruct2 = IndexedParsedBodyRequestStructEnum::first;

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
            requestStruct: $requestStruct
        );
        $route2 = $route->withRequestStruct($requestStruct2);

        self::assertNotSame($route, $route2);
        self::assertSame($requestStruct, $route->getRequestStruct());
        self::assertSame($requestStruct2, $route2->getRequestStruct());
    }

    public function testResponseStruct(): void
    {
        $path = '/';
        $name = 'route';

        $responseStruct  = ResponseStructEnum::first;
        $responseStruct2 = IndexedResponseStructEnum::first;

        $route  = new Route(
            path: $path,
            name: $name,
            handler: RouteHandlerFixture::handle(...),
            responseStruct: $responseStruct
        );
        $route2 = $route->withResponseStruct($responseStruct2);

        self::assertNotSame($route, $route2);
        self::assertSame($responseStruct, $route->getResponseStruct());
        self::assertSame($responseStruct2, $route2->getResponseStruct());
    }
}
