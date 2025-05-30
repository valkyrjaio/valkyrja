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
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\Tests\Classes\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestRouteNotMatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class RouteNotMatchedMiddlewareClass implements RouteNotMatchedMiddleware
{
    use MiddlewareCounterTrait;

    public function routeNotMatched(ServerRequest $request, Response $response, RouteNotMatchedHandler $handler): Response
    {
        $this->updateCounter();

        return $handler->routeNotMatched($request, $response);
    }
}
