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

namespace Valkyrja\Routing\Matchers;

use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Matcher as RouteMatcherContract;
use Valkyrja\Routing\Route;

use function preg_match;
use function trim;

/**
 * Class RouteMatcher.
 *
 * @author Melech Mizrachi
 */
class Matcher implements RouteMatcherContract
{
    /**
     * The route collection.
     *
     * @var Collection
     */
    protected Collection $collection;

    /**
     * Set the collection.
     *
     * @param Collection $collection The collection
     *
     * @return void
     */
    public function setCollection(Collection $collection): void
    {
        $this->collection = $collection;
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
     * @param string $path   The path
     * @param string $method The request method
     *
     * @return Route|null
     *      The route if found or null when no route is
     *      found for the path and method combination specified
     */
    public function match(string $path, string $method): ?Route
    {
        $path = $this->trimPath($path);

        if (null !== $route = $this->matchStatic($path, $method)) {
            return $route;
        }

        return $this->matchDynamic($path, $method);
    }

    /**
     * Match a dynamic route by path.
     *
     * @param string $path   The path
     * @param string $method The request method
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function matchStatic(string $path, string $method): ?Route
    {
        // Let's check if the route is set in the static routes
        if ($this->collection->hasStatic($path, $method)) {
            return $this->getMatchedStaticRoute($path, $method);
        }

        return null;
    }

    /**
     * Match a static route by path.
     *
     * @param string $path   The path
     * @param string $method The request method
     *
     * @return Route|null
     *      The route if found or null when no dynamic route is
     *      found for the path and method combination specified
     */
    public function matchDynamic(string $path, string $method): ?Route
    {
        // Attempt to find a match using dynamic routes that are set
        foreach ($this->collection->allDynamic($method) as $regex => $dynamicRoute) {
            // If the preg match is successful, we've found our route!
            /* @var array $matches */
            if (preg_match($regex, $path, $matches)) {
                return $this->getMatchedDynamicRoute($regex, $matches, $method);
            }
        }

        return null;
    }

    /**
     * Get a matched static route.
     *
     * @param string $path   The path
     * @param string $method The request method
     *
     * @return Route
     */
    protected function getMatchedStaticRoute(string $path, string $method): Route
    {
        return clone $this->collection->getStatic($path, $method);
    }

    /**
     * Get a matched dynamic route.
     *
     * @param string $path    The path
     * @param array  $matches The regex matches
     * @param string $method  The request method
     *
     * @return Route
     */
    protected function getMatchedDynamicRoute(string $path, array $matches, string $method): Route
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
