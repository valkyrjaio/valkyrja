<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Url;

use Override;
use Valkyrja\Http\Routing\Collection\RouteCollection;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\DynamicRoute;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRouteNameException;
use Valkyrja\Http\Routing\Url\Url;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Class UrlTest.
 */
final class UrlTest extends TestCase
{
    protected const string ROUTE_NAME  = 'route';
    protected const string ROUTE2_NAME = 'route2';

    protected Url $url;

    #[Override]
    protected function setUp(): void
    {
        $route      = new Route(
            path: '/',
            name: self::ROUTE_NAME,
            handler: static fn (): null => null,
        );
        $route2     = new DynamicRoute(
            path: '/{value}',
            name: self::ROUTE2_NAME,
            regex: '/{value}',
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA,
                ),
            ],
            handler: static fn (): null => null,
        );
        $collection = new RouteCollection();
        $this->url  = new Url(
            collection: $collection,
        );
        $collection->add($route);
        $collection->add($route2);
    }

    public function testGetUrl(): void
    {
        $url = $this->url->getUrl(name: self::ROUTE_NAME, data: []);

        self::assertSame('/', $url);
    }

    public function testWithData(): void
    {
        $url = $this->url->getUrl(
            name: self::ROUTE2_NAME,
            data: ['value' => 'test'],
        );

        self::assertSame('/test', $url);
    }

    public function testNonExistentRoute(): void
    {
        $this->expectException(HttpRoutingInvalidRouteNameException::class);

        $response = $this->url->getUrl('non-existent-route', []);
    }
}
