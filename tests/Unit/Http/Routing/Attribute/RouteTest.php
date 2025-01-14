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
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Model\Parameter\Parameter;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Struct\QueryRequestStructEnum;
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
        $route = new Route();

        self::assertSame('/', $route->getPath());
        self::assertNull($route->getName());
        self::assertSame(
            [
                RequestMethod::GET,
                RequestMethod::HEAD,
            ],
            $route->getMethods()
        );
    }

    public function testPath(): void
    {
        $value = '/test';
        $route = new Route(path: $value);

        self::assertSame($value, $route->getPath());
    }

    public function testName(): void
    {
        $value = 'test';
        $route = new Route(name: $value);

        self::assertSame($value, $route->getName());
    }

    public function testMethods(): void
    {
        $value = [
            RequestMethod::POST,
        ];
        $route = new Route(methods: $value);

        self::assertSame($value, $route->getMethods());
    }

    public function testParameters(): void
    {
        $value = [
            new Parameter(),
        ];
        $route = new Route(parameters: $value);

        self::assertSame($value, $route->getParameters());
    }

    public function testSecure(): void
    {
        $value = true;
        $route = new Route(secure: $value);

        self::assertSame($value, $route->isSecure());
    }

    public function testTo(): void
    {
        $value = '/path';
        $route = new Route(to: $value);

        self::assertSame($value, $route->getTo());
    }

    public function testCode(): void
    {
        $value = StatusCode::ACCEPTED;
        $route = new Route(code: $value);

        self::assertSame($value, $route->getCode());
    }

    public function testRequestStruct(): void
    {
        $value = QueryRequestStructEnum::class;
        $route = new Route(requestStruct: $value);

        self::assertSame($value, $route->getRequestStruct());
    }

    public function testMatchedMiddleware(): void
    {
        $value = [RouteMatchedMiddlewareClass::class];
        $route = new Route(matchedMiddleware: $value);

        self::assertSame($value, $route->getMiddleware());
        self::assertSame($value, $route->getMatchedMiddleware());
    }

    public function testDispatchedMiddleware(): void
    {
        $value = [RouteDispatchedMiddlewareClass::class];
        $route = new Route(dispatchedMiddleware: $value);

        self::assertSame($value, $route->getMiddleware());
        self::assertSame($value, $route->getDispatchedMiddleware());
    }

    public function testExceptionMiddleware(): void
    {
        $value = [ThrowableCaughtMiddlewareClass::class];
        $route = new Route(exceptionMiddleware: $value);

        self::assertSame($value, $route->getMiddleware());
        self::assertSame($value, $route->getExceptionMiddleware());
    }

    public function testSendingMiddleware(): void
    {
        $value = [SendingResponseMiddlewareClass::class];
        $route = new Route(sendingMiddleware: $value);

        self::assertSame($value, $route->getMiddleware());
        self::assertSame($value, $route->getSendingMiddleware());
    }

    public function testTerminatedMiddleware(): void
    {
        $value = [TerminatedMiddlewareClass::class];
        $route = new Route(terminatedMiddleware: $value);

        self::assertSame($value, $route->getMiddleware());
        self::assertSame($value, $route->getTerminatedMiddleware());
    }
}
