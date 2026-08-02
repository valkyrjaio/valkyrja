<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Middleware\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;

interface RouteNotMatchedMiddlewareContract
{
    /**
     * Middleware handler for after a route has not been matched.
     *
     * @param ServerRequestContract $request  The request
     * @param ResponseContract      $response The response
     */
    public function routeNotMatched(ServerRequestContract $request, ResponseContract $response, RouteNotMatchedHandlerContract $handler): ResponseContract;
}
