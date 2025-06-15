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

use JsonException;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\Contract\Collection as Contract;
use Valkyrja\Http\Routing\Exception\InvalidArgumentException;
use Valkyrja\Http\Routing\Model\Contract\Route;

use function array_map;
use function array_merge;
use function assert;
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
     * The named routes.
     *
     * @var array<string, string>
     */
    protected array $named = [];

    /**
     * @inheritDoc
     */
    public function add(Route $route): void
    {
        assert($route->getPath());

        // Set the route to its request methods
        $this->setRouteToRequestMethods($route);
        // Set the route to the named
        $this->setRouteToNamed($route);

        $this->routes[(string) $route->getId()] = $route;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function get(string $path, RequestMethod|null $method = null): Route|null
    {
        return $this->getStatic($path, $method) ?? $this->getDynamic($path, $method);
    }

    /**
     * @inheritDoc
     */
    public function isset(string $path, RequestMethod|null $method = null): bool
    {
        return $this->hasStatic($path, $method) || $this->hasDynamic($path, $method);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->ensureMethodRoutes(array_merge($this->static, $this->dynamic));
    }

    /**
     * @inheritDoc
     */
    public function allFlattened(): array
    {
        return $this->ensureRoutes($this->routes);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getStatic(string $path, RequestMethod|null $method = null): Route|null
    {
        return $this->getOfType($this->static, $path, $method);
    }

    /**
     * @inheritDoc
     */
    public function hasStatic(string $path, RequestMethod|null $method = null): bool
    {
        return $this->hasOfType($this->static, $path, $method);
    }

    /**
     * @inheritDoc
     */
    public function allStatic(RequestMethod|null $method = null): array
    {
        return $this->allOfType($this->static, $method);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getDynamic(string $regex, RequestMethod|null $method = null): Route|null
    {
        return $this->getOfType($this->dynamic, $regex, $method);
    }

    /**
     * @inheritDoc
     */
    public function hasDynamic(string $regex, RequestMethod|null $method = null): bool
    {
        return $this->hasOfType($this->dynamic, $regex, $method);
    }

    /**
     * @inheritDoc
     */
    public function allDynamic(RequestMethod|null $method = null): array
    {
        return $this->allOfType($this->dynamic, $method);
    }

    /**
     * @inheritDoc
     */
    public function getNamed(string $name): Route|null
    {
        $named = $this->named[$name] ?? null;

        if ($named === null) {
            return null;
        }

        return $this->ensureRoute($named);
    }

    /**
     * @inheritDoc
     */
    public function hasNamed(string $name): bool
    {
        return isset($this->named[$name]);
    }

    /**
     * @inheritDoc
     */
    public function allNamed(): array
    {
        return $this->ensureRoutes($this->named);
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
     * @param Route         $route         The route
     * @param RequestMethod $requestMethod The request method
     *
     * @return void
     */
    protected function setRouteToRequestMethod(Route $route, RequestMethod $requestMethod): void
    {
        $id = $route->getId();

        assert($id !== null);

        // If this is a dynamic route
        if ($route->isDynamic()) {
            $regex = $route->getRegex();

            assert(is_string($regex));

            // Set the route in the dynamic routes list
            $this->dynamic[$requestMethod->value][$regex] = $id;
        } // Otherwise set it in the static routes array
        else {
            // Set the route in the static routes list
            $this->static[$requestMethod->value][$route->getPath()] = $id;
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
        if (($name = $route->getName()) !== null && ($id = $route->getId()) !== null) {
            // Set the route in the named routes list
            $this->named[$name] = $id;
        }
    }

    /**
     * Get a route of type (static|dynamic).
     *
     * @param RequestMethodList  $type   The type [static|dynamic]
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @throws JsonException
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
     * @throws JsonException
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
