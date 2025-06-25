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

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Interface RouteDispatchedHandler.
 *
 * @author Melech Mizrachi
 */
interface RouteDispatchedHandler
{
    /**
     * Middleware handler for after a route is dispatched.
     */
    public function routeDispatched(ServerRequest $request, Response $response, Route $route): Response;
}
