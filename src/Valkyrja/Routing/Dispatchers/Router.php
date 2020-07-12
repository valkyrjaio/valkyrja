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
use RuntimeException;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Events\RouteMatched;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Helpers\RouteGroup;
use Valkyrja\Routing\Helpers\RouteMethods;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router as Contract;
use Valkyrja\Routing\Support\Abort;
use Valkyrja\Routing\Support\Middleware;
use Valkyrja\Routing\Support\MiddlewareAwareTrait;
use Valkyrja\View\View;

use function is_array;
use function rawurldecode;
use function str_replace;
use function strlen;
use function strpos;
use function substr;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 */
class Router implements Contract
{
    use MiddlewareAwareTrait;
    use RouteGroup;
    use RouteMethods;

    /**
     * The route collection.
     *
     * @var Collection
     */
    protected static Collection $collection;

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
     * The request.
     *
     * @var Request
     */
    protected Request $request;

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
     * @param Container       $container
     * @param Dispatcher      $dispatcher
     * @param Events          $events
     * @param Request         $request
     * @param ResponseFactory $responseFactory
     * @param Collection      $collection
     * @param array           $config
     * @param bool            $debug
     */
    public function __construct(
        Container $container,
        Dispatcher $dispatcher,
        Events $events,
        Request $request,
        ResponseFactory $responseFactory,
        Collection $collection,
        array $config,
        bool $debug = false
    ) {
        $this->container       = $container;
        $this->dispatcher      = $dispatcher;
        $this->events          = $events;
        $this->request         = $request;
        $this->responseFactory = $responseFactory;
        $this->config          = $config;
        $this->debug           = $debug;

        self::$collection = $collection;

        Middleware::$container = $container;
        Middleware::$router    = $this;
    }

    /**
     * Get the config.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Whether to run in debug.
     *
     * @return bool
     */
    public function debug(): bool
    {
        return $this->debug;
    }

    /**
     * Get the route collection.
     *
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return self::$collection;
    }

    /**
     * Get the route matcher.
     *
     * @return Matcher
     */
    public function getMatcher(): Matcher
    {
        return self::$collection->matcher();
    }

    /**
     * Set a single route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function addRoute(Route $route): void
    {
        // Set the route in the collection
        self::$collection->add($route);
    }

    /**
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return self::$collection->allFlattened();
    }

    /**
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @throws InvalidRouteName
     *
     * @return Route
     */
    public function getRoute(string $name): Route
    {
        // If no route was found
        if (! $this->hasRoute($name) || ! $route = self::$collection->getNamed($name)) {
            throw new InvalidRouteName($name);
        }

        return $route;
    }

    /**
     * Determine whether a route name exists.
     *
     * @param string $name The name of the route
     *
     * @return bool
     */
    public function hasRoute(string $name): bool
    {
        return self::$collection->hasNamed($name);
    }

    /**
     * Get a route url by name.
     *
     * @param string $name     The name of the route to get
     * @param array  $data     [optional] The route data if dynamic
     * @param bool   $absolute [optional] Whether this url should be absolute
     *
     * @throws InvalidRouteName
     *
     * @return string
     */
    public function getUrl(string $name, array $data = null, bool $absolute = null): string
    {
        // Get the matching route
        $route = $this->getRoute($name);
        // Set the host to use if this is an absolute url
        // or the config is set to always use absolute urls
        // or the route is secure (needs https:// appended)
        $host = $absolute || $route->isSecure() || $this->config['useAbsoluteUrls']
            ? $this->routeHost($route)
            : '';
        /** @var PathGenerator $pathGenerator */
        $pathGenerator = $this->container->getSingleton(PathGenerator::class);
        // Get the path from the generator
        $path = $route->getSegments()
            ? $pathGenerator->parse(
                $route->getSegments(),
                $data,
                $route->getParams()
            )
            : $route->getPath();

        if (null === $path) {
            throw new RuntimeException('Invalid path for route with name: ' . $name);
        }

        return $host . $this->validateRouteUrl($path);
    }

    /**
     * Get a route from a request.
     *
     * @param Request $request The request
     *
     * @throws InvalidArgumentException
     * @throws HttpException
     *
     * @return Route
     */
    public function getRouteFromRequest(Request $request): Route
    {
        // Decode the request uri
        $requestUri = rawurldecode($request->getUri()->getPath());
        // Try to match the route
        $route = $this->getRouteByPath($requestUri, $request->getMethod());

        // If no route is found
        if (null === $route) {
            // Abort with 404
            Abort::abort(StatusCode::NOT_FOUND);
        }

        return $route;
    }

    /**
     * Get a route by path.
     *
     * @param string $path   The path
     * @param string $method [optional] The method type of get
     *
     * @throws InvalidArgumentException
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function getRouteByPath(string $path, string $method = null): ?Route
    {
        return self::$collection->matcher()->match($path, $method ?? RequestMethod::GET);
    }

    /**
     * Determine if a uri is internal.
     *
     * @param string $uri The uri to check
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function isInternalUri(string $uri): bool
    {
        // Replace the scheme if it exists
        $uri = str_replace(['http://', 'https://'], '', $uri);

        // Get the host of the uri
        $host = (string) substr($uri, 0, strpos($uri, '/'));

        // If the host does not match the current request uri's host
        if ($host && $host !== $this->request->getUri()->getHost()) {
            // Return false immediately
            return false;
        }

        // Get only the path (full string from the first slash to the end of the path)
        $uri = (string) substr($uri, strpos($uri, '/'), strlen($uri));

        // Try to match the route
        $route = $this->getRouteByPath($uri);

        return $route instanceof Route;
    }

    /**
     * Get a route's host.
     *
     * @param Route $route The route
     *
     * @return string
     */
    protected function routeHost(Route $route): string
    {
        return 'http'
            . ($route->isSecure() ? 's' : '')
            . '://'
            . $this->request->getUri()->getHostPort();
    }

    /**
     * Validate the route url.
     *
     * @param string $path The path
     *
     * @return string
     */
    protected function validateRouteUrl(string $path): string
    {
        // If the last character is not a slash and the config is set to
        // ensure trailing slash
        if ($path[-1] !== '/' && $this->config['useTrailingSlash']) {
            // add a trailing slash
            $path .= '/';
        }

        return $path;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @param Request $request The request
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public function dispatch(Request $request): Response
    {
        // Get the route
        $route = $this->getRouteFromRequest($request);

        // Determine if the route is a redirect
        $this->determineRedirectRoute($route);
        // Determine if the route is secure and should be redirected
        $this->determineIsSecureRoute($request, $route);
        // Dispatch the route's before request handled middleware
        $request = $this->routeRequestMiddleware($request, $route);

        // Trigger an event for route matched
        $this->events->trigger(RouteMatched::class, [$route, $request]);
        // Set the found route in the service container
        $this->container->setSingleton(Route::class, $route);

        // Attempt to dispatch the route using any one of the callable options
        $dispatch = $this->dispatcher->dispatch($route, $route->getMatches());
        // Get the response from the dispatch
        $response = $this->getResponseFromDispatch($dispatch);

        // Dispatch the route's before request handled middleware and return the response
        return $this->routeResponseMiddleware($request, $response, $route);
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
     * Dispatch a route's before request handled middleware.
     *
     * @param Request $request The request
     * @param Route   $route   The route
     *
     * @return Request
     */
    protected function routeRequestMiddleware(Request $request, Route $route): Request
    {
        return $this->requestMiddleware($request, $route->getMiddleware() ?? []);
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
        if ($dispatch instanceof View) {
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
