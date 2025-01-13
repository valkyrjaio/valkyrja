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
use Valkyrja\Http\Routing\Model\Contract\Route;

/**
 * Interface RouteMatchedHandler.
 *
 * @author Melech Mizrachi
 */
interface RouteMatchedHandler
{
    /**
     * Middleware handler for after a route has been matched but before it has been dispatched.
     */
    public function routeMatched(ServerRequest $request, Route $route): Route|Response;
}
