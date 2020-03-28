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

namespace Valkyrja\Routing\Collections;

use InvalidArgumentException;
use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Routing\Route;

use function is_array;
use function Valkyrja\app;

/**
 * Trait CollectionHelpers.
 *
 * @author Melech Mizrachi
 */
trait CollectionHelpers
{
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
     * Get a route of type (static|dynamic).
     *
     * @param array       $type   The type [static|dynamic]
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route|null
     */
    protected function getOfType(array $type, string $path, string $method = null): ?Route
    {
        if (null === $method) {
            return $this->getAnyOfType($type, $path);
        }

        return $this->ensureRoute($type[$method][$path] ?? null);
    }

    /**
     * Get a route of any type (static|dynamic).
     *
     * @param array  $type The type [static|dynamic]
     * @param string $path The path
     *
     * @return Route|null
     */
    protected function getAnyOfType(array $type, string $path): ?Route
    {
        return $this->getOfType($type, $path, RequestMethod::GET)
            ?? $this->getOfType($type, $path, RequestMethod::HEAD)
            ?? $this->getOfType($type, $path, RequestMethod::POST)
            ?? $this->getOfType($type, $path, RequestMethod::PUT)
            ?? $this->getOfType($type, $path, RequestMethod::PATCH)
            ?? $this->getOfType($type, $path, RequestMethod::DELETE);
    }

    /**
     * Has a path of type (static|dynamic).
     *
     * @param array       $type   The type [static|dynamic]
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return bool
     */
    protected function hasOfType(array $type, string $path, string $method = null): bool
    {
        if (null === $method) {
            return $this->hasAnyOfType($type, $path);
        }

        return isset($type[$method][$path]);
    }

    /**
     * Has a path of any type.
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

    /**
     * Get all of type with optional by request method.
     *
     * @param array       $type   The type [static|dynamic]
     * @param string|null $method [optional] The request method
     *
     * @return array
     */
    protected function allOfType(array $type, string $method = null): array
    {
        if ($method === null) {
            return $this->ensureMethodRoutes($type);
        }

        return $this->ensureRoutes($type[$method] ?? []);
    }

    /**
     * Ensure request methods are arrays of routes.
     *
     * @param array $methodsArray
     *
     * @return array
     */
    protected function ensureMethodRoutes(array $methodsArray): array
    {
        $methods = [];

        foreach ($methodsArray as $key => $method) {
            $methodsArray[$key] = $this->ensureRoutes($method);
        }

        return $methods;
    }

    /**
     * Ensure an array is an array of routes.
     *
     * @param array $routesArray The routes array
     *
     * @return array
     */
    protected function ensureRoutes(array $routesArray): array
    {
        $routes = [];

        foreach ($routesArray as $key => $route) {
            $routes[$key] = $this->ensureRoute($route);
        }

        return $routes;
    }

    /**
     * Ensure a route, or null, is returned.
     *
     * @param Route|array|null $route The route
     *
     * @return Route|null
     */
    protected function ensureRoute($route = null): ?Route
    {
        if (is_array($route)) {
            return \Valkyrja\Routing\Models\Route::fromArray($route);
        }

        return $route;
    }
}
