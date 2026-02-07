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

        $collection->get('test');
    }

    public function testHas(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_REGEX;

        $collection = $this->collection;

        self::assertTrue($collection->has($routePath));
        self::assertTrue($collection->has($dynamicRoutePath));
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
        $dynamicRoutePath = self::DYNAMIC_ROUTE_REGEX;

        $route        = $this->route;
        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertSame($route, $collection->get($routePath));
        self::assertSame($dynamicRoute, $collection->get($dynamicRoutePath));
        self::assertSame($route, $collection->get($routePath, RequestMethod::GET));
        self::assertSame($dynamicRoute, $collection->get($dynamicRoutePath, RequestMethod::GET));
        self::assertSame($route, $collection->get($routePath, RequestMethod::HEAD));
        self::assertSame($dynamicRoute, $collection->get($dynamicRoutePath, RequestMethod::HEAD));
        self::assertNull($collection->get($routePath, RequestMethod::POST));
        self::assertNull($collection->get($dynamicRoutePath, RequestMethod::POST));
        self::assertNull($collection->get($routePath, RequestMethod::PUT));
        self::assertNull($collection->get($dynamicRoutePath, RequestMethod::PUT));
        self::assertNull($collection->get($routePath, RequestMethod::PATCH));
        self::assertNull($collection->get($dynamicRoutePath, RequestMethod::PATCH));
        self::assertNull($collection->get($routePath, RequestMethod::DELETE));
        self::assertNull($collection->get($dynamicRoutePath, RequestMethod::DELETE));
        self::assertNull($collection->get($routePath, RequestMethod::CONNECT));
        self::assertNull($collection->get($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertNull($collection->get($routePath, RequestMethod::TRACE));
        self::assertNull($collection->get($dynamicRoutePath, RequestMethod::TRACE));
        self::assertNull($collection->get($routePath, RequestMethod::OPTIONS));
        self::assertNull($collection->get($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testHasStatic(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_REGEX;

        $collection = $this->collection;

        self::assertTrue($collection->hasStatic($routePath));
        self::assertFalse($collection->hasStatic($dynamicRoutePath));
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
        $dynamicRoutePath = self::DYNAMIC_ROUTE_REGEX;

        $route      = $this->route;
        $collection = $this->collection;

        self::assertSame($route, $collection->getStatic($routePath));
        self::assertNull($collection->getStatic($dynamicRoutePath));
        self::assertSame($route, $collection->getStatic($routePath, RequestMethod::GET));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::GET));
        self::assertSame($route, $collection->getStatic($routePath, RequestMethod::HEAD));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::HEAD));
        self::assertNull($collection->getStatic($routePath, RequestMethod::POST));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::POST));
        self::assertNull($collection->getStatic($routePath, RequestMethod::PUT));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::PUT));
        self::assertNull($collection->getStatic($routePath, RequestMethod::PATCH));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::PATCH));
        self::assertNull($collection->getStatic($routePath, RequestMethod::DELETE));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::DELETE));
        self::assertNull($collection->getStatic($routePath, RequestMethod::CONNECT));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertNull($collection->getStatic($routePath, RequestMethod::TRACE));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::TRACE));
        self::assertNull($collection->getStatic($routePath, RequestMethod::OPTIONS));
        self::assertNull($collection->getStatic($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testHasDynamic(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_REGEX;

        $collection = $this->collection;

        self::assertFalse($collection->hasDynamic($routePath));
        self::assertTrue($collection->hasDynamic($dynamicRoutePath));
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
        $dynamicRoutePath = self::DYNAMIC_ROUTE_REGEX;

        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertNull($collection->getDynamic($routePath));
        self::assertSame($dynamicRoute, $collection->getDynamic($dynamicRoutePath));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::GET));
        self::assertSame($dynamicRoute, $collection->getDynamic($dynamicRoutePath, RequestMethod::GET));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::HEAD));
        self::assertSame($dynamicRoute, $collection->getDynamic($dynamicRoutePath, RequestMethod::HEAD));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::POST));
        self::assertNull($collection->getDynamic($dynamicRoutePath, RequestMethod::POST));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::PUT));
        self::assertNull($collection->getDynamic($dynamicRoutePath, RequestMethod::PUT));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::PATCH));
        self::assertNull($collection->getDynamic($dynamicRoutePath, RequestMethod::PATCH));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::DELETE));
        self::assertNull($collection->getDynamic($dynamicRoutePath, RequestMethod::DELETE));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::CONNECT));
        self::assertNull($collection->getDynamic($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::TRACE));
        self::assertNull($collection->getDynamic($dynamicRoutePath, RequestMethod::TRACE));
        self::assertNull($collection->getDynamic($routePath, RequestMethod::OPTIONS));
        self::assertNull($collection->getDynamic($dynamicRoutePath, RequestMethod::OPTIONS));
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
        self::assertNull($collection->getByName('non-existent'));
    }

    public function testAll(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_REGEX;

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
            $collection->allStatic()
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
        $dynamicRoutePath = self::DYNAMIC_ROUTE_REGEX;

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
            $collection->allDynamic()
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
}
