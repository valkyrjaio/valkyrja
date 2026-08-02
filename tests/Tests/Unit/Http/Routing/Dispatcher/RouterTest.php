<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Dispatcher;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Uri\Factory\UriFactory;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Routing\Collection\RouteCollection;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Dispatcher\Router;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Fixtures\Http\Middleware\RouteNotMatchedMiddlewareChangedFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Router service.
 */
final class RouterTest extends TestCase
{
    public static function dispatch(): ResponseContract
    {
        return new Response(statusCode: StatusCode::I_AM_A_TEAPOT);
    }

    public static function invalidDispatch(): string
    {
        return 'invalid';
    }

    public function testNotFound(): void
    {
        $router  = new Router();
        $request = new ServerRequest(
            uri: UriFactory::fromString('/non-existing-route'),
            method: RequestMethod::GET
        );

        $response = $router->dispatch(request: $request);

        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode());
    }

    public function testNotFoundWithRouteNotMatchedMiddleware(): void
    {
        RouteNotMatchedMiddlewareChangedFixture::resetCounter();

        $routeNotMatchedHandler = new RouteNotMatchedHandler();
        $routeNotMatchedHandler->add(RouteNotMatchedMiddlewareChangedFixture::class);

        $router  = new Router(routeNotMatchedHandler: $routeNotMatchedHandler);
        $request = new ServerRequest(
            uri: UriFactory::fromString('/non-existing-route'),
            method: RequestMethod::GET
        );

        $router->dispatch(request: $request);

        self::assertSame(1, RouteNotMatchedMiddlewareChangedFixture::getAndResetCounter());
    }

    public function testMethodNotAllowed(): void
    {
        $collection = new RouteCollection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher);
        $request    = new ServerRequest(
            uri: UriFactory::fromString('/'),
            method: RequestMethod::POST
        );

        $route = new Route(
            path: '/',
            name: 'route',
            handler: static fn (): null => null,
        );
        $collection->add($route);

        $response = $router->dispatch(request: $request);

        self::assertSame(StatusCode::METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    public function testMethodNotAllowedRouteNotMatchedMiddleware(): void
    {
        RouteNotMatchedMiddlewareChangedFixture::resetCounter();

        $routeNotMatchedHandler = new RouteNotMatchedHandler();
        $routeNotMatchedHandler->add(RouteNotMatchedMiddlewareChangedFixture::class);

        $collection = new RouteCollection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher, routeNotMatchedHandler: $routeNotMatchedHandler);
        $request    = new ServerRequest(
            uri: UriFactory::fromString('/'),
            method: RequestMethod::POST
        );

        $route = new Route(
            path: '/',
            name: 'route',
            handler: [self::class, 'dispatch'],
        );
        $collection->add($route);

        $router->dispatch(request: $request);

        self::assertSame(1, RouteNotMatchedMiddlewareChangedFixture::getAndResetCounter());
    }

    public function testResponseAfterRouteMatchedMiddleware(): void
    {
        RouteMatchedMiddlewareChangedFixture::resetCounter();

        $routeNotMatchedHandler = new RouteMatchedHandler();
        $routeNotMatchedHandler->add(RouteMatchedMiddlewareChangedFixture::class);

        $collection = new RouteCollection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher, routeMatchedHandler: $routeNotMatchedHandler);
        $request    = new ServerRequest(
            uri: UriFactory::fromString('/'),
            method: RequestMethod::GET
        );

        $route = new Route(
            path: '/',
            name: 'route',
            handler: [self::class, 'dispatch'],
        );
        $collection->add($route);

        $router->dispatch(request: $request);

        self::assertSame(1, RouteMatchedMiddlewareChangedFixture::getAndResetCounter());
    }

    public function testResponseAfterRouteMatchedMiddlewareFromRoute(): void
    {
        RouteMatchedMiddlewareChangedFixture::resetCounter();

        $collection = new RouteCollection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher);
        $request    = new ServerRequest(
            uri: UriFactory::fromString('/'),
            method: RequestMethod::GET
        );

        $route = new Route(
            path: '/',
            name: 'route',
            handler: [self::class, 'dispatch'],
            routeMatchedMiddleware: [RouteMatchedMiddlewareChangedFixture::class]
        );
        $collection->add($route);

        $router->dispatch(request: $request);

        self::assertSame(1, RouteMatchedMiddlewareChangedFixture::getAndResetCounter());
    }

    public function testResponseAfterRouteDispatched(): void
    {
        $collection = new RouteCollection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher);
        $request    = new ServerRequest(
            uri: UriFactory::fromString('/'),
            method: RequestMethod::GET
        );

        $route = new Route(
            path: '/',
            name: 'route',
            handler: [self::class, 'dispatch'],
        );
        $collection->add($route);

        $response = $router->dispatch(request: $request);

        self::assertSame(StatusCode::I_AM_A_TEAPOT, $response->getStatusCode());
    }
}
