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

use Valkyrja\Http\Request\Contract\ServerRequest;
use Valkyrja\Http\Response\Contract\Response;

/**
 * Interface RouteMatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
interface RouteMatchedMiddleware
{
    /**
     * Middleware handler for after a route has been matched but before it has been dispatched.
     *
     * @param ServerRequest $request The request
     *
     * @return ServerRequest|Response
     */
    public static function routeMatched(ServerRequest $request): ServerRequest|Response;
}
