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
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Class RouteMatchedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<RouteMatchedMiddleware>
 */
class RouteMatchedHandler extends Handler implements Contract\RouteMatchedHandler
{
    /**
     * @inheritDoc
     */
    public function routeMatched(ServerRequest $request, Route $route): Route|Response
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->routeMatched($request, $route, $this)
            : $route;
    }
}
