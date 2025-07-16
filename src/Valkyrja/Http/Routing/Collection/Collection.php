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

namespace Valkyrja\Http\Routing\Collection;

use Override;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\Contract\Collection as Contract;
use Valkyrja\Http\Routing\Data;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Exception\InvalidArgumentException;

use function array_map;
use function is_string;

/**
 * Class Collection.
 *
 * @author Melech Mizrachi
 *
 * @phpstan-import-type RequestMethodList from Contract
 * @phpstan-import-type RequestMethodRouteList from Contract
 *
 * @psalm-import-type RequestMethodList from Contract
 * @psalm-import-type RequestMethodRouteList from Contract
 */
class Collection implements Contract
{
    /**
     * The routes.
     *
     * @var array<string, Route|string>
     */
    protected array $routes = [];

    /**
     * The static routes.
     *
     * @var RequestMethodList
     */
    protected array $static = [];

    /**
     * The dynamic routes.
     *
     * @var RequestMethodList
     */
    protected array $dynamic = [];

    /**
     * @inheritDoc
     */
    #[Override]
    public function getData(): Data
    {
        return new Data(
            routes: array_map(
                static fn (Route|string $route): string => ! is_string($route)
                    ? serialize($route)
                    : $route,
                $this->routes
            ),
            static: $this->static,
            dynamic: $this->dynamic,
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setFromData(Data $data): void
    {
        $this->routes  = $data->routes;
        $this->static  = $data->static;
        $this->dynamic = $data->dynamic;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function add(Route $route): void
    {
        // Set the route to its request methods
        $this->setRouteToRequestMethods($route);

        $this->routes[$route->getName()] = $route;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string $path, RequestMethod|null $method = null): Route|null
    {
        return $this->getStatic($path, $method)
            ?? $this->getDynamic($path, $method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string $path, RequestMethod|null $method = null): bool
    {
        return $this->hasStatic($path, $method)
            || $this->hasDynamic($path, $method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function all(): array
    {
        return $this->ensureMethodRoutes(array_merge_recursive($this->static, $this->dynamic));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function allFlattened(): array
    {
        return $this->ensureRoutes($this->routes);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getStatic(string $path, RequestMethod|null $method = null): Route|null
    {
        return $this->getOfType($this->static, $path, $method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasStatic(string $path, RequestMethod|null $method = null): bool
    {
        return $this->hasOfType($this->static, $path, $method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function allStatic(RequestMethod|null $method = null): array
    {
        return $this->allOfType($this->static, $method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDynamic(string $regex, RequestMethod|null $method = null): Route|null
    {
        return $this->getOfType($this->dynamic, $regex, $method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasDynamic(string $regex, RequestMethod|null $method = null): bool
    {
        return $this->hasOfType($this->dynamic, $regex, $method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function allDynamic(RequestMethod|null $method = null): array
    {
        return $this->allOfType($this->dynamic, $method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getByName(string $name): Route|null
    {
        $named = $this->routes[$name] ?? null;

        if ($named === null) {
            return null;
        }

        return $this->ensureRoute($named);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasNamed(string $name): bool
    {
        return isset($this->routes[$name]);
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
        foreach ($route->getRequestMethods() as $requestMethod) {
            $this->setRouteToRequestMethod($route, $requestMethod);
        }
    }

    /**
     * Set the route to the request method.
     *
     * @param Route         $route         The route
     * @param RequestMethod $requestMethod The request method
     *
     * @return void
     */
    protected function setRouteToRequestMethod(Route $route, RequestMethod $requestMethod): void
    {
        $name  = $route->getName();
        $regex = $route->getRegex();

        // If this is a dynamic route
        if ($regex !== null) {
            // Set the route in the dynamic routes list
            $this->dynamic[$requestMethod->value][$regex] = $name;
        } // Otherwise set it in the static routes array
        else {
            // Set the route in the static routes list
            $this->static[$requestMethod->value][$route->getPath()] = $name;
        }
    }

    /**
     * Get a route of type (static|dynamic).
     *
     * @param RequestMethodList  $type   The type [static|dynamic]
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return Route|null
     */
    protected function getOfType(array $type, string $path, RequestMethod|null $method = null): Route|null
    {
        if ($method === null) {
            return $this->getAnyOfType($type, $path);
        }

        $route = $type[$method->value][$path] ?? null;

        if ($route === null) {
            return null;
        }

        return $this->ensureRoute($route);
    }

    /**
     * Get a route of any type (static|dynamic).
     *
     * @param RequestMethodList $type The type [static|dynamic]
     * @param string            $path The path
     *
     * @return Route|null
     */
    protected function getAnyOfType(array $type, string $path): Route|null
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
     * @param RequestMethodList  $type   The type [static|dynamic]
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return bool
     */
    protected function hasOfType(array $type, string $path, RequestMethod|null $method = null): bool
    {
        if ($method === null) {
            return $this->hasAnyOfType($type, $path);
        }

        return isset($type[$method->value][$path]);
    }

    /**
     * Has a path of any type.
     *
     * @param RequestMethodList $type The type [static|dynamic]
     * @param string            $path The path
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
     * @param RequestMethodList  $type   The type [static|dynamic]
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return RequestMethodRouteList|array<string, Route>
     */
    protected function allOfType(array $type, RequestMethod|null $method = null): array
    {
        if ($method === null) {
            return $this->ensureMethodRoutes($type);
        }

        return $this->ensureRoutes($type[$method->value] ?? []);
    }

    /**
     * Ensure request methods are arrays of routes.
     *
     * @param RequestMethodList $methodsArray
     *
     * @return RequestMethodRouteList
     */
    protected function ensureMethodRoutes(array $methodsArray): array
    {
        return array_map(
            [$this, 'ensureRoutes'],
            $methodsArray
        );
    }

    /**
     * Ensure an array is an array of routes.
     *
     * @param array<string, string|Route> $routesArray The routes array
     *
     * @return array<string, Route>
     */
    protected function ensureRoutes(array $routesArray): array
    {
        return array_map(
            [$this, 'ensureRoute'],
            $routesArray
        );
    }

    /**
     * Ensure a route, or null, is returned.
     *
     * @param Route|string $route The route
     *
     * @return Route
     */
    protected function ensureRoute(Route|string $route): Route
    {
        if (is_string($route) && isset($this->routes[$route])) {
            $route = $this->routes[$route];
        }

        if (is_string($route)) {
            $unserializedRoute = unserialize($route, ['allowed_classes' => true]);

            if (! $unserializedRoute instanceof Route) {
                throw new InvalidArgumentException('Invalid object serialized.');
            }

            return $unserializedRoute;
        }

        return $route;
    }
}
