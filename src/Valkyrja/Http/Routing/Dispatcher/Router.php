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

namespace Valkyrja\Http\Routing\Dispatcher;

use Override;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\Container as ContainerContract;
use Valkyrja\Dispatch\Dispatcher\Contract\Dispatcher as DispatcherContract;
use Valkyrja\Dispatch\Dispatcher\Dispatcher;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Factory\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler as RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler as RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler as RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler as SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler as TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler as ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Dispatcher\Contract\Router as Contract;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher as MatcherContract;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteNameException;

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
        protected ContainerContract $container = new Container(),
        protected DispatcherContract $dispatcher = new Dispatcher(),
        protected MatcherContract $matcher = new Matcher(),
        protected ResponseFactory $responseFactory = new HttpMessageResponseFactory(),
        protected ThrowableCaughtHandlerContract $throwableCaughtHandler = new ThrowableCaughtHandler(),
        protected RouteMatchedHandlerContract $routeMatchedHandler = new RouteMatchedHandler(),
        protected RouteNotMatchedHandlerContract $routeNotMatchedHandler = new RouteNotMatchedHandler(),
        protected RouteDispatchedHandlerContract $routeDispatchedHandler = new RouteDispatchedHandler(),
        protected SendingResponseHandlerContract $sendingResponseHandler = new SendingResponseHandler(),
        protected TerminatedHandlerContract $terminatedHandler = new TerminatedHandler()
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
     */
    #[Override]
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
        $arguments = $dispatch->getArguments();

        // Attempt to dispatch the route using any one of the callable options
        $response = $this->dispatcher->dispatch(
            dispatch: $dispatch,
            arguments: $arguments
        );

        if (! $response instanceof Response) {
            throw new InvalidRouteNameException('Dispatch must be a valid response');
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
        /** @var non-empty-string $requestPath */
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
