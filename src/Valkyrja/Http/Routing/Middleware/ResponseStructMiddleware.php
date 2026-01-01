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

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;

/**
 * Class StructRouteDispatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class ResponseStructMiddleware implements RouteDispatchedMiddlewareContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function routeDispatched(
        ServerRequestContract $request,
        ResponseContract $response,
        RouteContract $route,
        RouteDispatchedHandlerContract $handler
    ): ResponseContract {
        $responseStruct = $route->getResponseStruct();

        if ($responseStruct !== null && $response instanceof JsonResponseContract) {
            $response = $this->updateJsonWithResponseStruct($response, $responseStruct);
        }

        return $handler->routeDispatched($request, $response, $route);
    }

    /**
     * Update the Json in a response with a given response struct.
     *
     * @param JsonResponseContract                 $response       The json response
     * @param class-string<ResponseStructContract> $responseStruct The response struct
     *
     * @return JsonResponseContract
     */
    protected function updateJsonWithResponseStruct(JsonResponseContract $response, string $responseStruct): JsonResponseContract
    {
        $data = $response->getBodyAsJson();

        /** @psalm-suppress MixedArgumentTypeCoercion */
        return $response->withJsonAsBody($responseStruct::getStructuredData($data));
    }
}
