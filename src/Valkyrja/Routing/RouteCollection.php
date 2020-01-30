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

namespace Valkyrja\Routing;

/**
 * Class RouteCollection.
 *
 * @author Melech Mizrachi
 */
class RouteCollection
{
    /**
     * The routes.
     *
     * @var Route[]
     */
    protected array $routes = [];

    /**
     * The static routes.
     *
     * @var string[][]
     */
    protected array $staticRoutes = [];

    /**
     * The dynamic routes.
     *
     * @var string[][]
     */
    protected array $dynamicRoutes = [];

    /**
     * The named routes.
     *
     * @var string[]
     */
    protected array $namedRoutes = [];

    /**
     * Add a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function addRoute(Route $route): void
    {
        $key                = md5(json_encode((array) $route, JSON_THROW_ON_ERROR));
        $this->routes[$key] = $route;

        foreach ($route->getRequestMethods() as $requestMethod) {
            // If this is a dynamic route
            if ($route->isDynamic()) {
                // Set the route's regex and path in the dynamic routes list
                $this->dynamicRoutes[$requestMethod][$route->getRegex()] = $key;
            } // Otherwise set it in the static routes array
            else {
                // Set the route's path in the static routes list
                $this->staticRoutes[$requestMethod][$route->getPath()] = $key;
            }
        }

        // If this route has a name set
        if ($route->getName()) {
            // Set the route in the named routes list
            $this->namedRoutes[$route->getName()] = $key;
        }
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get a static route.
     *
     * @param string $method The request method
     * @param string $path   The path
     *
     * @return null|Route
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function staticRoute(string $method, string $path): ?Route
    {
        return $this->route($this->staticRoutes[$method][$path] ?? $path);
    }

    /**
     * Determine if a static route exists.
     *
     * @param string $method The request method
     * @param string $path   The path
     *
     * @return bool
     */
    public function issetStaticRoute(string $method, string $path): bool
    {
        return isset($this->staticRoutes[$method][$path]);
    }

    /**
     * Get static routes of a certain request method.
     *
     * @param string $method The request method
     *
     * @return string[]
     */
    public function getStaticRoutes(string $method): array
    {
        return $this->staticRoutes[$method];
    }

    /**
     * Get a dynamic route.
     *
     * @param string $method The request method
     * @param string $regex  The regex
     *
     * @return null|Route
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function dynamicRoute(string $method, string $regex): ?Route
    {
        return $this->route($this->dynamicRoutes[$method][$regex] ?? $regex);
    }

    /**
     * Determine if a dynamic route exists.
     *
     * @param string $method The request method
     * @param string $regex  The regex
     *
     * @return bool
     */
    public function issetDynamicRoute(string $method, string $regex): bool
    {
        return isset($this->dynamicRoutes[$method][$regex]);
    }

    /**
     * Get the dynamic routes in this collection.
     *
     * @param string $method
     *
     * @return string[]
     */
    public function getDynamicRoutes(string $method): array
    {
        return $this->dynamicRoutes[$method] ?? [];
    }

    /**
     * Get a named route.
     *
     * @param string $name The name
     *
     * @return null|Route
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function namedRoute(string $name): ?Route
    {
        return $this->route($this->namedRoutes[$name] ?? $name);
    }

    /**
     * Determine if a named route exists.
     *
     * @param string $name The name
     *
     * @return bool
     */
    public function issetNamedRoute(string $name): bool
    {
        return isset($this->namedRoutes[$name]);
    }

    /**
     * Get the named routes in this collection.
     *
     * @return string[]
     */
    public function getNamedRoutes(): array
    {
        return $this->namedRoutes;
    }

    /**
     * Get a route.
     *
     * @param string $path The path
     *
     * @return null|Route
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    protected function route(string $path): ?Route
    {
        return $this->routes[$path] ?? null;
    }

    /**
     * Determine if a route exists.
     *
     * @param string $path The path
     *
     * @return bool
     */
    protected function issetRoute(string $path): bool
    {
        return isset($this->routes[$path]);
    }
}
