<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Routing;

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\Request;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Http\RequestMethod;
use Valkyrja\Routing\Models\Route;

/**
 * Interface Router
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Router
{
    /**
     * The variable regex.
     *
     * @constant string
     */
    public const VARIABLE_REGEX = <<<'REGEX'
\{
    \s* ([a-zA-Z_][a-zA-Z0-9_-]*) \s*
    (?:
        : \s* ([^{}]*(?:\{(?-1)\}[^{}]*)*)
    )?
\}
REGEX;

    /**
     * Router constructor.
     *
     * @param \Valkyrja\Contracts\Application $application
     */
    public function __construct(Application $application);

    /**
     * Set a single route.
     *
     * @param \Valkyrja\Routing\Models\Route $route
     *
     * @return void
     *
     * @throws \Valkyrja\Http\Exceptions\NonExistentActionException
     */
    public function addRoute(Route $route): void;

    /**
     * Helper function to set a GET addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     */
    public function get(Route $route): void;

    /**
     * Helper function to set a POST addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     */
    public function post(Route $route): void;

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     */
    public function put(Route $route): void;

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     */
    public function patch(Route $route): void;

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     */
    public function delete(Route $route): void;

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param \Valkyrja\Routing\Models\Route $route The route
     *
     * @return void
     */
    public function head(Route $route): void;

    /**
     * Set routes from a given array of routes.
     *
     * @param array $routes The routes to set
     *
     * @return void
     */
    public function setRoutes(array $routes): void;

    /**
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes(): array;

    /**
     * Get a route by path.
     *
     * @param string $path   The path
     * @param string $method [optional] The method type of get
     *
     * @return array
     */
    public function getRouteByPath(string $path, string $method = RequestMethod::GET): array;

    /**
     * Get a route from a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return array
     */
    public function getRouteFromRequest(Request $request): array;

    /**
     * Get a route by name.
     *
     * @param string $name   The name of the route to get
     * @param string $method [optional] The method type of get
     * @param string $type   [optional] The type of routes (static/dynamic)
     *
     * @return array
     */
    public function getRouteByName(string $name, string $method = RequestMethod::GET, string $type = 'static'): array;

    /**
     * Get a route url by name.
     *
     * @param string $name   The name of the route to get
     * @param string $method [optional] The method type of get
     * @param array  $data   [optional] The route data if dynamic
     *
     * @return string
     */
    public function getRouteUrlByName(string $name, array $data = [], string $method = RequestMethod::GET): string;

    /**
     * Setup routes.
     *
     * @return void
     */
    public function setupRoutes(): void;

    /**
     * Dispatch the route and find a match.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function dispatch(Request $request): Response;
}
