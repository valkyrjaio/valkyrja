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

namespace Valkyrja\Tests\Unit\Http\Routing\Factory;

use Override;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\ResponseFactory as MessageResponseFactory;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Factory\ResponseFactory;
use Valkyrja\Http\Routing\Url\Url;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ResponseFactory service.
 */
class ResponseFactoryTest extends TestCase
{
    protected const string ROUTE_NAME = 'route';

    protected ResponseFactory $responseFactory;

    #[Override]
    protected function setUp(): void
    {
        $route           = new Route(
            path: '/',
            name: self::ROUTE_NAME,
            dispatch: new MethodDispatch(self::class, 'dispatch'),
        );
        $collection      = new Collection();
        $responseFactory = new MessageResponseFactory();
        $url             = new Url(
            collection: $collection,
        );
        $collection->add($route);

        $this->responseFactory = new ResponseFactory(
            responseFactory: $responseFactory,
            url: $url
        );
    }

    public function testDefaults(): void
    {
        $response = $this->responseFactory->createRouteRedirectResponse(
            name: self::ROUTE_NAME
        );

        self::assertSame('/', $response->getUri()->__toString());
        self::assertSame(StatusCode::FOUND, $response->getStatusCode());
        self::assertSame('/', $response->getHeaderLine(HeaderName::LOCATION));
    }

    public function testWithArguments(): void
    {
        $response = $this->responseFactory->createRouteRedirectResponse(
            name: self::ROUTE_NAME,
            statusCode: StatusCode::MOVED_PERMANENTLY,
            headers: [new Header('Test', 'fire')]
        );

        self::assertSame('/', $response->getUri()->__toString());
        self::assertSame(StatusCode::MOVED_PERMANENTLY, $response->getStatusCode());
        self::assertSame('fire', $response->getHeaderLine('Test'));
        self::assertSame('/', $response->getHeaderLine(HeaderName::LOCATION));
    }
}
