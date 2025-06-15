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
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Response\Response as HttpResponse;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Routing\Model\Contract\Route;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct;
use Valkyrja\Validation\Contract\Validate;

use function assert;
use function is_a;

/**
 * Class RequestStructMiddleware.
 *
 * @author Melech Mizrachi
 */
class RequestStructMiddleware implements RouteMatchedMiddleware
{
    /**
     * @inheritDoc
     */
    public function routeMatched(ServerRequest $request, Route $route, RouteMatchedHandler $handler): Route|Response
    {
        $message = $route->getRequestStruct();

        if ($message !== null) {
            $this->ensureIsStruct($message);

            $response = $this->ensureRequestConformsToMessage($request, $route, $message);
        }

        /**
         * @psalm-suppress MixedReturnStatement No clue what Psalm is confused about here. $response is either a Response or null, and routeMatched() returns a
         * Route or Response
         */
        return $response
            ?? $handler->routeMatched($request, $route);
    }

    /**
     * Ensure a message is a message.
     *
     * @param string $message The message
     *
     * @return void
     */
    protected function ensureIsStruct(string $message): void
    {
        assert($this->determineIsStruct($message));
    }

    /**
     * Determine if a dependency is a message.
     *
     * @param string $struct The message
     *
     * @return bool
     */
    protected function determineIsStruct(string $struct): bool
    {
        return is_a($struct, RequestStruct::class, true);
    }

    /**
     * @param ServerRequest               $request      The request
     * @param Route                       $matchedRoute The matched route
     * @param class-string<RequestStruct> $struct       The message class name
     *
     * @return Response|null
     */
    protected function ensureRequestConformsToMessage(ServerRequest $request, Route $matchedRoute, string $struct): Response|null
    {
        return $this->ensureRequestHasNoExtraData($request, $matchedRoute, $struct)
            ?? $this->ensureRequestIsValid($request, $matchedRoute, $struct)
            ?? null;
    }

    /**
     * @param ServerRequest               $request      The request
     * @param Route                       $matchedRoute The matched route
     * @param class-string<RequestStruct> $struct       The message class name
     *
     * @return Response|null
     */
    protected function ensureRequestHasNoExtraData(ServerRequest $request, Route $matchedRoute, string $struct): Response|null
    {
        // If there is extra data
        if ($struct::determineIfRequestContainsExtraData($request)) {
            // Then the payload is too large
            return $this->getExtraDataErrorResponse($request, $matchedRoute, $struct);
        }

        return null;
    }

    /**
     * @param ServerRequest               $request      The request
     * @param Route                       $matchedRoute The matched route
     * @param class-string<RequestStruct> $struct       The message class name
     *
     * @return Response
     */
    protected function getExtraDataErrorResponse(ServerRequest $request, Route $matchedRoute, string $struct): Response
    {
        return new HttpResponse(
            statusCode: StatusCode::PAYLOAD_TOO_LARGE,
        );
    }

    /**
     * @param ServerRequest               $request      The request
     * @param Route                       $matchedRoute The matched route
     * @param class-string<RequestStruct> $struct       The message class name
     *
     * @return Response|null
     */
    protected function ensureRequestIsValid(ServerRequest $request, Route $matchedRoute, string $struct): Response|null
    {
        $validate = $struct::validate($request);

        if (! $validate->rules()) {
            return $this->getValidationErrorsResponse($request, $matchedRoute, $validate, $struct);
        }

        return null;
    }

    /**
     * @param ServerRequest               $request      The request
     * @param Route                       $matchedRoute The matched route
     * @param Validate                    $validate     The validation object
     * @param class-string<RequestStruct> $struct       The message class name
     *
     * @return Response
     */
    protected function getValidationErrorsResponse(ServerRequest $request, Route $matchedRoute, Validate $validate, string $struct): Response
    {
        return new HttpResponse(
            statusCode: StatusCode::BAD_REQUEST,
        );
    }
}
