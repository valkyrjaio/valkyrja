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

namespace Valkyrja\Tests\Unit\Http\Routing\Collection;

use Override;
use stdClass;
use TypeError;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Data;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRoutePathException;
use Valkyrja\Tests\Classes\Http\Routing\Collection\CollectionClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Collection service.
 */
final class CollectionTest extends TestCase
{
    protected const string ROUTE_PATH = '/version';
    protected const string ROUTE_NAME = 'version';

    protected const string DYNAMIC_ROUTE_PATH  = '/{value}';
    protected const string DYNAMIC_ROUTE_REGEX = '/' . Regex::ALPHA . '/';
    protected const string DYNAMIC_ROUTE_NAME  = 'dynamic';

    protected Route $route;
    protected Route $dynamicRoute;
    protected Collection $collection;

    #[Override]
    protected function setUp(): void
    {
        $this->route = new Route(
            path: self::ROUTE_PATH,
            name: self::ROUTE_NAME,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'httpCallback'])
        );

        $this->dynamicRoute = new Route(
            path: self::DYNAMIC_ROUTE_PATH,
            name: self::DYNAMIC_ROUTE_NAME,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'httpCallback']),
            regex: self::DYNAMIC_ROUTE_REGEX,
            parameters: [
                new Parameter(name: 'value', regex: Regex::ALPHA),
            ]
        );

        $this->collection = new Collection();
        $this->collection->add($this->route);
        $this->collection->add($this->dynamicRoute);
    }

    public function testData(): void
    {
        $routeName = 'version';

        $route = new Route(
            path: "/$routeName",
            name: $routeName,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'httpCallback']),
            parameters: [new Parameter(name: 'value', regex: Regex::ALPHA)],
        );

        $data = new Data(
            routes: [
                $routeName => $route,
            ]
        );

        $data2 = new Data(
            routes: [
                $routeName => static fn (): RouteContract => $route,
            ]
        );

        $collection = new Collection();
        $collection->setFromData($data);

        $collection2 = new Collection();
        $collection2->add($route);

        $collection3 = new Collection();
        $collection3->setFromData($data2);

        $dataFromCollection  = $collection->getData();
        $dataFromCollection2 = $collection2->getData();
        $dataFromCollection3 = $collection3->getData();

        self::assertNotSame($data, $dataFromCollection);
        self::assertSame($route, $dataFromCollection->routes[$routeName]);
        self::assertSame($route, $dataFromCollection2->routes[$routeName]);
        self::assertSame($route, $dataFromCollection3->routes[$routeName]);
        self::assertSame($routeName, $collection->getByName($routeName)->getName());
        self::assertSame($routeName, $collection2->getByName($routeName)->getName());
        self::assertSame($routeName, $collection3->getByName($routeName)->getName());
    }

    public function testInvalidSerializedRoute(): void
    {
        $this->expectException(TypeError::class);

        $data = new Data(
            routes: [
                'test' => static fn () => new stdClass(),
            ],
            static: [
                RequestMethod::GET->value => [
                    'test' => 'test',
                ],
            ],
        );

        $collection = new Collection();
        $collection->setFromData($data);

        $collection->get('test', RequestMethod::ANY);
    }

    public function testHas(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $collection = $this->collection;

        self::assertTrue($collection->has($routePath, RequestMethod::ANY));
        self::assertTrue($collection->has($dynamicRoutePath, RequestMethod::ANY));
        self::assertTrue($collection->has($routePath, RequestMethod::GET));
        self::assertTrue($collection->has($dynamicRoutePath, RequestMethod::GET));
        self::assertTrue($collection->has($routePath, RequestMethod::HEAD));
        self::assertTrue($collection->has($dynamicRoutePath, RequestMethod::HEAD));
        self::assertFalse($collection->has($routePath, RequestMethod::POST));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::POST));
        self::assertFalse($collection->has($routePath, RequestMethod::PUT));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::PUT));
        self::assertFalse($collection->has($routePath, RequestMethod::PATCH));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::PATCH));
        self::assertFalse($collection->has($routePath, RequestMethod::DELETE));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::DELETE));
        self::assertFalse($collection->has($routePath, RequestMethod::CONNECT));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertFalse($collection->has($routePath, RequestMethod::TRACE));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::TRACE));
        self::assertFalse($collection->has($routePath, RequestMethod::OPTIONS));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testGet(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $route        = $this->route;
        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertSame($route, $collection->get($routePath, RequestMethod::ANY));
        self::assertSame($dynamicRoute, $collection->get($dynamicRoutePath, RequestMethod::ANY));
        self::assertSame($route, $collection->get($routePath, RequestMethod::GET));
        self::assertSame($dynamicRoute, $collection->get($dynamicRoutePath, RequestMethod::GET));
        self::assertSame($route, $collection->get($routePath, RequestMethod::HEAD));
        self::assertSame($dynamicRoute, $collection->get($dynamicRoutePath, RequestMethod::HEAD));
        self::assertFalse($collection->has($routePath, RequestMethod::POST));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::POST));
        self::assertFalse($collection->has($routePath, RequestMethod::PUT));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::PUT));
        self::assertFalse($collection->has($routePath, RequestMethod::PATCH));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::PATCH));
        self::assertFalse($collection->has($routePath, RequestMethod::DELETE));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::DELETE));
        self::assertFalse($collection->has($routePath, RequestMethod::CONNECT));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertFalse($collection->has($routePath, RequestMethod::TRACE));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::TRACE));
        self::assertFalse($collection->has($routePath, RequestMethod::OPTIONS));
        self::assertFalse($collection->has($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testGetThrowsForNonExistent(): void
    {
        $path = 'non-existent';

        $this->expectException(InvalidRoutePathException::class);
        $this->expectExceptionMessage("The path '$path' is not a valid route for the given method 'ANY'");

        $this->collection->get($path, RequestMethod::ANY);
    }

    public function testHasStatic(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $collection = $this->collection;

        self::assertTrue($collection->hasStatic($routePath, RequestMethod::ANY));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::ANY));
        self::assertTrue($collection->hasStatic($routePath, RequestMethod::GET));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::GET));
        self::assertTrue($collection->hasStatic($routePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::POST));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::POST));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::PUT));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::PUT));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::OPTIONS));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testGetStatic(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $route      = $this->route;
        $collection = $this->collection;

        self::assertSame($route, $collection->getStatic($routePath, RequestMethod::ANY));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::ANY));
        self::assertSame($route, $collection->getStatic($routePath, RequestMethod::GET));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::GET));
        self::assertSame($route, $collection->getStatic($routePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::POST));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::POST));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::PUT));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::PUT));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasStatic($routePath, RequestMethod::OPTIONS));
        self::assertFalse($collection->hasStatic($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testGetStaticThrowsForNonExistent(): void
    {
        $path = 'non-existent';

        $this->expectException(InvalidRoutePathException::class);
        $this->expectExceptionMessage("The static path '$path' is not a valid route for the given method 'ANY'");

        $this->collection->getStatic($path, RequestMethod::ANY);
    }

    public function testHasDynamic(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $collection = $this->collection;

        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::ANY));
        self::assertTrue($collection->hasDynamic($dynamicRoutePath, RequestMethod::ANY));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::GET));
        self::assertTrue($collection->hasDynamic($dynamicRoutePath, RequestMethod::GET));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::HEAD));
        self::assertTrue($collection->hasDynamic($dynamicRoutePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::POST));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::POST));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::PUT));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::PUT));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::OPTIONS));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testGetDynamic(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::ANY));
        self::assertSame($dynamicRoute, $collection->getDynamic($dynamicRoutePath, RequestMethod::ANY));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::GET));
        self::assertSame($dynamicRoute, $collection->getDynamic($dynamicRoutePath, RequestMethod::GET));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::HEAD));
        self::assertSame($dynamicRoute, $collection->getDynamic($dynamicRoutePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::POST));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::POST));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::PUT));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::PUT));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasDynamic($routePath, RequestMethod::OPTIONS));
        self::assertFalse($collection->hasDynamic($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testGetDynamicThrowsForNonExistent(): void
    {
        $path = 'non-existent';

        $this->expectException(InvalidRoutePathException::class);
        $this->expectExceptionMessage("The dynamic path '$path' is not a valid route for the given method 'ANY'");

        $this->collection->getDynamic($path, RequestMethod::ANY);
    }

    public function testHasNamed(): void
    {
        $routeName        = self::ROUTE_NAME;
        $dynamicRouteName = self::DYNAMIC_ROUTE_NAME;

        $collection = $this->collection;

        self::assertTrue($collection->hasNamed($routeName));
        self::assertTrue($collection->hasNamed($dynamicRouteName));
    }

    public function testGetRouteByName(): void
    {
        $routeName        = self::ROUTE_NAME;
        $dynamicRouteName = self::DYNAMIC_ROUTE_NAME;

        $route        = $this->route;
        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertSame($route, $collection->getByName($routeName));
        self::assertSame($dynamicRoute, $collection->getByName($dynamicRouteName));
        self::assertFalse($collection->hasNamed('non-existent'));
    }

    public function testGetNamedThrowsForNonExistent(): void
    {
        $name = 'non-existent';

        $this->expectException(InvalidRouteNameException::class);
        $this->expectExceptionMessage("A route with the name '$name' does not exist");

        $this->collection->getByName($name);
    }

    public function testAll(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $route        = $this->route;
        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertSame(
            [
                RequestMethod::HEAD->value => [
                    $routePath        => $route,
                    $dynamicRoutePath => $dynamicRoute,
                ],
                RequestMethod::GET->value  => [
                    $routePath        => $route,
                    $dynamicRoutePath => $dynamicRoute,
                ],
            ],
            $collection->all()
        );
    }

    public function testAllStatic(): void
    {
        $routePath = self::ROUTE_PATH;

        $route      = $this->route;
        $collection = $this->collection;

        self::assertSame(
            [
                RequestMethod::HEAD->value => [
                    $routePath => $route,
                ],
                RequestMethod::GET->value  => [
                    $routePath => $route,
                ],
            ],
            $collection->allStatic(RequestMethod::ANY)
        );

        self::assertSame(
            [
                $routePath => $route,
            ],
            $collection->allStatic(RequestMethod::GET)
        );

        self::assertSame(
            [
                $routePath => $route,
            ],
            $collection->allStatic(RequestMethod::HEAD)
        );

        self::assertEmpty($collection->allStatic(RequestMethod::POST));
        self::assertEmpty($collection->allStatic(RequestMethod::PUT));
        self::assertEmpty($collection->allStatic(RequestMethod::DELETE));
        self::assertEmpty($collection->allStatic(RequestMethod::CONNECT));
        self::assertEmpty($collection->allStatic(RequestMethod::PATCH));
        self::assertEmpty($collection->allStatic(RequestMethod::TRACE));
        self::assertEmpty($collection->allStatic(RequestMethod::OPTIONS));
    }

    public function testAllDynamic(): void
    {
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertSame(
            [
                RequestMethod::HEAD->value => [
                    $dynamicRoutePath => $dynamicRoute,
                ],
                RequestMethod::GET->value  => [
                    $dynamicRoutePath => $dynamicRoute,
                ],
            ],
            $collection->allDynamic(RequestMethod::ANY)
        );

        self::assertSame(
            [
                $dynamicRoutePath => $dynamicRoute,
            ],
            $collection->allDynamic(RequestMethod::GET)
        );

        self::assertSame(
            [
                $dynamicRoutePath => $dynamicRoute,
            ],
            $collection->allDynamic(RequestMethod::HEAD)
        );

        self::assertEmpty($collection->allDynamic(RequestMethod::POST));
        self::assertEmpty($collection->allDynamic(RequestMethod::PUT));
        self::assertEmpty($collection->allDynamic(RequestMethod::DELETE));
        self::assertEmpty($collection->allDynamic(RequestMethod::CONNECT));
        self::assertEmpty($collection->allDynamic(RequestMethod::PATCH));
        self::assertEmpty($collection->allDynamic(RequestMethod::TRACE));
        self::assertEmpty($collection->allDynamic(RequestMethod::OPTIONS));
    }

    public function testAllFlattened(): void
    {
        self::assertSame(
            [
                self::ROUTE_NAME         => $this->route,
                self::DYNAMIC_ROUTE_NAME => $this->dynamicRoute,
            ],
            $this->collection->allFlattened()
        );
    }

    public function testAddRouteWithAnyRequestMethod(): void
    {
        $route = new Route(
            path: self::ROUTE_PATH,
            name: self::ROUTE_NAME,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'httpCallback']),
            requestMethods: [RequestMethod::ANY],
        );

        $dynamicRoute = new Route(
            path: self::DYNAMIC_ROUTE_PATH,
            name: self::DYNAMIC_ROUTE_NAME,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'httpCallback']),
            requestMethods: [RequestMethod::ANY],
            regex: self::DYNAMIC_ROUTE_REGEX,
            parameters: [
                new Parameter(name: 'value', regex: Regex::ALPHA),
            ]
        );

        $collection = new Collection();
        $collection->add($route);
        $collection->add($dynamicRoute);

        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::ANY));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::HEAD));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::GET));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::POST));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::PUT));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::DELETE));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::CONNECT));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::PATCH));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::TRACE));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::OPTIONS));

        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::ANY));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::HEAD));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::GET));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::POST));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::PUT));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::DELETE));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::CONNECT));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::PATCH));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::TRACE));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::OPTIONS));
    }

    public function testAddRouteWithAnyRequestMethodDirectlyViaHelperMethod(): void
    {
        $route = new Route(
            path: self::ROUTE_PATH,
            name: self::ROUTE_NAME,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'httpCallback']),
            requestMethods: [RequestMethod::ANY],
        );

        $dynamicRoute = new Route(
            path: self::DYNAMIC_ROUTE_PATH,
            name: self::DYNAMIC_ROUTE_NAME,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'httpCallback']),
            requestMethods: [RequestMethod::ANY],
            regex: self::DYNAMIC_ROUTE_REGEX,
            parameters: [
                new Parameter(name: 'value', regex: Regex::ALPHA),
            ]
        );

        // Should not be added to the collection
        $collection = new CollectionClass();
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::ANY);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::ANY);

        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::ANY));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::HEAD));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::GET));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::POST));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::PUT));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::DELETE));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::CONNECT));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::PATCH));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::TRACE));
        self::assertFalse($collection->has(self::ROUTE_PATH, RequestMethod::OPTIONS));

        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::ANY));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::HEAD));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::GET));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::POST));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::PUT));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::DELETE));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::CONNECT));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::PATCH));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::TRACE));
        self::assertFalse($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::OPTIONS));

        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::HEAD);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::GET);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::POST);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::PUT);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::DELETE);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::CONNECT);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::PATCH);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::TRACE);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::OPTIONS);

        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::ANY));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::HEAD));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::GET));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::POST));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::PUT));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::DELETE));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::CONNECT));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::PATCH));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::TRACE));
        self::assertTrue($collection->has(self::ROUTE_PATH, RequestMethod::OPTIONS));

        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::HEAD);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::GET);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::POST);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::PUT);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::DELETE);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::CONNECT);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::PATCH);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::TRACE);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::OPTIONS);

        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::ANY));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::HEAD));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::GET));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::POST));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::PUT));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::DELETE));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::CONNECT));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::PATCH));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::TRACE));
        self::assertTrue($collection->has(self::DYNAMIC_ROUTE_PATH, RequestMethod::OPTIONS));
    }
}
