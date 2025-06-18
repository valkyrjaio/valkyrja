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

namespace Unit\Http\Routing\Attribute;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Struct\QueryRequestStructEnum;
use Valkyrja\Tests\Classes\Http\Struct\ResponseStructEnum;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the route attribute.
 *
 * @author Melech Mizrachi
 */
class RouteTest extends TestCase
{
    public function testDefaults(): void
    {
        $route = new Route(
            path: '/',
            name: 'test'
        );

        self::assertSame('/', $route->getPath());
        self::assertSame('test', $route->getName());
        self::assertContains(RequestMethod::HEAD, $route->getRequestMethods());
        self::assertContains(RequestMethod::GET, $route->getRequestMethods());
        self::assertNotContains(RequestMethod::POST, $route->getRequestMethods());
        self::assertNotContains(RequestMethod::PUT, $route->getRequestMethods());
        self::assertNotContains(RequestMethod::PATCH, $route->getRequestMethods());
        self::assertNotContains(RequestMethod::TRACE, $route->getRequestMethods());
        self::assertNotContains(RequestMethod::OPTIONS, $route->getRequestMethods());
        self::assertNotContains(RequestMethod::DELETE, $route->getRequestMethods());
        self::assertNotContains(RequestMethod::CONNECT, $route->getRequestMethods());
    }

    public function testPath(): void
    {
        $value = '/test';
        $route = new Route(path: $value, name: 'test');

        self::assertSame($value, $route->getPath());
    }

    public function testName(): void
    {
        $value = 'test';
        $route = new Route(path: '/', name: $value);

        self::assertSame($value, $route->getName());
    }

    public function testMethods(): void
    {
        $value = [
            RequestMethod::POST,
        ];
        $route = new Route(path: '/', name: 'test', requestMethods: $value);

        self::assertSame($value, $route->getRequestMethods());
    }

    public function testParameters(): void
    {
        $value = [
            new Parameter(name: 'test', regex: Regex::ALPHA),
        ];
        $route = new Route(path: '/', name: 'test', parameters: $value);

        self::assertSame($value, $route->getParameters());
    }

    public function testRequestStruct(): void
    {
        $value = QueryRequestStructEnum::class;
        $route = new Route(path: '/', name: 'test', requestStruct: $value);

        self::assertSame($value, $route->getRequestStruct());
    }

    public function testResponseStruct(): void
    {
        $value = ResponseStructEnum::class;
        $route = new Route(path: '/', name: 'test', responseStruct: $value);

        self::assertSame($value, $route->getResponseStruct());
    }

    public function testMatchedMiddleware(): void
    {
        $value = [RouteMatchedMiddlewareClass::class];
        $route = new Route(path: '/', name: 'test', routeMatchedMiddleware: $value);

        self::assertSame($value, $route->getRouteMatchedMiddleware());
    }

    public function testDispatchedMiddleware(): void
    {
        $value = [RouteDispatchedMiddlewareClass::class];
        $route = new Route(path: '/', name: 'test', routeDispatchedMiddleware: $value);

        self::assertSame($value, $route->getRouteDispatchedMiddleware());
    }

    public function testExceptionMiddleware(): void
    {
        $value = [ThrowableCaughtMiddlewareClass::class];
        $route = new Route(path: '/', name: 'test', throwableCaughtMiddleware: $value);

        self::assertSame($value, $route->getThrowableCaughtMiddleware());
    }

    public function testSendingMiddleware(): void
    {
        $value = [SendingResponseMiddlewareClass::class];
        $route = new Route(path: '/', name: 'test', sendingResponseMiddleware: $value);

        self::assertSame($value, $route->getSendingResponseMiddleware());
    }

    public function testTerminatedMiddleware(): void
    {
        $value = [TerminatedMiddlewareClass::class];
        $route = new Route(path: '/', name: 'test', terminatedMiddleware: $value);

        self::assertSame($value, $route->getTerminatedMiddleware());
    }
}
