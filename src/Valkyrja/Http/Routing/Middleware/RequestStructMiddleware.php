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
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;

use function assert;
use function is_a;

class RequestStructMiddleware implements RouteMatchedMiddlewareContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function routeMatched(ServerRequestContract $request, RouteContract $route, RouteMatchedHandlerContract $handler): RouteContract|ResponseContract
    {
        $response = null;
        $message  = $route->getRequestStruct();

        if ($message !== null) {
            $this->ensureIsStruct($message);

            $response = $this->ensureRequestConformsToMessage($request, $route, $message);
        }

        return $response
            ?? $handler->routeMatched($request, $route);
    }

    /**
     * Ensure a message is a message.
     *
     * @param string $message The message
     */
    protected function ensureIsStruct(string $message): void
    {
        assert($this->determineIsStruct($message));
    }

    /**
     * Determine if a dependency is a message.
     *
     * @param string $struct The message
     */
    protected function determineIsStruct(string $struct): bool
    {
        return is_a($struct, RequestStructContract::class, true);
    }

    /**
     * @param ServerRequestContract               $request      The request
     * @param RouteContract                       $matchedRoute The matched route
     * @param class-string<RequestStructContract> $struct       The message class name
     */
    protected function ensureRequestConformsToMessage(ServerRequestContract $request, RouteContract $matchedRoute, string $struct): ResponseContract|null
    {
        return $this->ensureRequestHasNoExtraData($request, $matchedRoute, $struct)
            ?? $this->ensureRequestIsValid($request, $matchedRoute, $struct)
            ?? null;
    }

    /**
     * @param ServerRequestContract               $request      The request
     * @param RouteContract                       $matchedRoute The matched route
     * @param class-string<RequestStructContract> $struct       The message class name
     */
    protected function ensureRequestHasNoExtraData(ServerRequestContract $request, RouteContract $matchedRoute, string $struct): ResponseContract|null
    {
        // If there is extra data
        if ($struct::determineIfRequestContainsExtraData($request)) {
            // Then the payload is too large
            return $this->getExtraDataErrorResponse($request, $matchedRoute, $struct);
        }

        return null;
    }

    /**
     * @param ServerRequestContract               $request      The request
     * @param RouteContract                       $matchedRoute The matched route
     * @param class-string<RequestStructContract> $struct       The message class name
     */
    protected function getExtraDataErrorResponse(ServerRequestContract $request, RouteContract $matchedRoute, string $struct): ResponseContract
    {
        return new Response(
            statusCode: StatusCode::PAYLOAD_TOO_LARGE,
        );
    }

    /**
     * @param ServerRequestContract               $request      The request
     * @param RouteContract                       $matchedRoute The matched route
     * @param class-string<RequestStructContract> $struct       The message class name
     */
    protected function ensureRequestIsValid(ServerRequestContract $request, RouteContract $matchedRoute, string $struct): ResponseContract|null
    {
        $validate = $struct::validate($request);

        if (! $validate->rules()) {
            return $this->getValidationErrorsResponse($request, $matchedRoute, $validate, $struct);
        }

        return null;
    }

    /**
     * @param ServerRequestContract               $request      The request
     * @param RouteContract                       $matchedRoute The matched route
     * @param ValidatorContract                   $validate     The validation object
     * @param class-string<RequestStructContract> $struct       The message class name
     */
    protected function getValidationErrorsResponse(
        ServerRequestContract $request,
        RouteContract $matchedRoute,
        ValidatorContract $validate,
        string $struct
    ): ResponseContract {
        return new Response(
            statusCode: StatusCode::BAD_REQUEST,
        );
    }
}
