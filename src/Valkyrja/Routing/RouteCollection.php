<?php

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
     * @var \Valkyrja\Routing\Route[]
     */
    protected $routes = [];

    /**
     * The static routes.
     *
     * @var string[]
     */
    protected $staticRoutes = [];

    /**
     * The dynamic routes.
     *
     * @var string[]
     */
    protected $dynamicRoutes = [];

    /**
     * The named routes.
     *
     * @var string[]
     */
    protected $namedRoutes = [];

    /**
     * Add a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function addRoute(Route $route): void
    {
        $this->routes[$route->getPath()] = $route;

        // If this is a dynamic route
        if ($route->isDynamic()) {
            // Set the route's regex and path in the dynamic routes list
            $this->dynamicRoutes[$route->getRegex()] = $route->getPath();
        } // Otherwise set it in the static routes array
        else {
            // Set the route's path in the static routes list
            $this->staticRoutes[$route->getPath()] = true;
        }

        // If this route has a name set
        if ($route->getName()) {
            // Set the route in the named routes list
            $this->namedRoutes[$route->getName()] = $route->getPath();
        }
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
    public function getRoute(string $path): ? Route
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
    public function issetRoute(string $path): bool
    {
        return isset($this->routes[$path]);
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
     * @param string $path The path
     *
     * @return null|Route
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function getStaticRoute(string $path): ? Route
    {
        return $this->getRoute($this->staticRoutes[$path] ?? $path);
    }

    /**
     * Determine if a static route exists.
     *
     * @param string $path The path
     *
     * @return bool
     */
    public function issetStaticRoute(string $path): bool
    {
        return isset($this->staticRoutes[$path]);
    }

    /**
     * @return \string[]
     */
    public function getStaticRoutes(): array
    {
        return $this->staticRoutes;
    }

    /**
     * Get a dynamic route.
     *
     * @param string $regex The regex
     *
     * @return null|Route
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function getDynamicRoute(string $regex): ? Route
    {
        return $this->getRoute($this->dynamicRoutes[$regex] ?? $regex);
    }

    /**
     * Determine if a dynamic route exists.
     *
     * @param string $regex The regex
     *
     * @return bool
     */
    public function issetDynamicRoute(string $regex): bool
    {
        return isset($this->dynamicRoutes[$regex]);
    }

    /**
     * Get the dynamic routes in this collection.
     *
     * @return string[]
     */
    public function getDynamicRoutes(): array
    {
        return $this->dynamicRoutes;
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
    public function getNamedRoute(string $name): ? Route
    {
        return $this->getRoute($this->namedRoutes[$name] ?? $name);
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
}
