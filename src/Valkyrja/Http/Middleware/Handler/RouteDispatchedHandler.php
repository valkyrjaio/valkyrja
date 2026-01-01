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

namespace Valkyrja\Http\Middleware\Handler;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Abstract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract as Contract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * Class DispatchedHandler.
 *
 * @extends Handler<RouteDispatchedMiddlewareContract>
 */
class RouteDispatchedHandler extends Handler implements Contract
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
