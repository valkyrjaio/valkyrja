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

namespace Valkyrja\Tests\Unit\Http\Routing;

use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Http\Routing\Router;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteNotMatchedMiddlewareChangedClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Router service.
 *
 * @author Melech Mizrachi
 */
class RouterTest extends TestCase
{
    public function testNotFound(): void
    {
        $router  = new Router();
        $request = new ServerRequest(
            uri: Uri::fromString('/non-existing-route'),
            method: RequestMethod::GET
        );

        $response = $router->dispatch(request: $request);

        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode());
    }

    public function testNotFoundWithRouteNotMatchedMiddleware(): void
    {
        RouteNotMatchedMiddlewareChangedClass::resetCounter();

        $routeNotMatchedHandler = new RouteNotMatchedHandler();
        $routeNotMatchedHandler->add(RouteNotMatchedMiddlewareChangedClass::class);

        $router  = new Router(routeNotMatchedHandler: $routeNotMatchedHandler);
        $request = new ServerRequest(
            uri: Uri::fromString('/non-existing-route'),
            method: RequestMethod::GET
        );

        $router->dispatch(request: $request);

        self::assertSame(1, RouteNotMatchedMiddlewareChangedClass::getAndResetCounter());
    }

    public function testMethodNotAllowed(): void
    {
        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher);
        $request    = new ServerRequest(
            uri: Uri::fromString('/'),
            method: RequestMethod::POST
        );

        $route = new Route(path: '/', name: 'route');
        $collection->add($route);

        $response = $router->dispatch(request: $request);

        self::assertSame(StatusCode::METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    public function testMethodNotAllowedRouteNotMatchedMiddleware(): void
    {
        RouteNotMatchedMiddlewareChangedClass::resetCounter();

        $routeNotMatchedHandler = new RouteNotMatchedHandler();
        $routeNotMatchedHandler->add(RouteNotMatchedMiddlewareChangedClass::class);

        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher, routeNotMatchedHandler: $routeNotMatchedHandler);
        $request    = new ServerRequest(
            uri: Uri::fromString('/'),
            method: RequestMethod::POST
        );

        $route = new Route(path: '/', name: 'route');
        $collection->add($route);

        $router->dispatch(request: $request);

        self::assertSame(1, RouteNotMatchedMiddlewareChangedClass::getAndResetCounter());
    }

    public function testResponseAfterRouteMatchedMiddleware(): void
    {
        RouteMatchedMiddlewareChangedClass::resetCounter();

        $routeNotMatchedHandler = new RouteMatchedHandler();
        $routeNotMatchedHandler->add(RouteMatchedMiddlewareChangedClass::class);

        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher, routeMatchedHandler: $routeNotMatchedHandler);
        $request    = new ServerRequest(
            uri: Uri::fromString('/'),
            method: RequestMethod::GET
        );

        $route = new Route(path: '/', name: 'route');
        $collection->add($route);

        $router->dispatch(request: $request);

        self::assertSame(1, RouteMatchedMiddlewareChangedClass::getAndResetCounter());
    }

    public function testResponseAfterRouteMatchedMiddlewareFromRoute(): void
    {
        RouteMatchedMiddlewareChangedClass::resetCounter();

        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher);
        $request    = new ServerRequest(
            uri: Uri::fromString('/'),
            method: RequestMethod::GET
        );

        $route = new Route(
            path: '/',
            name: 'route',
            routeMatchedMiddleware: [RouteMatchedMiddlewareChangedClass::class]
        );
        $collection->add($route);

        $router->dispatch(request: $request);

        self::assertSame(1, RouteMatchedMiddlewareChangedClass::getAndResetCounter());
    }

    public function testResponseAfterRouteDispatched(): void
    {
        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher);
        $request    = new ServerRequest(
            uri: Uri::fromString('/'),
            method: RequestMethod::GET
        );

        $route = new Route(
            path: '/',
            name: 'route',
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatch'])
        );
        $collection->add($route);

        $response = $router->dispatch(request: $request);

        self::assertSame(StatusCode::I_AM_A_TEAPOT, $response->getStatusCode());
    }

    public function testResponseAfterRouteDispatchedWithInvalidDispatch(): void
    {
        $this->expectException(InvalidRouteNameException::class);

        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);
        $router     = new Router(matcher: $matcher);
        $request    = new ServerRequest(
            uri: Uri::fromString('/'),
            method: RequestMethod::GET
        );

        $route = new Route(
            path: '/',
            name: 'route',
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'invalidDispatch'])
        );
        $collection->add($route);

        $router->dispatch(request: $request);
    }

    public static function dispatch(): Response
    {
        return new \Valkyrja\Http\Message\Response\Response(statusCode: StatusCode::I_AM_A_TEAPOT);
    }

    public static function invalidDispatch(): string
    {
        return 'invalid';
    }
}
