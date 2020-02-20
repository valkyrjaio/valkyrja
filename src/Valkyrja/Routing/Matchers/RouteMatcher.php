<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Matchers;

use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\RouteCollection;
use Valkyrja\Routing\RouteMatcher as RouteMatcherContract;

/**
 * Class RouteMatcher.
 *
 * @author Melech Mizrachi
 */
class RouteMatcher implements RouteMatcherContract
{
    /**
     * The route collection.
     *
     * @var RouteCollection
     */
    protected RouteCollection $collection;

    /**
     * RouteMatcher constructor.
     *
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->collection = $routeCollection;
    }

    /**
     * Trim a path.
     *
     * @param string $path The path
     *
     * @return string
     */
    public function trimPath(string $path): string
    {
        return '/' . trim($path, '/');
    }

    /**
     * Match a route by path.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *      The route if found or null when no route is
     *      found for the path and method combination specified
     */
    public function match(string $path, string $method = null): ?Route
    {
        $path   = $this->trimPath($path);
        $method = $method ?? RequestMethod::GET;

        if (null !== $route = $this->matchStatic($path, $method)) {
            return $route;
        }

        return $this->matchDynamic($path, $method);
    }

    /**
     * Match a dynamic route by path.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function matchStatic(string $path, string $method = null): ?Route
    {
        $route = null;

        // Let's check if the route is set in the static routes
        if ($this->collection->issetStatic($path, $method)) {
            $route = $this->getMatchedStaticRoute($path, $method);
        }

        if (null !== $route && (null === $method || $this->isValidMethod($route, $method))) {
            return $route;
        }

        return null;
    }

    /**
     * Match a static route by path.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *      The route if found or null when no dynamic route is
     *      found for the path and method combination specified
     */
    public function matchDynamic(string $path, string $method = null): ?Route
    {
        // The route to return (null by default)
        $route = null;

        // The dynamic routes
        // Attempt to find a match using dynamic routes that are set
        foreach ($this->collection->allDynamic($method) as $regex => $dynamicRoute) {
            // If the preg match is successful, we've found our route!
            /* @var array $matches */
            if (preg_match($regex, $path, $matches)) {
                $route = $this->getMatchedDynamicRoute($dynamicRoute, $matches, $method);

                break;
            }
        }

        // If the route was found and the method is valid
        if (null !== $route && (null === $method || $this->isValidMethod($route, $method))) {
            // Return the route
            return $route;
        }

        return null;
    }

    /**
     * @param Route  $route  The route
     * @param string $method The method
     *
     * @return bool
     */
    protected function isValidMethod(Route $route, string $method): bool
    {
        return in_array($method, $route->getRequestMethods(), true);
    }

    /**
     * Get a matched static route.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route
     */
    protected function getMatchedStaticRoute(string $path, string $method = null): Route
    {
        return clone $this->collection->getStatic($path, $method);
    }

    /**
     * Get a matched dynamic route.
     *
     * @param string      $path    The path
     * @param array       $matches The regex matches
     * @param string|null $method  [optional] The request method
     *
     * @return Route
     */
    protected function getMatchedDynamicRoute(string $path, array $matches, string $method = null): Route
    {
        // Clone the route to avoid changing the one set in the master array
        $dynamicRoute = clone $this->collection->getDynamic($path, $method);
        // The first match is the path itself
        unset($matches[0]);

        // Iterate through the matches
        foreach ($matches as $key => $match) {
            // If there is no match (middle of regex optional group)
            if (! $match) {
                // Set the value to null so the controller's action
                // can use the default it sets
                $matches[$key] = null;
            }
        }

        // Set the matches
        $dynamicRoute->setMatches($matches);

        return $dynamicRoute;
    }
}
