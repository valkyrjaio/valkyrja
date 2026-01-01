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

namespace Valkyrja\Http\Middleware\Handler\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * Interface RouteDispatchedHandlerContract.
 *
 * @author Melech Mizrachi
 *
 * @extends HandlerContract<RouteDispatchedMiddlewareContract>
 */
interface RouteDispatchedHandlerContract extends HandlerContract
{
    /**
     * Middleware handler for after a route is dispatched.
     */
    public function routeDispatched(ServerRequestContract $request, ResponseContract $response, RouteContract $route): ResponseContract;
}
