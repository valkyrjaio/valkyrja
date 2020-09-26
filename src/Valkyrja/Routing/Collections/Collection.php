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
use JsonException;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Routing\Collection as Contract;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Route;
use Valkyrja\Support\Type\Arr;

use function array_merge;
use function is_array;
use function md5;

/**
 * Class Collection.
 *
 * @author Melech Mizrachi
 */
class Collection implements Contract
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
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The dispatcher.
     *
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * The route matcher.
     *
     * @var Matcher
     */
    protected Matcher $matcher;

    /**
     * Collection constructor.
     *
     * @param Container  $container
     * @param Dispatcher $dispatcher
     * @param Matcher    $matcher
     */
    public function __construct(Container $container, Dispatcher $dispatcher, Matcher $matcher)
    {
        $matcher->setCollection($this);

        $this->container  = $container;
        $this->dispatcher = $dispatcher;
        $this->matcher    = $matcher;
    }

    /**
     * Add a route.
     *
     * @param Route $route The route
     *
     * @throws JsonException
     *
     * @return void
     */
    public function add(Route $route): void
    {
        // Verify the route
        $this->verifyRoute($route);
        // Verify the dispatch
        $this->dispatcher->verifyDispatch($route);

        // Set the path to the validated cleaned path (/some/path)
        $route->setPath($this->matcher->trimPath($route->getPath() ?? ''));
        // Set the route to its request methods
        $this->setRouteToRequestMethods($route);
        // Set the route to the named
        $this->setRouteToNamed($route);

        $this->routes[md5(Arr::toString($route->toArray()))] = $route;
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
        return $this->getStatic($path, $method) ?? $this->getDynamic($path, $method);
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
     * @return Route[][]
     */
    public function all(): array
    {
        return $this->ensureMethodRoutes(array_merge($this->static, $this->dynamic));
    }

    /**
     * Get a flat array of routes.
     *
     * @return Route[]
     */
    public function allFlattened(): array
    {
        return $this->ensureRoutes($this->routes);
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
        return $this->getOfType($this->static, $path, $method);
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
        return $this->hasOfType($this->static, $path, $method);
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
        return $this->allOfType($this->static, $method);
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
        return $this->getOfType($this->dynamic, $regex, $method);
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
        return $this->hasOfType($this->dynamic, $regex, $method);
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
        return $this->allOfType($this->dynamic, $method);
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
        return $this->ensureRoute($this->named[$name] ?? null);
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
        return $this->ensureRoutes($this->named);
    }

    /**
     * Get the route matcher.
     *
     * @return Matcher
     */
    public function matcher(): Matcher
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
        foreach ($methodsArray as $key => $method) {
            $methodsArray[$key] = $this->ensureRoutes($method);
        }

        return $methodsArray;
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
