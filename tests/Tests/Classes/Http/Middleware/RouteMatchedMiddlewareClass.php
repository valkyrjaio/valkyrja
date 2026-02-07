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
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Tests\Classes\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestRouteMatchedMiddleware.
 */
class RouteMatchedMiddlewareClass implements RouteMatchedMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function routeMatched(ServerRequestContract $request, RouteContract $route, RouteMatchedHandlerContract $handler): RouteContract|ResponseContract
    {
        $this->updateCounter();

        return $handler->routeMatched($request, $route);
    }
}
