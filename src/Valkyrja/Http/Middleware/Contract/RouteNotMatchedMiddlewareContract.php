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

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;

/**
 * Interface RouteNotMatchedMiddlewareContract.
 *
 * @author Melech Mizrachi
 */
interface RouteNotMatchedMiddlewareContract
{
    /**
     * Middleware handler for after a route has not been matched.
     *
     * @param ServerRequestContract $request  The request
     * @param ResponseContract      $response The response
     *
     * @return ResponseContract
     */
    public function routeNotMatched(ServerRequestContract $request, ResponseContract $response, RouteNotMatchedHandlerContract $handler): ResponseContract;
}
