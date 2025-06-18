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

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Tests\Classes\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestRouteDispatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class RouteDispatchedMiddlewareClass implements RouteDispatchedMiddleware
{
    use MiddlewareCounterTrait;

    public function routeDispatched(ServerRequest $request, Response $response, Route $route, RouteDispatchedHandler $handler): Response
    {
        $this->updateCounter();

        return $handler->routeDispatched($request, $response, $route);
    }
}
