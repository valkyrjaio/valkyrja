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

namespace Valkyrja\Http\Server\Middleware\RouteMatched;

use Override;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;

class RequestStructMiddleware implements RouteMatchedMiddlewareContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function routeMatched(ServerRequestContract $request, RouteContract $route, RouteMatchedHandlerContract $handler): RouteContract|ResponseContract
    {
        $response = null;

        if ($route->hasRequestStruct()) {
            $message = $route->getRequestStruct();

            $response = $this->ensureRequestConformsToMessage($request, $route, $message);
        }

        return $response
            ?? $handler->routeMatched($request, $route);
    }

    /**
     * Ensure the request conforms to the request struct.
     */
    protected function ensureRequestConformsToMessage(ServerRequestContract $request, RouteContract $matchedRoute, RequestStructContract $struct): ResponseContract|null
    {
        return $this->ensureRequestHasNoExtraData($request, $matchedRoute, $struct)
            ?? $this->ensureRequestIsValid($request, $matchedRoute, $struct)
            ?? null;
    }

    /**
     * Ensure the request has no extra data.
     */
    protected function ensureRequestHasNoExtraData(ServerRequestContract $request, RouteContract $matchedRoute, RequestStructContract $struct): ResponseContract|null
    {
        // If there is extra data
        if ($struct::determineIfRequestContainsExtraData($request)) {
            // Then the payload is too large
            return $this->getExtraDataErrorResponse($request, $matchedRoute, $struct);
        }

        return null;
    }

    /**
     * Get the error response for extra data present in the request.
     */
    protected function getExtraDataErrorResponse(ServerRequestContract $request, RouteContract $matchedRoute, RequestStructContract $struct): ResponseContract
    {
        return new Response(
            statusCode: StatusCode::PAYLOAD_TOO_LARGE,
        );
    }

    /**
     * Ensure the request is valid.
     */
    protected function ensureRequestIsValid(ServerRequestContract $request, RouteContract $matchedRoute, RequestStructContract $struct): ResponseContract|null
    {
        $validate = $struct::validate($request);

        if (! $validate->validateRules()) {
            return $this->getValidationErrorsResponse($request, $matchedRoute, $validate, $struct);
        }

        return null;
    }

    /**
     * Get the error response for validations errors present in the request.
     */
    protected function getValidationErrorsResponse(
        ServerRequestContract $request,
        RouteContract $matchedRoute,
        ValidatorContract $validate,
        RequestStructContract $struct
    ): ResponseContract {
        return new Response(
            statusCode: StatusCode::BAD_REQUEST,
        );
    }
}
