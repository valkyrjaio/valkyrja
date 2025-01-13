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
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Routing\Model\Contract\Route;

/**
 * Class TestRouteMatchedMiddlewareChanged.
 *
 * @author Melech Mizrachi
 */
class TestRouteMatchedMiddlewareChanged implements RouteMatchedMiddleware
{
    use MiddlewareCounter;

    public function routeMatched(ServerRequest $request, Route $route, RouteMatchedHandler $handler): Route|Response
    {
        $this->updateCounter();

        return new \Valkyrja\Http\Message\Response\Response();
    }
}
