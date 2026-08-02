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
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Tests\Fixtures\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestRouteNotMatchedMiddleware.
 */
final class RouteNotMatchedMiddlewareFixture implements RouteNotMatchedMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function routeNotMatched(ServerRequestContract $request, ResponseContract $response, RouteNotMatchedHandlerContract $handler): ResponseContract
    {
        $this->updateCounter();

        return $handler->routeNotMatched($request, $response);
    }
}
