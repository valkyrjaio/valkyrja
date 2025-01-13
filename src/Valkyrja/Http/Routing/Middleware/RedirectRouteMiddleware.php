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

namespace Valkyrja\Http\Routing\Middleware;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Routing\Model\Contract\Route;

/**
 * Class RedirectRouteMiddleware.
 *
 * @author Melech Mizrachi
 */
class RedirectRouteMiddleware implements RouteMatchedMiddleware
{
    public function __construct(
        protected ResponseFactory $responseFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function routeMatched(ServerRequest $request, Route $route, RouteMatchedHandler $handler): Route|Response
    {
        // Redirect to the redirect path
        return $this->responseFactory
            ->createRedirectResponse(
                uri: $route->getTo(),
                statusCode: $route->getCode() ?? StatusCode::FOUND
            );
    }
}
