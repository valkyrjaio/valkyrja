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

namespace Valkyrja\Tests\Unit\Http\Routing\Url;

use Override;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Url\Url;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Class UrlTest.
 */
class UrlTest extends TestCase
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
            dispatch: new MethodDispatch(self::class, 'dispatch'),
        );
        $route2     = new Route(
            path: '/{value}',
            name: self::ROUTE2_NAME,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA,
                ),
            ]
        );
        $collection = new Collection();
        $this->url  = new Url(
            collection: $collection,
        );
        $collection->add($route);
        $collection->add($route2);
    }

    public function testGetUrl(): void
    {
        $url = $this->url->getUrl(name: self::ROUTE_NAME);

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
        $this->expectException(InvalidRouteNameException::class);

        $response = $this->url->getUrl('non-existent-route');
    }
}
