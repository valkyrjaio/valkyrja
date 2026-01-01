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

namespace Valkyrja\Tests\Unit\Http\Routing\Middleware;

use JsonException;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Middleware\ResponseStructMiddleware;
use Valkyrja\Tests\Classes\Http\Struct\IndexedResponseStructEnum;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Class ResponseStructMiddlewareTest.
 */
class ResponseStructMiddlewareTest extends TestCase
{
    public function testRouteDispatchedNoResponseStruct(): void
    {
        $request  = new ServerRequest();
        $response = new JsonResponse();
        $route    = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
        );
        $handler  = new RouteDispatchedHandler();

        $middleware = new ResponseStructMiddleware();

        $responseAfterMiddleware = $middleware->routeDispatched(
            request: $request,
            response: $response,
            route: $route,
            handler: $handler
        );

        self::assertSame($response, $responseAfterMiddleware);
    }

    public function testRouteDispatchedNotJsonResponse(): void
    {
        $request  = new ServerRequest();
        $response = new Response();
        $route    = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            responseStruct: IndexedResponseStructEnum::class
        );
        $handler  = new RouteDispatchedHandler();

        $middleware = new ResponseStructMiddleware();

        $responseAfterMiddleware = $middleware->routeDispatched(
            request: $request,
            response: $response,
            route: $route,
            handler: $handler
        );

        self::assertSame($response, $responseAfterMiddleware);
    }

    /**
     * @throws JsonException
     */
    public function testRouteDispatched(): void
    {
        $request  = new ServerRequest();
        $response = new JsonResponse(data: ['first' => 'test', 'second' => 'test2', 'third' => 'test3']);
        $route    = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            responseStruct: IndexedResponseStructEnum::class
        );
        $handler  = new RouteDispatchedHandler();

        $middleware = new ResponseStructMiddleware();

        $responseAfterMiddleware = $middleware->routeDispatched(
            request: $request,
            response: $response,
            route: $route,
            handler: $handler
        );

        self::assertNotSame($response, $responseAfterMiddleware);
        self::assertInstanceOf(JsonResponse::class, $responseAfterMiddleware);
        self::assertSame([1 => 'test', 2 => 'test2', 3 => 'test3'], $responseAfterMiddleware->getBodyAsJson());
    }
}
