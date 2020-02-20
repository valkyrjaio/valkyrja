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

use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Support\Cacheable;

/**
 * Interface Router.
 *
 * @author Melech Mizrachi
 */
interface Router extends Cacheable
{
    /**
     * Set a single route.
     *
     * @param Route $route
     *
     * @return void
     */
    public function addRoute(Route $route): void;

    /**
     * Helper function to set a GET addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function get(Route $route): void;

    /**
     * Helper function to set a POST addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function post(Route $route): void;

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function put(Route $route): void;

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function patch(Route $route): void;

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function delete(Route $route): void;

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function head(Route $route): void;

    /**
     * Helper function to set any request method addRoute.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function any(Route $route): void;

    /**
     * Get all routes set by the application.
     *
     * @return Route[]
     */
    public function getRoutes(): array;

    /**
     * Get the route collection.
     *
     * @return RouteCollection
     */
    public function collection(): RouteCollection;

    /**
     * Get the route matcher.
     *
     * @return RouteMatcher
     */
    public function matcher(): RouteMatcher;

    /**
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @return Route
     */
    public function route(string $name): Route;

    /**
     * Determine whether a route name exists.
     *
     * @param string $name The name of the route
     *
     * @return bool
     */
    public function routeIsset(string $name): bool;

    /**
     * Get a route url by name.
     *
     * @param string $name     The name of the route to get
     * @param array  $data     [optional] The route data if dynamic
     * @param bool   $absolute [optional] Whether this url should be absolute
     *
     * @return string
     */
    public function routeUrl(string $name, array $data = null, bool $absolute = null): string;

    /**
     * Get a route from a request.
     *
     * @param Request $request The request
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function requestRoute(Request $request): ?Route;

    /**
     * Get a route by path.
     *
     * @param string $path   The path
     * @param string $method [optional] The method type of get
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function matchRoute(string $path, string $method = null): ?Route;

    /**
     * Determine if a uri is valid.
     *
     * @param string $uri The uri to check
     *
     * @return bool
     */
    public function isInternalUri(string $uri): bool;

    /**
     * Dispatch the route and find a match.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function dispatch(Request $request): Response;
}
