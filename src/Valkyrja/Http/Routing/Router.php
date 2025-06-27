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

namespace Valkyrja\Http\Routing;

use JsonException;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Dispatcher\Data\Contract\ClassDispatch;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Exception\HttpException;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Factory\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Contract\Router as Contract;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher;

use function is_array;
use function is_float;
use function is_int;
use function is_string;
use function rawurldecode;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 */
class Router implements Contract
{
    /**
     * Router constructor.
     */
    public function __construct(
        protected Container $container = new \Valkyrja\Container\Container(),
        protected Dispatcher $dispatcher = new \Valkyrja\Dispatcher\Dispatcher(),
        protected Matcher $matcher = new \Valkyrja\Http\Routing\Matcher\Matcher(),
        protected ResponseFactory $responseFactory = new HttpMessageResponseFactory(),
        protected ThrowableCaughtHandler $throwableCaughtHandler = new Middleware\Handler\ThrowableCaughtHandler(),
        protected RouteMatchedHandler $routeMatchedHandler = new Middleware\Handler\RouteMatchedHandler(),
        protected RouteNotMatchedHandler $routeNotMatchedHandler = new Middleware\Handler\RouteNotMatchedHandler(),
        protected RouteDispatchedHandler $routeDispatchedHandler = new Middleware\Handler\RouteDispatchedHandler(),
        protected SendingResponseHandler $sendingResponseHandler = new Middleware\Handler\SendingResponseHandler(),
        protected TerminatedHandler $terminatedHandler = new Middleware\Handler\TerminatedHandler()
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function dispatch(ServerRequest $request): Response
    {
        // Attempt to match the route
        $matchedRoute = $this->attemptToMatchRoute($request);

        // If the route was not matched a response returned
        if ($matchedRoute instanceof Response) {
            // Dispatch RouteNotMatchedMiddleware
            return $this->routeNotMatchedHandler->routeNotMatched(
                request: $request,
                response: $matchedRoute
            );
        }

        return $this->dispatchRoute(
            request: $request,
            route: $matchedRoute
        );
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function dispatchRoute(ServerRequest $request, Route $route): Response
    {
        // The route has been matched
        $this->routeMatched($route);

        // Dispatch the RouteMatchedMiddleware
        $routeAfterMiddleware = $this->routeMatchedHandler->routeMatched(
            request: $request,
            route: $route
        );

        // If the return value after middleware is a response return it
        if ($routeAfterMiddleware instanceof Response) {
            return $routeAfterMiddleware;
        }

        // Set the route after middleware has potentially modified it in the service container
        $this->container->setSingleton(Route::class, $routeAfterMiddleware);

        $dispatch  = $routeAfterMiddleware->getDispatch();
        $arguments = null;

        if ($dispatch instanceof ClassDispatch) {
            $arguments = $dispatch->getArguments();
        }

        // Attempt to dispatch the route using any one of the callable options
        $response = $this->dispatcher->dispatch(
            dispatch: $dispatch,
            arguments: $arguments
        );

        if (! $response instanceof Response) {
            return $this->getResponseForDispatch($response);
        }

        return $this->routeDispatchedHandler->routeDispatched(
            request: $request,
            response: $response,
            route: $routeAfterMiddleware
        );
    }

    /**
     * Match a route, or a response if no route exists, from a given server request.
     *
     * @param ServerRequest $request The request
     *
     * @throws HttpException
     *
     * @return Route|Response
     */
    protected function attemptToMatchRoute(ServerRequest $request): Route|Response
    {
        // Decode the request uri
        $requestPath = rawurldecode($request->getUri()->getPath());
        // Try to match the route
        $route = $this->matcher->match(
            path: $requestPath,
            requestMethod: $request->getMethod()
        );

        // Return the route if it was found
        if ($route !== null) {
            return $route;
        }

        // If the route matches for any method
        if ($this->matcher->match($requestPath) !== null) {
            // Then the route exists but not for the requested method, and so it is not allowed
            return $this->responseFactory->createResponse(
                statusCode: StatusCode::METHOD_NOT_ALLOWED,
            );
        }

        // Otherwise return a response with a 404
        return $this->responseFactory->createResponse(
            statusCode: StatusCode::NOT_FOUND,
        );
    }

    /**
     * Get a response object from a mixed response from a dispatch.
     *
     * @param mixed $response The response
     *
     * @throws JsonException
     *
     * @return Response
     */
    protected function getResponseForDispatch(mixed $response): Response
    {
        return match (true) {
            is_string($response) => $this->getResponseForString($response),
            is_int($response)    => $this->getResponseForInt($response),
            is_float($response)  => $this->getResponseForFloat($response),
            is_array($response)  => $this->getResponseForArray($response),
            default              => throw new InvalidRouteNameException('Dispatch must be a valid response')
        };
    }

    /**
     * Get a response object from a string response from a dispatch.
     *
     * @param string $response The response
     *
     * @return Response
     */
    protected function getResponseForString(string $response): Response
    {
        if (str_starts_with($response, '/')) {
            return $this->responseFactory->createRedirectResponse(
                uri: $response,
            );
        }

        return $this->responseFactory->createTextResponse(
            content: $response,
        );
    }

    /**
     * Get a response object from an int response from a dispatch.
     *
     * @param int $response The response
     *
     * @return Response
     */
    protected function getResponseForInt(int $response): Response
    {
        return $this->getResponseForString((string) $response);
    }

    /**
     * Get a response object from a float response from a dispatch.
     *
     * @param float $response The response
     *
     * @return Response
     */
    protected function getResponseForFloat(float $response): Response
    {
        return $this->getResponseForString((string) $response);
    }

    /**
     * Get a response object from an array response from a dispatch.
     *
     * @param array<array-key, mixed> $response The response
     *
     * @throws JsonException
     *
     * @return Response
     */
    protected function getResponseForArray(array $response): Response
    {
        return $this->responseFactory->createJsonResponse(
            data: $response,
        );
    }

    /**
     * Do various stuff after the route has been matched.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function routeMatched(Route $route): void
    {
        $this->routeMatchedHandler->add(...$route->getRouteMatchedMiddleware());
        $this->routeDispatchedHandler->add(...$route->getRouteDispatchedMiddleware());
        $this->throwableCaughtHandler->add(...$route->getThrowableCaughtMiddleware());
        $this->sendingResponseHandler->add(...$route->getSendingResponseMiddleware());
        $this->terminatedHandler->add(...$route->getTerminatedMiddleware());

        // Set the found route in the service container
        $this->container->setSingleton(Route::class, $route);
    }
}
