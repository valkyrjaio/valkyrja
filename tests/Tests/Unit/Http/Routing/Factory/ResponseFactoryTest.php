<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Factory;

use Override;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\Factory\ResponseFactory;
use Valkyrja\Http\Routing\Collection\RouteCollection;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Factory\RoutingResponseFactory;
use Valkyrja\Http\Routing\Url\Url;
use Valkyrja\Tests\Fixtures\Http\Routing\Handler\RouteHandlerFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ResponseFactory service.
 */
final class ResponseFactoryTest extends TestCase
{
    protected const string ROUTE_NAME = 'route';

    protected RoutingResponseFactory $responseFactory;

    #[Override]
    protected function setUp(): void
    {
        $route           = new Route(
            path: '/',
            name: self::ROUTE_NAME,
            handler: RouteHandlerFixture::handle(...),
        );
        $collection      = new RouteCollection();
        $responseFactory = new ResponseFactory();
        $url             = new Url(
            collection: $collection,
        );
        $collection->add($route);

        $this->responseFactory = new RoutingResponseFactory(
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
        self::assertSame('/', $response->getHeaders()->getHeaderLine(HeaderName::LOCATION));
    }

    public function testWithArguments(): void
    {
        $response = $this->responseFactory->createRouteRedirectResponse(
            name: self::ROUTE_NAME,
            statusCode: StatusCode::MOVED_PERMANENTLY,
            headers: HeaderCollection::fromArray([new Header('Test', 'fire')])
        );

        self::assertSame('/', $response->getUri()->__toString());
        self::assertSame(StatusCode::MOVED_PERMANENTLY, $response->getStatusCode());
        self::assertSame('fire', $response->getHeaders()->getHeaderLine('Test'));
        self::assertSame('/', $response->getHeaders()->getHeaderLine(HeaderName::LOCATION));
    }
}
