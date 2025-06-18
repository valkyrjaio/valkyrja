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

use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Config;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Http\Routing\Router;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Router.
 *
 * @author Melech Mizrachi
 */
class RouterTest extends TestCase
{
    public function testGetMethods(): void
    {
        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);
        $config     = new Config();

        $router = new Router(
            collection: $collection,
            matcher: $matcher,
            config: $config,
            debug: true,
        );

        self::assertSame($collection, $router->getCollection());
        self::assertSame($matcher, $router->getMatcher());
        self::assertSame($config, $router->getConfig());
        self::assertTrue($router->debug());
    }

    public function testRouteMethods(): void
    {
        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);

        $router = new Router(
            collection: $collection,
            matcher: $matcher,
        );

        $routeName = 'test';

        self::assertFalse($router->hasRoute($routeName));
        self::assertEmpty($router->getRoutes());

        $route = new Route(
            path: '/',
            name: $routeName
        );
        $router->addRoute($route);

        self::assertTrue($router->hasRoute($routeName));
        self::assertSame([$routeName => $route], $router->getRoutes());
        self::assertSame($collection->allFlattened(), $router->getRoutes());

        self::assertSame($collection, $router->getCollection());
        self::assertSame($matcher, $router->getMatcher());
    }

    public function testGetRouteException(): void
    {
        $this->expectException(InvalidRouteNameException::class);

        $collection = new Collection();
        $matcher    = new Matcher(collection: $collection);

        $router = new Router(
            collection: $collection,
            matcher: $matcher,
        );

        $routeName = 'test';
        $router->getRoute($routeName);
    }
}
