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

namespace Valkyrja\Http\Middleware\Handler;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler as Contract;

/**
 * Class RouteNotMatchedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<RouteNotMatchedMiddleware>
 */
class RouteNotMatchedHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function routeNotMatched(ServerRequest $request, Response $response): Response
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->routeNotMatched($request, $response, $this)
            : $response;
    }
}
