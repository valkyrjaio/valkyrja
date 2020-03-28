<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Dispatchers;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Config\Models\Model;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Collections\Collection as CollectionClass;
use Valkyrja\Routing\Events\RouteMatched;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Helpers\RouteGroup;
use Valkyrja\Routing\Helpers\RouteMethods;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Matchers\Matcher as MatcherClass;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router as RouterContract;
use Valkyrja\Support\Providers\Provides;
use Valkyrja\View\View;

use function is_array;
use function strlen;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 */
class Router implements RouterContract
{
    use Provides;
    use RouteGroup;
    use RouteMethods;

    protected Application       $app;
    protected static Collection $collection;

    /**
     * Router constructor.
     *
     * @param Application     $application The application
     * @param Collection|null $collection
     */
    public function __construct(Application $application, Collection $collection)
    {
        $this->app        = $application;
        self::$collection = $collection;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            RouterContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(
            RouterContract::class,
            new static($app, new CollectionClass(new MatcherClass()))
        );

        $app->router()->setup();
    }

    /**
     * Get the route collection.
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        return self::$collection;
    }

    /**
     * Get the route matcher.
     *
     * @return Matcher
     */
    public function matcher(): Matcher
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
        $host = $absolute || $route->isSecure() || $this->app->config(ConfigKey::ROUTING_USE_ABSOLUTE_URLS, false)
            ? $this->routeHost($route)
            : '';
        // Get the path from the generator
        $path = $route->getSegments()
            ? $this->app->pathGenerator()->parse(
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
            $this->app->abort();
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
        return self::$collection->matcher()->match($path, $method);
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
        if ($host && $host !== $this->app->request()->getUri()->getHost()) {
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
     * Set the data from cache.
     *
     * @param bool $force    [optional] Whether to force setup
     * @param bool $useCache [optional] Whether to use cache
     *
     * @return void
     */
    public function setup(bool $force = false, bool $useCache = true): void
    {
        self::$collection->setup($force, $useCache);
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return Model|object
     */
    public function getCacheable(): object
    {
        return self::$collection->getCacheable();
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
            . request()->getUri()->getHostPort();
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
        if ($path[-1] !== '/' && $this->app->config(ConfigKey::ROUTING_TRAILING_SLASH, false)) {
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
        $this->app->events()->trigger(RouteMatched::class, [$route, $request]);
        // Set the found route in the service container
        $this->app->container()->setSingleton(Route::class, $route);

        // Attempt to dispatch the route using any one of the callable options
        $dispatch = $this->app->dispatcher()->dispatch($route, $route->getMatches());
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
            $this->app->redirect($route->getTo(), $route->getCode())->throw();
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
            $this->app->redirect()->secure($request->getUri()->getPath())->throw();
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
        return $this->app->kernel()->requestMiddleware($request, $route->getMiddleware() ?? []);
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
            return $this->app->response($dispatch->render());
        }

        // If the dispatch is an array, return it as JSON
        if (is_array($dispatch)) {
            return $this->app->json($dispatch);
        }

        // Otherwise its a string so wrap it in a new response and return it
        return $this->app->response((string) $dispatch);
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
        return $this->app->kernel()->responseMiddleware(
            $request,
            $response,
            $route->getMiddleware() ?? []
        );
    }
}
