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

namespace Valkyrja\Routing\Collections;

use Valkyrja\Application\Application;
use Valkyrja\Routing\Matchers\RouteMatcher;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\RouteCollection as RouteCollectionContract;
use Valkyrja\Routing\RouteMatcher as RouteMatcherContract;

use const JSON_THROW_ON_ERROR;

/**
 * Class RouteCollection.
 *
 * @author Melech Mizrachi
 */
class RouteCollection implements RouteCollectionContract
{
    /**
     * Application.
     *
     * @var Application
     */
    protected Application $app;

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
    protected array $static = [];

    /**
     * The dynamic routes.
     *
     * @var string[][]
     */
    protected array $dynamic = [];

    /**
     * The named routes.
     *
     * @var string[]
     */
    protected array $named = [];

    /**
     * The route matcher.
     *
     * @var RouteMatcherContract
     */
    protected RouteMatcherContract $matcher;

    /**
     * RouteCollection constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app     = $app;
        $this->matcher = new RouteMatcher($this);
    }

    /**
     * Add a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function add(Route $route): void
    {
        $key                = md5(json_encode((array) $route, JSON_THROW_ON_ERROR));
        $this->routes[$key] = $route;

        // Verify the dispatch
        $this->app->dispatcher()->verifyDispatch($route);

        // Set the path to the validated cleaned path (/some/path)
        $route->setPath($this->matcher->trimPath($route->getPath()));

        foreach ($route->getRequestMethods() as $requestMethod) {
            // If this is a dynamic route
            if ($route->isDynamic()) {
                // Set the dynamic route's properties through the path parser
                $this->parseDynamicRoute($route);
                // Set the route's regex and path in the dynamic routes list
                $this->dynamic[$requestMethod][$route->getRegex()] = $key;
            } // Otherwise set it in the static routes array
            else {
                // Set the route's path in the static routes list
                $this->static[$requestMethod][$route->getPath()] = $key;
            }
        }

        // If this route has a name set
        if ($route->getName()) {
            // Set the route in the named routes list
            $this->named[$route->getName()] = $key;
        }
    }

    /**
     * Get a route.
     *
     * @param string $path The path
     *
     * @return Route|null
     *      The route if found or null when no route is
     *      found for the path combination specified
     */
    public function get(string $path): ?Route
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
    public function isset(string $path): bool
    {
        return isset($this->routes[$path]);
    }

    /**
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }

    /**
     * Get a static route.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function getStatic(string $path, string $method = null): ?Route
    {
        if (null !== $method) {
            return $this->get($path);
        }

        $methodPath = $this->static[$method][$path] ?? null;

        return $methodPath ? $this->get($methodPath) : null;
    }

    /**
     * Determine if a static route exists.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return bool
     */
    public function issetStatic(string $path, string $method = null): bool
    {
        if (null !== $method) {
            return $this->isset($path);
        }

        return isset($this->static[$method][$path]);
    }

    /**
     * Get static routes of a certain request method.
     *
     * @param string|null $method [optional] The request method
     *
     * @return string[]|string[][]
     */
    public function allStatic(string $method = null): array
    {
        if ($method === null) {
            return $this->static;
        }

        return $this->static[$method];
    }

    /**
     * Get a dynamic route.
     *
     * @param string      $regex  The regex
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *      The route if found or null when no dynamic route is
     *      found for the path and method combination specified
     */
    public function getDynamic(string $regex, string $method = null): ?Route
    {
        if (null !== $method) {
            return $this->get($regex);
        }

        $methodPath = $this->dynamic[$method][$regex] ?? null;

        return $methodPath ? $this->get($methodPath) : null;
    }

    /**
     * Determine if a dynamic route exists.
     *
     * @param string      $regex  The regex
     * @param string|null $method [optional] The request method
     *
     * @return bool
     */
    public function issetDynamic(string $regex, string $method = null): bool
    {
        if (null !== $method) {
            return $this->isset($regex);
        }

        return isset($this->dynamic[$method][$regex]);
    }

    /**
     * Get the dynamic routes in this collection.
     *
     * @param string|null $method [optional] The request method
     *
     * @return string[]|string[][]
     */
    public function allDynamic(string $method = null): array
    {
        if ($method === null) {
            return $this->dynamic;
        }

        return $this->dynamic[$method] ?? [];
    }

    /**
     * Get a named route.
     *
     * @param string $name The name
     *
     * @return Route|null
     *      The route if found or null when no named route is
     *      found for the path and method combination specified
     */
    public function getNamed(string $name): ?Route
    {
        return $this->get($this->named[$name] ?? $name);
    }

    /**
     * Determine if a named route exists.
     *
     * @param string $name The name
     *
     * @return bool
     */
    public function issetNamed(string $name): bool
    {
        return isset($this->named[$name]);
    }

    /**
     * Get the named routes in this collection.
     *
     * @return string[]
     */
    public function allNamed(): array
    {
        return $this->named;
    }

    /**
     * Get the route matcher.
     *
     * @return RouteMatcherContract
     */
    public function matcher(): RouteMatcherContract
    {
        return $this->matcher;
    }

    /**
     * Parse a dynamic route and set its properties.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function parseDynamicRoute(Route $route): void
    {
        // Parse the path
        $parsedRoute = $this->app->pathParser()->parse($route->getPath());

        // Set the properties
        $route->setRegex($parsedRoute['regex']);
        $route->setParams($parsedRoute['params']);
        $route->setSegments($parsedRoute['segments']);
    }
}
