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

namespace Valkyrja\Http\Middleware\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Interface RouteDispatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
interface RouteDispatchedMiddleware
{
    /**
     * Middleware handler for after a route and request is dispatched.
     */
    public function routeDispatched(ServerRequest $request, Response $response, Route $route, RouteDispatchedHandler $handler): Response;
}
