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

use Valkyrja\Container\Contract\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Factory\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware;
use Valkyrja\Http\Middleware\Handler\Contract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Collection\Collection as RouteCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Contract\Router as Contract;
use Valkyrja\Http\Routing\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher;
use Valkyrja\Http\Routing\Model\Contract\Route;

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
        protected Collection $collection = new RouteCollection(),
        protected Container $container = new \Valkyrja\Container\Container(),
        protected Dispatcher $dispatcher = new \Valkyrja\Dispatcher\Dispatcher(),
        protected Matcher $matcher = new \Valkyrja\Http\Routing\Matcher\Matcher(),
        protected ResponseFactory $responseFactory = new HttpMessageResponseFactory(),
        protected ThrowableCaughtHandler&Handler $exceptionHandler = new Middleware\Handler\ThrowableCaughtHandler(),
        protected RouteMatchedHandler&Handler $routeMatchedHandler = new Middleware\Handler\RouteMatchedHandler(),
        protected RouteNotMatchedHandler&Handler $routeNotMatchedHandler = new Middleware\Handler\RouteNotMatchedHandler(),
        protected RouteDispatchedHandler&Handler $routeDispatchedHandler = new Middleware\Handler\RouteDispatchedHandler(),
        protected SendingResponseHandler&Handler $sendingResponseHandler = new Middleware\Handler\SendingResponseHandler(),
        protected TerminatedHandler&Handler $terminatedHandler = new Middleware\Handler\TerminatedHandler(),
        protected Config $config = new Config(),
        protected bool $debug = false
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function debug(): bool
    {
        return $this->debug;
    }

    /**
     * @inheritDoc
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * @inheritDoc
     */
    public function getMatcher(): Matcher
    {
        return $this->matcher;
    }

    /**
     * @inheritDoc
     */
    public function addRoute(Route $route): void
    {
        // Set the route in the collection
        $this->collection->add($route);
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(): array
    {
        return $this->collection->allFlattened();
    }

    /**
     * @inheritDoc
     */
    public function getRoute(string $name): Route
    {
        // If no route was found
        if (! $this->hasRoute($name) || ! $route = $this->collection->getNamed($name)) {
            throw new InvalidRouteNameException($name);
        }

        return $route;
    }

    /**
     * @inheritDoc
     */
    public function hasRoute(string $name): bool
    {
        return $this->collection->hasNamed($name);
    }

    /**
     * @inheritDoc
     */
    public function attemptToMatchRoute(ServerRequest $request): Route|Response
    {
        // Decode the request uri
        $requestUri = rawurldecode($request->getUri()->getPath());
        // Try to match the route
        $route = $this->matcher->match($requestUri, $request->getMethod());

        // Return the route if it was found
        if ($route !== null) {
            return $route;
        }

        // If the route matches for any method
        if ($this->matcher->match($requestUri) !== null) {
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
     * @inheritDoc
     */
    public function dispatch(ServerRequest $request): Response
    {
        // Attempt to match the route
        $matchedRoute = $this->attemptToMatchRoute($request);

        // If the route was not matched a response returned
        if ($matchedRoute instanceof Response) {
            // Dispatch RouteNotMatchedMiddleware
            return $this->routeNotMatchedHandler->routeNotMatched($request, $matchedRoute);
        }

        return $this->dispatchRoute($request, $matchedRoute);
    }

    /**
     * @inheritDoc
     */
    public function dispatchRoute(ServerRequest $request, Route $route): Response
    {
        // The route has been matched
        $this->routeMatched($route);

        // Dispatch the RouteMatchedMiddleware
        $routeAfterMiddleware = $this->routeMatchedHandler->routeMatched($request, $route);

        // If the return value after middleware is a response return it
        if ($routeAfterMiddleware instanceof Response) {
            return $routeAfterMiddleware;
        }

        // Set the route after middleware has potentially modified it in the service container
        $this->container->setSingleton(Route::class, $routeAfterMiddleware);

        // Attempt to dispatch the route using any one of the callable options
        $response = $this->dispatcher->dispatch($routeAfterMiddleware, $routeAfterMiddleware->getMatches());

        if (! $response instanceof Response) {
            throw new InvalidRouteNameException('Dispatch must be a response');
        }

        return $this->routeDispatchedHandler->routeDispatched($request, $response, $routeAfterMiddleware);
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
        $matchedMiddleware    = $route->getMatchedMiddleware();
        $dispatchedMiddleware = $route->getDispatchedMiddleware();
        $exceptionMiddleware  = $route->getExceptionMiddleware();
        $sendingMiddleware    = $route->getSendingMiddleware();
        $terminatedMiddleware = $route->getTerminatedMiddleware();

        // Add all the middleware defined for the route to the respective middleware handlers
        if ($matchedMiddleware !== null) {
            $this->routeMatchedHandler->add(...$matchedMiddleware);
        }

        if ($dispatchedMiddleware !== null) {
            $this->routeDispatchedHandler->add(...$dispatchedMiddleware);
        }

        if ($exceptionMiddleware !== null) {
            $this->exceptionHandler->add(...$exceptionMiddleware);
        }

        if ($sendingMiddleware !== null) {
            $this->sendingResponseHandler->add(...$sendingMiddleware);
        }

        if ($terminatedMiddleware !== null) {
            $this->terminatedHandler->add(...$terminatedMiddleware);
        }

        // Set the found route in the service container
        $this->container->setSingleton(Route::class, $route);
    }
}
