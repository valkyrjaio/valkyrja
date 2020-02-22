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

namespace Valkyrja\Routing\Helpers;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Http\Exceptions\NotFoundHttpException;
use Valkyrja\Http\Request;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\RouteCollection;
use Valkyrja\Routing\RouteMatcher;

use function count;

/**
 * Trait RouteHelpers.
 *
 * @author Melech Mizrachi
 *
 * @property RouteCollection $collection
 * @property Application     $app
 */
trait RouteHelpers
{
    /**
     * Set a single route.
     *
     * @param Route $route The route
     *
     * @throws InvalidClosureException
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws InvalidArgumentException
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
        return self::$collection->all();
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
    public function route(string $name): Route
    {
        // If no route was found
        if (! $this->routeIsset($name)) {
            throw new InvalidRouteName($name);
        }

        return self::$collection->getNamed($name);
    }

    /**
     * Determine whether a route name exists.
     *
     * @param string $name The name of the route
     *
     * @return bool
     */
    public function routeIsset(string $name): bool
    {
        return self::$collection->issetNamed($name);
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
    public function routeUrl(string $name, array $data = null, bool $absolute = null): string
    {
        // Get the matching route
        $route = $this->route($name);
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

        return $host . $this->validateRouteUrl($path);
    }

    /**
     * Get a route from a request.
     *
     * @param Request $request The request
     *
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException
     *
     * @return Route
     */
    public function requestRoute(Request $request): Route
    {
        // Decode the request uri
        $requestUri = rawurldecode($request->getPathOnly());
        // Try to match the route
        $route = $this->matchRoute($requestUri, $request->getMethod());

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
    public function matchRoute(string $path, string $method = null): ?Route
    {
        return self::$collection->matcher()->match($path, $method);
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
            . request()->getHttpHost();
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
     * Determine if a uri is valid.
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
        if ($host && $host !== $this->app->request()->getHttpHost()) {
            // Return false immediately
            return false;
        }

        // Get only the path (full string from the first slash to the end
        // of the path)
        $uri = (string) substr($uri, strpos($uri, '/'), count($uri));

        // Try to match the route
        $route = $this->matchRoute($uri);

        return $route instanceof Route;
    }

    /**
     * Get the route collection.
     *
     * @return RouteCollection
     */
    abstract public function collection(): RouteCollection;

    /**
     * Get the route matcher.
     *
     * @return RouteMatcher
     */
    abstract public function matcher(): RouteMatcher;
}
