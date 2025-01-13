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

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\JsonResponse;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Routing\Model\Contract\Route;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct;

/**
 * Class StructRouteDispatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class ResponseStructMiddleware implements RouteDispatchedMiddleware
{
    /**
     * @inheritDoc
     */
    public function routeDispatched(ServerRequest $request, Response $response, Route $route, RouteDispatchedHandler $handler): Response
    {
        $responseStruct = $route->getResponseStruct();

        if ($responseStruct !== null && $response instanceof JsonResponse) {
            $response = $this->updateJsonWithResponseStruct($response, $responseStruct);
        }

        return $handler->routeDispatched($request, $response, $route);
    }

    /**
     * Update the Json in a response with a given response struct.
     *
     * @param JsonResponse                 $response       The json response
     * @param class-string<ResponseStruct> $responseStruct The response struct
     *
     * @return JsonResponse
     */
    protected function updateJsonWithResponseStruct(JsonResponse $response, string $responseStruct): JsonResponse
    {
        $data = $response->getBodyAsJson();

        return $response->withJsonAsBody($responseStruct::getStructuredData($data));
    }
}
