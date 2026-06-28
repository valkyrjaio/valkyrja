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

namespace Valkyrja\Tests\Classes\Http\Middleware;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Tests\Classes\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestRouteDispatchedMiddleware.
 */
final class RouteDispatchedMiddlewareClass implements RouteDispatchedMiddlewareContract
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
