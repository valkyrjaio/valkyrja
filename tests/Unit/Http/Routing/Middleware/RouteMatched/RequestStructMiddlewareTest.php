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

namespace Valkyrja\Tests\Unit\Http\Routing\Middleware\RouteMatched;

use JsonException;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\JsonServerRequest;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Middleware\RouteMatched\RequestStructMiddleware;
use Valkyrja\Tests\Classes\Http\Struct\IndexedJsonRequestStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Class RequestStructMiddlewareTest.
 */
class RequestStructMiddlewareTest extends TestCase
{
    public function testRouteMatchedNoRequestStruct(): void
    {
        $request = new ServerRequest();
        $route   = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
        );
        $handler = new RouteMatchedHandler();

        $middleware = new RequestStructMiddleware();

        $responseAfterMiddleware = $middleware->routeMatched(
            request: $request,
            route: $route,
            handler: $handler
        );

        self::assertSame($route, $responseAfterMiddleware);
    }

    /**
     * @throws JsonException
     */
    public function testRouteDispatchedExtraDataPayloadTooLarge(): void
    {
        $request = new JsonServerRequest(
            parsedJson: [
                1 => 'test',
                2 => 'test2',
                3 => 'test3',
                4 => 'test4',
            ]
        );
        $route   = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            requestStruct: IndexedJsonRequestStructEnum::class
        );
        $handler = new RouteMatchedHandler();

        $middleware = new RequestStructMiddleware();

        $responseAfterMiddleware = $middleware->routeMatched(
            request: $request,
            route: $route,
            handler: $handler
        );

        self::assertInstanceOf(ResponseContract::class, $responseAfterMiddleware);
        self::assertSame(StatusCode::PAYLOAD_TOO_LARGE, $responseAfterMiddleware->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    public function testRouteDispatchedBadRequest(): void
    {
        $request = new JsonServerRequest(
            parsedJson: [
                1 => 'test',
                2 => 'test2',
                3 => 'test3',
            ]
        );
        $route   = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            requestStruct: IndexedJsonRequestStructEnum::class
        );
        $handler = new RouteMatchedHandler();

        $middleware = new RequestStructMiddleware();

        $responseAfterMiddleware = $middleware->routeMatched(
            request: $request,
            route: $route,
            handler: $handler
        );

        self::assertInstanceOf(ResponseContract::class, $responseAfterMiddleware);
        self::assertSame(StatusCode::BAD_REQUEST, $responseAfterMiddleware->getStatusCode());
    }

    /**
     * @throws JsonException
     */
    public function testRouteDispatched(): void
    {
        $request = new JsonServerRequest(
            parsedJson: [
                1 => 'test',
                2 => 200,
                3 => 'test3',
            ]
        );
        $route   = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            requestStruct: IndexedJsonRequestStructEnum::class
        );
        $handler = new RouteMatchedHandler();

        $middleware = new RequestStructMiddleware();

        $responseAfterMiddleware = $middleware->routeMatched(
            request: $request,
            route: $route,
            handler: $handler
        );

        self::assertSame($route, $responseAfterMiddleware);
    }
}
