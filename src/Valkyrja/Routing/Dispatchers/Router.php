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

namespace Valkyrja\Routing\Dispatchers;

use InvalidArgumentException;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Events\RouteMatched;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router as Contract;
use Valkyrja\Routing\Support\Abort;
use Valkyrja\Routing\Support\Controller;
use Valkyrja\Routing\Support\Middleware;
use Valkyrja\Routing\Support\MiddlewareAwareTrait;
use Valkyrja\View\Template;

use function is_array;
use function rawurldecode;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 */
class Router implements Contract
{
    use MiddlewareAwareTrait;

    /**
     * The route collection.
     *
     * @var Collection
     */
    protected Collection $collection;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The dispatcher.
     *
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * The events.
     *
     * @var Events
     */
    protected Events $events;

    /**
     * The matcher.
     *
     * @var Matcher
     */
    protected Matcher $matcher;

    /**
     * The response factory.
     *
     * @var ResponseFactory
     */
    protected ResponseFactory $responseFactory;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Whether to run in debug.
     *
     * @var bool
     */
    protected bool $debug;

    /**
     * Router constructor.
     *
     * @param Collection      $collection
     * @param Container       $container
     * @param Dispatcher      $dispatcher
     * @param Events          $events
     * @param Matcher         $matcher
     * @param ResponseFactory $responseFactory
     * @param array           $config
     * @param bool            $debug
     */
    public function __construct(
        Collection $collection,
        Container $container,
        Dispatcher $dispatcher,
        Events $events,
        Matcher $matcher,
        ResponseFactory $responseFactory,
        array $config,
        bool $debug = false
    ) {
        $this->collection      = $collection;
        $this->container       = $container;
        $this->dispatcher      = $dispatcher;
        $this->events          = $events;
        $this->matcher         = $matcher;
        $this->responseFactory = $responseFactory;
        $this->config          = $config;
        $this->debug           = $debug;

        Controller::$container       = Middleware::$container = $container;
        Controller::$events          = Middleware::$events = $events;
        Controller::$responseFactory = Middleware::$responseFactory = $responseFactory;
        Controller::$router          = Middleware::$router = $this;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
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
            throw new InvalidRouteName($name);
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
     *
     * @throws InvalidArgumentException
     */
    public function getRouteFromRequest(Request $request): Route
    {
        // Decode the request uri
        $requestUri = rawurldecode($request->getUri()->getPath());
        // Try to match the route
        $route = $this->matcher->match($requestUri, $request->getMethod() ?? RequestMethod::GET);

        // If no route is found
        if (null === $route) {
            // Abort with 404
            Abort::abort404();
        }

        return $route;
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     */
    public function dispatch(Request $request): Response
    {
        // Get the route
        $route = $this->getRouteFromRequest($request);

        // Determine if the route is a redirect
        $this->determineRedirectRoute($route);
        // Determine if the route is secure and should be redirected
        $this->determineIsSecureRoute($request, $route);
        // Set the route in the middleware
        Middleware::$route = $route;
        // Dispatch the route's before request handled middleware
        $requestAfterMiddleware = $this->requestMiddleware($request, $route->getMiddleware() ?? []);

        // If the return value after middleware is a response return it
        if ($requestAfterMiddleware instanceof Response) {
            return $requestAfterMiddleware;
        }

        // Trigger an event for route matched
        $this->events->trigger(RouteMatched::class, [$route, $requestAfterMiddleware]);
        // Set the found route in the service container
        $this->container->setSingleton(Route::class, $route);

        // Set the request in the abstract controller
        Controller::$request = $request;
        // Set the route in the abstract controller
        Controller::$route = $route;

        // Attempt to dispatch the route using any one of the callable options
        $dispatch = $this->dispatcher->dispatch($route, $route->getMatches());
        // Get the response from the dispatch
        $response = $this->getResponseFromDispatch($dispatch);

        // Dispatch the route's before request handled middleware and return the response
        return $this->routeResponseMiddleware($requestAfterMiddleware, $response, $route);
    }

    /**
     * Determine if a route is a redirect.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function determineRedirectRoute(Route $route): void
    {
        // If the route is a redirect and a redirect route is set
        if ($route->isRedirect()) {
            // Throw the redirect to the redirect path
            $this->responseFactory->createRedirectResponse($route->getTo(), $route->getCode())->throw();
        }
    }

    /**
     * Determine if the route should be secure.
     *
     * @param Request $request The request
     * @param Route   $route   The route
     *
     * @return void
     */
    protected function determineIsSecureRoute(Request $request, Route $route): void
    {
        // If the route is secure and the current request is not secure
        if ($route->isSecure() && ! $request->getUri()->isSecure()) {
            // Throw the redirect to the secure path
            $this->responseFactory->createRedirectResponse()->secure($request->getUri()->getPath(), $request)->throw();
        }
    }

    /**
     * Get a response from a dispatch.
     *
     * @param mixed $dispatch The dispatch
     *
     * @return Response
     */
    protected function getResponseFromDispatch($dispatch): Response
    {
        // If the dispatch is a Response then simply return it
        if ($dispatch instanceof Response) {
            return $dispatch;
        }

        // If the dispatch is a View, render it then wrap it in a new response and return it
        if ($dispatch instanceof Template) {
            return $this->responseFactory->createResponse($dispatch->render());
        }

        // If the dispatch is an array, return it as JSON
        if (is_array($dispatch)) {
            return $this->responseFactory->createJsonResponse($dispatch);
        }

        // Otherwise its a string so wrap it in a new response and return it
        return $this->responseFactory->createResponse((string) $dispatch);
    }

    /**
     * Dispatch a route's after request handled middleware.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     * @param Route    $route    The route
     *
     * @return Response
     */
    protected function routeResponseMiddleware(Request $request, Response $response, Route $route): Response
    {
        return $this->responseMiddleware($request, $response, $route->getMiddleware() ?? []);
    }
}
