<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Server\Middleware\RouteMatched;

use JsonException;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Server\Middleware\RouteMatched\ResponseStructMiddleware;
use Valkyrja\Tests\Fixtures\Http\Struct\IndexedResponseStructEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Class ResponseStructMiddlewareTest.
 */
final class ResponseStructMiddlewareTest extends TestCase
{
    public function testRouteDispatchedNoResponseStruct(): void
    {
        $request  = new ServerRequest();
        $response = new JsonResponse();
        $route    = new Route(
            path: '/',
            name: 'route',
            handler: static fn (): null => null,
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
            handler: static fn (): null => null,
            responseStruct: IndexedResponseStructEnum::first
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
            handler: static fn (): null => null,
            responseStruct: IndexedResponseStructEnum::first
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
