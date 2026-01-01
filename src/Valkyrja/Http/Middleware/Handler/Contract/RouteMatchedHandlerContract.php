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
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * Interface RouteMatchedHandlerContract.
 *
 * @extends HandlerContract<RouteMatchedMiddlewareContract>
 */
interface RouteMatchedHandlerContract extends HandlerContract
{
    /**
     * Middleware handler for after a route has been matched but before it has been dispatched.
     */
    public function routeMatched(ServerRequestContract $request, RouteContract $route): RouteContract|ResponseContract;
}
