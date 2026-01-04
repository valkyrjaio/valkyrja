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
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Abstract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;

/**
 * @extends Handler<RouteNotMatchedMiddlewareContract>
 */
class RouteNotMatchedHandler extends Handler implements RouteNotMatchedHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function routeNotMatched(ServerRequestContract $request, ResponseContract $response): ResponseContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->routeNotMatched($request, $response, $this)
            : $response;
    }
}
