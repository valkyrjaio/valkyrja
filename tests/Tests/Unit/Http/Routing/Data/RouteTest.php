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

namespace Valkyrja\Tests\Unit\Http\Routing\Data;

use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\IndexedParsedBodyRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\IndexedResponseStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
        );

        self::assertSame($path, $route->getPath());
        self::assertSame($name, $route->getName());
        self::assertSame(self::class, $route->getDispatch()->getClass());
        self::assertSame('dispatch', $route->getDispatch()->getMethod());
        self::assertSame([RequestMethod::HEAD, RequestMethod::GET], $route->getRequestMethods());
        self::assertNull($route->getRegex());
        self::assertEmpty($route->getParameters());
        self::assertEmpty($route->getRouteMatchedMiddleware());
        self::assertEmpty($route->getRouteDispatchedMiddleware());
        self::assertEmpty($route->getThrowableCaughtMiddleware());
        self::assertEmpty($route->getSendingResponseMiddleware());
        self::assertEmpty($route->getTerminatedMiddleware());
        self::assertNull($route->getRequestStruct());
        self::assertNull($route->getResponseStruct());
    }

    public function testConstructor(): void
    {
        $path                      = '/';
        $name                      = 'route';
        $dispatch                  = new MethodDispatch(self::class, 'dispatch');
        $methods                   = [RequestMethod::HEAD, RequestMethod::POST];
        $regex                     = 'regex';
        $parameters                = [new Parameter(name: 'test', regex: Regex::ALPHA)];
        $routeMatchedMiddleware    = [RouteMatchedMiddlewareClass::class];
        $routeDispatchedMiddleware = [RouteDispatchedMiddlewareClass::class];
        $throwableCaughtMiddleware = [ThrowableCaughtMiddlewareClass::class];
        $sendingResponseMiddleware = [SendingResponseMiddlewareClass::class];
        $terminatedMiddleware      = [TerminatedMiddlewareClass::class];
        $requestStruct             = IndexedJsonRequestStructEnum::class;
        $responseStruct            = ResponseStructEnum::class;

        $route = new Route(...[
            'path'                      => $path,
            'name'                      => $name,
            'dispatch'                  => $dispatch,
            'requestMethods'            => $methods,
            'regex'                     => $regex,
            'parameters'                => $parameters,
            'routeMatchedMiddleware'    => $routeMatchedMiddleware,
            'routeDispatchedMiddleware' => $routeDispatchedMiddleware,
            'throwableCaughtMiddleware' => $throwableCaughtMiddleware,
            'sendingResponseMiddleware' => $sendingResponseMiddleware,
            'terminatedMiddleware'      => $terminatedMiddleware,
            'requestStruct'             => $requestStruct,
            'responseStruct'            => $responseStruct,
        ]);

        self::assertSame($path, $route->getPath());
        self::assertSame($name, $route->getName());
        self::assertSame(self::class, $route->getDispatch()->getClass());
        self::assertSame('dispatch', $route->getDispatch()->getMethod());
        self::assertSame($methods, $route->getRequestMethods());
        self::assertSame($regex, $route->getRegex());
        self::assertSame($parameters, $route->getParameters());
        self::assertSame($routeMatchedMiddleware, $route->getRouteMatchedMiddleware());
        self::assertSame($routeDispatchedMiddleware, $route->getRouteDispatchedMiddleware());
        self::assertSame($throwableCaughtMiddleware, $route->getThrowableCaughtMiddleware());
        self::assertSame($sendingResponseMiddleware, $route->getSendingResponseMiddleware());
        self::assertSame($terminatedMiddleware, $route->getTerminatedMiddleware());
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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

    public function testDispatch(): void
    {
        $path = '/';
        $name = 'route';

        $dispatch  = new MethodDispatch(class: self::class, method: 'test');
        $dispatch2 = new MethodDispatch(class: self::class, method: 'test2');
        $dispatch3 = new MethodDispatch(class: self::class, method: 'test3');

        $route  = new Route(path: $path, name: $name, dispatch: $dispatch);
        $route2 = $route->withDispatch($dispatch2);
        $route3 = $route2->withDispatch($dispatch3);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertNotSame($route2, $route3);
        self::assertSame($dispatch, $route->getDispatch());
        self::assertSame($dispatch2, $route2->getDispatch());
        self::assertSame($dispatch3, $route3->getDispatch());
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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

    public function testParameters(): void
    {
        $path = '/';
        $name = 'route';

        $parameter  = new Parameter(name: 'test1', regex: Regex::ALPHA);
        $parameter2 = new Parameter(name: 'test2', regex: Regex::ALPHA);
        $parameter3 = new Parameter(name: 'test3', regex: Regex::ALPHA);
        $parameter4 = new Parameter(name: 'test4', regex: Regex::ALPHA);

        $route  = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            parameters: [$parameter]
        );
        $route2 = $route->withParameters($parameter2);
        $route3 = $route->withParameters($parameter3);
        $route4 = $route->withAddedParameters($parameter2);
        $route5 = $route->withAddedParameters($parameter3, $parameter4);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertNotSame($route, $route4);
        self::assertNotSame($route, $route5);
        self::assertSame([$parameter], $route->getParameters());
        self::assertSame([$parameter2], $route2->getParameters());
        self::assertSame([$parameter3], $route3->getParameters());
        self::assertSame([$parameter, $parameter2], $route4->getParameters());
        self::assertSame([$parameter, $parameter3, $parameter4], $route5->getParameters());
    }

    public function testRouteMatchedMiddleware(): void
    {
        $path = '/';
        $name = 'route';

        $middleware  = RouteMatchedMiddlewareClass::class;
        $middleware2 = RouteMatchedMiddlewareChangedClass::class;

        $route  = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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

        $middleware  = RouteDispatchedMiddlewareClass::class;
        $middleware2 = RouteDispatchedMiddlewareChangedClass::class;

        $route  = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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

        $middleware  = ThrowableCaughtMiddlewareClass::class;
        $middleware2 = ThrowableCaughtMiddlewareChangedClass::class;

        $route  = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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

        $middleware  = SendingResponseMiddlewareClass::class;
        $middleware2 = SendingResponseMiddlewareChangedClass::class;

        $route  = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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

    public function testTerminatedMiddleware(): void
    {
        $path = '/';
        $name = 'route';

        $middleware  = TerminatedMiddlewareClass::class;
        $middleware2 = TerminatedMiddlewareChangedClass::class;

        $route  = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            terminatedMiddleware: [$middleware]
        );
        $route2 = $route->withTerminatedMiddleware($middleware2);
        $route3 = $route->withAddedTerminatedMiddleware($middleware2);

        self::assertNotSame($route, $route2);
        self::assertNotSame($route, $route3);
        self::assertSame([$middleware], $route->getTerminatedMiddleware());
        self::assertSame([$middleware2], $route2->getTerminatedMiddleware());
        self::assertSame([$middleware, $middleware2], $route3->getTerminatedMiddleware());
    }

    public function testRequestStruct(): void
    {
        $path = '/';
        $name = 'route';

        $requestStruct  = IndexedJsonRequestStructEnum::class;
        $requestStruct2 = IndexedParsedBodyRequestStructEnum::class;

        $route  = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
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

        $responseStruct  = ResponseStructEnum::class;
        $responseStruct2 = IndexedResponseStructEnum::class;

        $route  = new Route(
            path: $path,
            name: $name,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            responseStruct: $responseStruct
        );
        $route2 = $route->withResponseStruct($responseStruct2);

        self::assertNotSame($route, $route2);
        self::assertSame($responseStruct, $route->getResponseStruct());
        self::assertSame($responseStruct2, $route2->getResponseStruct());
    }
}
