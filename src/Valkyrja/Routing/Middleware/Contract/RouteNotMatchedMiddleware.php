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

namespace Valkyrja\Routing\Middleware\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Interface RouteNotMatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
interface RouteNotMatchedMiddleware
{
    /**
     * Middleware handler for after a route has not been matched.
     *
     * @param ServerRequest $request  The request
     * @param Response      $response The response
     *
     * @return Response
     */
    public static function routeNotMatched(ServerRequest $request, Response $response): Response;
}
