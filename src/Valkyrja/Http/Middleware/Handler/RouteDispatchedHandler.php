<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Middleware\Handler;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Abstract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * @extends Handler<RouteDispatchedMiddlewareContract>
 */
class RouteDispatchedHandler extends Handler implements RouteDispatchedHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function routeDispatched(ServerRequestContract $request, ResponseContract $response, RouteContract $route): ResponseContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->routeDispatched($request, $response, $route, $this)
            : $response;
    }
}
