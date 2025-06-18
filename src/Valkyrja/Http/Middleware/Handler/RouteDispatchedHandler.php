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

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Class DispatchedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<RouteDispatchedMiddleware>
 */
class RouteDispatchedHandler extends Handler implements Contract\RouteDispatchedHandler
{
    /**
     * @inheritDoc
     */
    public function routeDispatched(ServerRequest $request, Response $response, Route $route): Response
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->routeDispatched($request, $response, $route, $this)
            : $response;
    }
}
