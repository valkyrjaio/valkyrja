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
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\RouteCollection;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\DynamicRoute;
use Valkyrja\Http\Routing\Data\HttpRoutingData;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidDynamicRouteNameException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRouteNameException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRoutePathException;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRouteRegexException;
use Valkyrja\Tests\Classes\Http\Routing\Collection\CollectionClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Collection service.
 */
final class RouteCollectionTest extends TestCase
{
    protected const string ROUTE_PATH = '/version';
    protected const string ROUTE_NAME = 'version';

    protected const string DYNAMIC_ROUTE_PATH  = '/{value}';
    protected const string DYNAMIC_ROUTE_REGEX = '/' . Regex::ALPHA . '/';
    protected const string DYNAMIC_ROUTE_NAME  = 'dynamic';

    protected Route $route;
    protected DynamicRoute $dynamicRoute;
    protected RouteCollection $collection;

    #[Override]
    protected function setUp(): void
    {
        $this->route = new Route(
            path: self::ROUTE_PATH,
            name: self::ROUTE_NAME,
            handler: static fn (): null => null,
        );

        $this->dynamicRoute = new DynamicRoute(
            path: self::DYNAMIC_ROUTE_PATH,
            name: self::DYNAMIC_ROUTE_NAME,
            regex: self::DYNAMIC_ROUTE_REGEX,
            parameters: [
                new Parameter(name: 'value', regex: Regex::ALPHA),
            ],
            handler: static fn (): null => null,
        );

        $this->collection = new RouteCollection();
        $this->collection->add($this->route);
        $this->collection->add($this->dynamicRoute);
    }

    public function testData(): void
    {
        $routeName = 'version';

        $route = new Route(
            path: "/$routeName",
            name: $routeName,
            handler: static fn (): null => null,
        );

        $data = new HttpRoutingData(
            routes: [
                $routeName => static fn (): RouteContract => $route,
            ]
        );

        $data2 = new HttpRoutingData(
            routes: [
                $routeName => static fn (): RouteContract => $route,
            ]
        );

        $collection = new RouteCollection();
        $collection->setFromData($data);

        $collection2 = new RouteCollection();
        $collection2->add($route);

        $collection3 = new RouteCollection();
        $collection3->setFromData($data2);

        $dataFromCollection  = $collection->getData();
        $dataFromCollection2 = $collection2->getData();
        $dataFromCollection3 = $collection3->getData();

        self::assertNotSame($data, $dataFromCollection);
        self::assertSame($route, $dataFromCollection->routes[$routeName]());
        self::assertSame($route, $dataFromCollection2->routes[$routeName]());
        self::assertSame($route, $dataFromCollection3->routes[$routeName]());
        self::assertSame($routeName, $collection->getByName($routeName)->getName());
        self::assertSame($routeName, $collection2->getByName($routeName)->getName());
        self::assertSame($routeName, $collection3->getByName($routeName)->getName());
    }

    public function testInvalidSerializedRoute(): void
    {
        $this->expectException(TypeError::class);

        $data = new HttpRoutingData(
            routes: [
                'test' => static fn () => new stdClass(),
            ],
            paths: [
                RequestMethod::GET->value => [
                    'test' => 'test',
                ],
            ],
        );

        $collection = new RouteCollection();
        $collection->setFromData($data);

        $collection->getByPath('test', RequestMethod::GET);
    }

    public function testHasPath(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $collection = $this->collection;

        self::assertTrue($collection->hasPath($routePath, RequestMethod::ANY));
        self::assertTrue($collection->hasPath($dynamicRoutePath, RequestMethod::ANY));
        self::assertTrue($collection->hasPath($routePath, RequestMethod::GET));
        self::assertTrue($collection->hasPath($dynamicRoutePath, RequestMethod::GET));
        self::assertTrue($collection->hasPath($routePath, RequestMethod::HEAD));
        self::assertTrue($collection->hasPath($dynamicRoutePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::POST));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::POST));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::PUT));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::PUT));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::OPTIONS));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testGetByPath(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $route        = $this->route;
        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertSame($route, $collection->getByPath($routePath, RequestMethod::ANY));
        self::assertSame($dynamicRoute, $collection->getByPath($dynamicRoutePath, RequestMethod::ANY));
        self::assertSame($route, $collection->getByPath($routePath, RequestMethod::GET));
        self::assertSame($dynamicRoute, $collection->getByPath($dynamicRoutePath, RequestMethod::GET));
        self::assertSame($route, $collection->getByPath($routePath, RequestMethod::HEAD));
        self::assertSame($dynamicRoute, $collection->getByPath($dynamicRoutePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::POST));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::POST));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::PUT));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::PUT));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::PATCH));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::DELETE));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::CONNECT));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::TRACE));
        self::assertFalse($collection->hasPath($routePath, RequestMethod::OPTIONS));
        self::assertFalse($collection->hasPath($dynamicRoutePath, RequestMethod::OPTIONS));
    }

    public function testGetByPathThrowsForNonExistent(): void
    {
        $path = 'non-existent';

        $this->expectException(HttpRoutingInvalidRoutePathException::class);
        $this->expectExceptionMessage("The path '$path' is not a valid route for the given method 'ANY'");

        $this->collection->getByPath($path, RequestMethod::ANY);
    }

    public function testHasRegex(): void
    {
        $dynamicRouteRegex = self::DYNAMIC_ROUTE_REGEX;
        $routePath         = self::ROUTE_PATH;

        $collection = $this->collection;

        self::assertTrue($collection->hasRegex($dynamicRouteRegex, RequestMethod::ANY));
        self::assertFalse($collection->hasRegex($routePath, RequestMethod::ANY));
        self::assertTrue($collection->hasRegex($dynamicRouteRegex, RequestMethod::GET));
        self::assertFalse($collection->hasRegex($routePath, RequestMethod::GET));
        self::assertTrue($collection->hasRegex($dynamicRouteRegex, RequestMethod::HEAD));
        self::assertFalse($collection->hasRegex($routePath, RequestMethod::HEAD));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::POST));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::PUT));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::PATCH));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::DELETE));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::CONNECT));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::TRACE));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::OPTIONS));
    }

    public function testGetByRegex(): void
    {
        $dynamicRouteRegex = self::DYNAMIC_ROUTE_REGEX;
        $routePath         = self::ROUTE_PATH;

        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertFalse($collection->hasRegex($routePath, RequestMethod::ANY));
        self::assertSame($dynamicRoute, $collection->getByRegex($dynamicRouteRegex, RequestMethod::ANY));
        self::assertFalse($collection->hasRegex($routePath, RequestMethod::GET));
        self::assertSame($dynamicRoute, $collection->getByRegex($dynamicRouteRegex, RequestMethod::GET));
        self::assertFalse($collection->hasRegex($routePath, RequestMethod::HEAD));
        self::assertSame($dynamicRoute, $collection->getByRegex($dynamicRouteRegex, RequestMethod::HEAD));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::POST));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::PUT));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::PATCH));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::DELETE));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::CONNECT));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::TRACE));
        self::assertFalse($collection->hasRegex($dynamicRouteRegex, RequestMethod::OPTIONS));
    }

    public function testGetByRegexThrowsForNonExistent(): void
    {
        $regex = 'non-existent';

        $this->expectException(HttpRoutingInvalidRouteRegexException::class);
        $this->expectExceptionMessage("The regex '$regex' is not a valid route for the given method 'ANY'");

        $this->collection->getByRegex($regex, RequestMethod::ANY);
    }

    public function testGetPaths(): void
    {
        $routePath        = self::ROUTE_PATH;
        $routeName        = self::ROUTE_NAME;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;
        $dynamicRouteName = self::DYNAMIC_ROUTE_NAME;

        $collection = $this->collection;

        self::assertSame(
            [
                $routePath        => $routeName,
                $dynamicRoutePath => $dynamicRouteName,
            ],
            $collection->getPaths(RequestMethod::ANY)
        );

        self::assertSame(
            [
                $routePath => $routeName,
            ],
            $collection->getPaths(RequestMethod::GET)
        );

        self::assertSame(
            [
                $routePath => $routeName,
            ],
            $collection->getPaths(RequestMethod::HEAD)
        );

        self::assertEmpty($collection->getPaths(RequestMethod::POST));
        self::assertEmpty($collection->getPaths(RequestMethod::PUT));
        self::assertEmpty($collection->getPaths(RequestMethod::DELETE));
        self::assertEmpty($collection->getPaths(RequestMethod::CONNECT));
        self::assertEmpty($collection->getPaths(RequestMethod::PATCH));
        self::assertEmpty($collection->getPaths(RequestMethod::TRACE));
        self::assertEmpty($collection->getPaths(RequestMethod::OPTIONS));
    }

    public function testGetRegexes(): void
    {
        $dynamicRouteRegex = self::DYNAMIC_ROUTE_REGEX;
        $dynamicRouteName  = self::DYNAMIC_ROUTE_NAME;

        $collection = $this->collection;

        self::assertSame(
            [
                $dynamicRouteRegex => $dynamicRouteName,
            ],
            $collection->getRegexes(RequestMethod::ANY)
        );

        self::assertSame(
            [
                $dynamicRouteRegex => $dynamicRouteName,
            ],
            $collection->getRegexes(RequestMethod::GET)
        );

        self::assertSame(
            [
                $dynamicRouteRegex => $dynamicRouteName,
            ],
            $collection->getRegexes(RequestMethod::HEAD)
        );

        self::assertEmpty($collection->getRegexes(RequestMethod::POST));
        self::assertEmpty($collection->getRegexes(RequestMethod::PUT));
        self::assertEmpty($collection->getRegexes(RequestMethod::DELETE));
        self::assertEmpty($collection->getRegexes(RequestMethod::CONNECT));
        self::assertEmpty($collection->getRegexes(RequestMethod::PATCH));
        self::assertEmpty($collection->getRegexes(RequestMethod::TRACE));
        self::assertEmpty($collection->getRegexes(RequestMethod::OPTIONS));
    }

    public function testHasName(): void
    {
        $routeName        = self::ROUTE_NAME;
        $dynamicRouteName = self::DYNAMIC_ROUTE_NAME;

        $collection = $this->collection;

        self::assertTrue($collection->hasName($routeName));
        self::assertTrue($collection->hasName($dynamicRouteName));
        self::assertFalse($collection->hasName('non-existent'));
    }

    public function testGetByName(): void
    {
        $routeName        = self::ROUTE_NAME;
        $dynamicRouteName = self::DYNAMIC_ROUTE_NAME;

        $route        = $this->route;
        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertSame($route, $collection->getByName($routeName));
        self::assertSame($dynamicRoute, $collection->getByName($dynamicRouteName));
        self::assertFalse($collection->hasName('non-existent'));
    }

    public function testGetByNameThrowsForNonExistent(): void
    {
        $name = 'non-existent';

        $this->expectException(HttpRoutingInvalidRouteNameException::class);
        $this->expectExceptionMessage("A route with the name '$name' does not exist");

        $this->collection->getByName($name);
    }

    public function testGetAll(): void
    {
        $routePath        = self::ROUTE_PATH;
        $dynamicRoutePath = self::DYNAMIC_ROUTE_PATH;

        $route        = $this->route;
        $dynamicRoute = $this->dynamicRoute;
        $collection   = $this->collection;

        self::assertSame(
            [
                $routePath        => $route,
                $dynamicRoutePath => $dynamicRoute,
            ],
            $collection->getAll(RequestMethod::ANY)
        );

        self::assertSame(
            [
                $routePath => $route,
            ],
            $collection->getAll(RequestMethod::GET)
        );

        self::assertSame(
            [
                $routePath => $route,
            ],
            $collection->getAll(RequestMethod::HEAD)
        );

        self::assertEmpty($collection->getAll(RequestMethod::POST));
        self::assertEmpty($collection->getAll(RequestMethod::PUT));
        self::assertEmpty($collection->getAll(RequestMethod::DELETE));
        self::assertEmpty($collection->getAll(RequestMethod::CONNECT));
        self::assertEmpty($collection->getAll(RequestMethod::PATCH));
        self::assertEmpty($collection->getAll(RequestMethod::TRACE));
        self::assertEmpty($collection->getAll(RequestMethod::OPTIONS));
    }

    public function testAddRouteWithAnyRequestMethod(): void
    {
        $route = new Route(
            path: self::ROUTE_PATH,
            name: self::ROUTE_NAME,
            handler: static fn (): null => null,
            requestMethods: [RequestMethod::ANY],
        );

        $dynamicRoute = new DynamicRoute(
            path: self::DYNAMIC_ROUTE_PATH,
            name: self::DYNAMIC_ROUTE_NAME,
            regex: self::DYNAMIC_ROUTE_REGEX,
            parameters: [
                new Parameter(name: 'value', regex: Regex::ALPHA),
            ],
            handler: static fn (): null => null,
            requestMethods: [RequestMethod::ANY]
        );

        $collection = new RouteCollection();
        $collection->add($route);
        $collection->add($dynamicRoute);

        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::ANY));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::HEAD));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::GET));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::POST));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::PUT));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::DELETE));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::CONNECT));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::PATCH));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::TRACE));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::OPTIONS));

        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::ANY));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::HEAD));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::GET));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::POST));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::PUT));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::DELETE));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::CONNECT));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::PATCH));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::TRACE));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::OPTIONS));

        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::ANY));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::HEAD));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::GET));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::POST));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::PUT));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::DELETE));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::CONNECT));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::PATCH));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::TRACE));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::OPTIONS));
    }

    public function testAddRouteWithAnyRequestMethodDirectlyViaHelperMethod(): void
    {
        $route = new Route(
            path: self::ROUTE_PATH,
            name: self::ROUTE_NAME,
            handler: static fn (): null => null,
            requestMethods: [RequestMethod::ANY],
        );

        $dynamicRoute = new DynamicRoute(
            path: self::DYNAMIC_ROUTE_PATH,
            name: self::DYNAMIC_ROUTE_NAME,
            regex: self::DYNAMIC_ROUTE_REGEX,
            parameters: [
                new Parameter(name: 'value', regex: Regex::ALPHA),
            ],
            handler: static fn (): null => null,
            requestMethods: [RequestMethod::ANY]
        );

        // Should not be added to the collection when called with ANY
        $collection = new CollectionClass();
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::ANY);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::ANY);

        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::ANY));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::HEAD));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::GET));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::POST));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::PUT));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::DELETE));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::CONNECT));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::PATCH));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::TRACE));
        self::assertFalse($collection->hasPath(self::ROUTE_PATH, RequestMethod::OPTIONS));

        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::ANY));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::HEAD));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::GET));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::POST));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::PUT));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::DELETE));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::CONNECT));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::PATCH));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::TRACE));
        self::assertFalse($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::OPTIONS));

        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::HEAD);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::GET);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::POST);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::PUT);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::DELETE);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::CONNECT);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::PATCH);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::TRACE);
        $collection->setRouteToRequestMethodWrapper($route, RequestMethod::OPTIONS);

        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::ANY));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::HEAD));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::GET));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::POST));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::PUT));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::DELETE));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::CONNECT));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::PATCH));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::TRACE));
        self::assertTrue($collection->hasPath(self::ROUTE_PATH, RequestMethod::OPTIONS));

        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::HEAD);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::GET);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::POST);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::PUT);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::DELETE);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::CONNECT);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::PATCH);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::TRACE);
        $collection->setRouteToRequestMethodWrapper($dynamicRoute, RequestMethod::OPTIONS);

        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::ANY));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::HEAD));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::GET));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::POST));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::PUT));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::DELETE));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::CONNECT));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::PATCH));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::TRACE));
        self::assertTrue($collection->hasPath(self::DYNAMIC_ROUTE_PATH, RequestMethod::OPTIONS));

        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::ANY));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::HEAD));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::GET));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::POST));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::PUT));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::DELETE));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::CONNECT));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::PATCH));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::TRACE));
        self::assertTrue($collection->hasRegex(self::DYNAMIC_ROUTE_REGEX, RequestMethod::OPTIONS));
    }

    public function testGetRouteFromName(): void
    {
        $name = 'non-existent';

        $this->expectException(HttpRoutingInvalidRouteNameException::class);
        $this->expectExceptionMessage("Invalid name `$name` provided");

        $collection = new CollectionClass();
        $collection->getRouteFromNameWrapper($name);
    }

    public function testGetDynamicRouteFromName(): void
    {
        $this->expectException(HttpRoutingInvalidDynamicRouteNameException::class);
        $this->expectExceptionMessage('Invalid dynamic route ' . self::ROUTE_NAME);

        $collection = new CollectionClass();
        $collection->add($this->route);

        $collection->getDynamicRouteFromNameWrapper(self::ROUTE_NAME);
    }
}
