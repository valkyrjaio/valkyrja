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

use InvalidArgumentException;
use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Routing\Collection as RouteCollectionContract;
use Valkyrja\Routing\Matcher as RouteMatcherContract;
use Valkyrja\Routing\Matchers\Matcher;
use Valkyrja\Routing\Route;

/**
 * Class RouteCollection.
 *
 * @author Melech Mizrachi
 */
class Collection implements RouteCollectionContract
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
     * @var Route[][]
     */
    protected array $static = [];

    /**
     * The dynamic routes.
     *
     * @var Route[][]
     */
    protected array $dynamic = [];

    /**
     * The named routes.
     *
     * @var Route[]
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
     */
    public function __construct()
    {
        $this->matcher = new Matcher($this);
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
        // Verify the route
        $this->verifyRoute($route);
        // Verify the dispatch
        app()->dispatcher()->verifyDispatch($route);

        // Set the path to the validated cleaned path (/some/path)
        $route->setPath($this->matcher->trimPath($route->getPath()));
        // Set the route to its request methods
        $this->setRouteToRequestMethods($route);
        // Set the route to the named
        $this->setRouteToNamed($route);

        $this->routes[] = $route;
    }

    /**
     * Get a route.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     *      The route if found or null when no route is
     *      found for the path combination specified
     */
    public function get(string $path, string $method = null): ?Route
    {
        return $this->getStatic($path, $method) ?? $this->getDynamic($path, $method) ?? null;
    }

    /**
     * Determine if a route exists.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return bool
     */
    public function isset(string $path, string $method = null): bool
    {
        return $this->hasStatic($path, $method) || $this->hasDynamic($path, $method);
    }

    /**
     * Get all routes.
     *
     * @return Route[]
     */
    public function all(): array
    {
        return array_merge($this->static, $this->dynamic);
    }

    /**
     * Get a flat array of routes.
     *
     * @return Route[]
     */
    public function allFlattened(): array
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
        if (null === $method) {
            return $this->getStatic($path, RequestMethod::GET)
                ?? $this->getStatic($path, RequestMethod::HEAD)
                ?? $this->getStatic($path, RequestMethod::POST)
                ?? $this->getStatic($path, RequestMethod::PUT)
                ?? $this->getStatic($path, RequestMethod::PATCH)
                ?? $this->getStatic($path, RequestMethod::DELETE);
        }

        return $this->static[$method][$path] ?? null;
    }

    /**
     * Determine if a static route exists.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return bool
     */
    public function hasStatic(string $path, string $method = null): bool
    {
        if (null === $method) {
            return $this->hasAnyStatic($path);
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
        if (null === $method) {
            return $this->getDynamic($regex, RequestMethod::GET)
                ?? $this->getDynamic($regex, RequestMethod::HEAD)
                ?? $this->getDynamic($regex, RequestMethod::POST)
                ?? $this->getDynamic($regex, RequestMethod::PUT)
                ?? $this->getDynamic($regex, RequestMethod::PATCH)
                ?? $this->getDynamic($regex, RequestMethod::DELETE);
        }

        return $this->dynamic[$method][$regex] ?? null;
    }

    /**
     * Determine if a dynamic route exists.
     *
     * @param string      $regex  The regex
     * @param string|null $method [optional] The request method
     *
     * @return bool
     */
    public function hasDynamic(string $regex, string $method = null): bool
    {
        if (null === $method) {
            return $this->hasAnyDynamic($regex);
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
        return $this->named[$name] ?? null;
    }

    /**
     * Determine if a named route exists.
     *
     * @param string $name The name
     *
     * @return bool
     */
    public function hasNamed(string $name): bool
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
     * Verify a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function verifyRoute(Route $route): void
    {
        if (! $route->getPath()) {
            throw new InvalidArgumentException('Invalid path defined in route.');
        }
    }

    /**
     * Set a route to its request methods.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setRouteToRequestMethods(Route $route): void
    {
        foreach ($route->getMethods() as $requestMethod) {
            $this->setRouteToRequestMethod($route, $requestMethod);
        }
    }

    /**
     * Set the route to the request method.
     *
     * @param Route  $route         The route
     * @param string $requestMethod The request method
     *
     * @return void
     */
    protected function setRouteToRequestMethod(Route $route, string $requestMethod): void
    {
        // If this is a dynamic route
        if ($route->isDynamic()) {
            // Set the dynamic route's properties through the path parser
            $this->parseDynamicRoute($route);
            // Set the route in the dynamic routes list
            $this->dynamic[$requestMethod][$route->getRegex()] = $route;
        } // Otherwise set it in the static routes array
        else {
            // Set the route in the static routes list
            $this->static[$requestMethod][$route->getPath()] = $route;
        }
    }

    /**
     * Set the route to the named.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setRouteToNamed(Route $route): void
    {
        // If this route has a name set
        if ($route->getName()) {
            // Set the route in the named routes list
            $this->named[$route->getName()] = $route;
        }
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
        $this->verifyRoute($route);

        // Parse the path
        $parsedRoute = app()->pathParser()->parse($route->getPath());

        // Set the properties
        $route->setRegex($parsedRoute['regex']);
        $route->setParams($parsedRoute['params']);
        $route->setSegments($parsedRoute['segments']);
    }

    /**
     * Has any static (no request method filtering).
     *
     * @param string $path The path
     *
     * @return bool
     */
    protected function hasAnyStatic(string $path): bool
    {
        return $this->hasAnyOfType($this->static, $path);
    }

    /**
     * Has any dynamic (no request method filtering).
     *
     * @param string $regex The regex
     *
     * @return bool
     */
    protected function hasAnyDynamic(string $regex): bool
    {
        return $this->hasAnyOfType($this->dynamic, $regex);
    }

    /**
     * Has a path in the type of routing.
     *
     * @param array  $type The type [static|dynamic]
     * @param string $path The path
     *
     * @return bool
     */
    protected function hasAnyOfType(array $type, string $path): bool
    {
        foreach ($type as $requestMethod) {
            if (isset($requestMethod[$path])) {
                return true;
            }
        }

        return false;
    }
}
