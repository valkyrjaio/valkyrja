<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Middleware;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Tests\Fixtures\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestRouteDispatchedMiddleware.
 */
final class RouteDispatchedMiddlewareFixture implements RouteDispatchedMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function routeDispatched(
        ServerRequestContract $request,
        ResponseContract $response,
        RouteContract $route,
        RouteDispatchedHandlerContract $handler
    ): ResponseContract {
        $this->updateCounter();

        return $handler->routeDispatched($request, $response, $route);
    }
}
