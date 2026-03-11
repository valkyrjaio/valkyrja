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

use Closure;
use Override;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Data;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteRegexException;
use Valkyrja\Http\Routing\Throwable\Exception\RuntimeException;

use function array_map;
use function in_array;
use function is_callable;

/**
 * @phpstan-import-type RequestMethodList from CollectionContract
 *
 * @psalm-import-type RequestMethodList from CollectionContract
 */
class Collection implements CollectionContract
{
    /**
     * The routes.
     * Keyed by route name.
     *
     * @var array<string, RouteContract|DynamicRouteContract|(Closure():RouteContract|DynamicRouteContract)>
     */
    protected array $routes = [];

    /**
     * A map of paths to route names.
     *
     * @var RequestMethodList
     */
    protected array $paths = [];

    /**
     * A map of paths to route names.
     *
     * @var RequestMethodList
     */
    protected array $dynamicPaths = [];

    /**
     * A map of regexes to route names.
     *
     * @var RequestMethodList
     */
    protected array $regexes = [];

    /**
     * @inheritDoc
     */
    #[Override]
    public function getData(): Data
    {
        return new Data(
            routes: array_map(
                static fn (RouteContract|Closure $route): RouteContract => is_callable($route)
                    ? $route()
                    : $route,
                $this->routes
            ),
            paths: $this->paths,
            dynamicPaths: $this->dynamicPaths,
            regexes: $this->regexes,
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setFromData(Data $data): void
    {
        $this->routes       = $data->routes;
        $this->paths        = $data->paths;
        $this->dynamicPaths = $data->dynamicPaths;
        $this->regexes      = $data->regexes;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function add(RouteContract $route): void
    {
        // Set the route to its request methods
        $this->setRouteToRequestMethods($route);

        $this->routes[$route->getName()] = $route;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasPath(string $path, RequestMethod $method): bool
    {
        if ($method !== RequestMethod::ANY) {
            $type = $method->value;

            return isset($this->paths[$type][$path])
                || isset($this->dynamicPaths[$type][$path]);
        }

        return array_any(
            $method->all(),
            fn (RequestMethod $methodToCheck): bool => $this->hasPath($path, $methodToCheck)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getByPath(string $path, RequestMethod $method): RouteContract
    {
        return $this->internalGetByPath($path, $method)
            ?? throw new InvalidRoutePathException("The path '$path' is not a valid route for the given method '$method->value'");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasRegex(string $regex, RequestMethod $method): bool
    {
        if ($method !== RequestMethod::ANY) {
            $type = $method->value;

            return isset($this->regexes[$type][$regex]);
        }

        return array_any(
            $method->all(),
            fn (RequestMethod $methodToCheck): bool => $this->hasRegex($regex, $methodToCheck)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getByRegex(string $regex, RequestMethod $method): DynamicRouteContract
    {
        return $this->internalGetByRegex($regex, $method)
            ?? throw new InvalidRouteRegexException("The regex '$regex' is not a valid route for the given method '$method->value'");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPaths(RequestMethod $method): array
    {
        if ($method !== RequestMethod::ANY) {
            $type = $method->value;

            return $this->paths[$type] ?? [];
        }

        return array_merge(
            $this->paths[RequestMethod::HEAD->value] ?? [],
            $this->paths[RequestMethod::GET->value] ?? [],
            $this->paths[RequestMethod::POST->value] ?? [],
            $this->paths[RequestMethod::PATCH->value] ?? [],
            $this->paths[RequestMethod::PUT->value] ?? [],
            $this->paths[RequestMethod::DELETE->value] ?? [],
            $this->paths[RequestMethod::OPTIONS->value] ?? [],
            $this->paths[RequestMethod::TRACE->value] ?? [],
            $this->paths[RequestMethod::CONNECT->value] ?? [],
            $this->dynamicPaths[RequestMethod::HEAD->value] ?? [],
            $this->dynamicPaths[RequestMethod::GET->value] ?? [],
            $this->dynamicPaths[RequestMethod::POST->value] ?? [],
            $this->dynamicPaths[RequestMethod::PATCH->value] ?? [],
            $this->dynamicPaths[RequestMethod::PUT->value] ?? [],
            $this->dynamicPaths[RequestMethod::DELETE->value] ?? [],
            $this->dynamicPaths[RequestMethod::OPTIONS->value] ?? [],
            $this->dynamicPaths[RequestMethod::TRACE->value] ?? [],
            $this->dynamicPaths[RequestMethod::CONNECT->value] ?? [],
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRegexes(RequestMethod $method): array
    {
        if ($method !== RequestMethod::ANY) {
            $type = $method->value;

            return $this->regexes[$type] ?? [];
        }

        return array_merge(
            $this->regexes[RequestMethod::HEAD->value] ?? [],
            $this->regexes[RequestMethod::GET->value] ?? [],
            $this->regexes[RequestMethod::POST->value] ?? [],
            $this->regexes[RequestMethod::PATCH->value] ?? [],
            $this->regexes[RequestMethod::PUT->value] ?? [],
            $this->regexes[RequestMethod::DELETE->value] ?? [],
            $this->regexes[RequestMethod::OPTIONS->value] ?? [],
            $this->regexes[RequestMethod::TRACE->value] ?? [],
            $this->regexes[RequestMethod::CONNECT->value] ?? [],
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasName(string $name): bool
    {
        return isset($this->routes[$name]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getByName(string $name): RouteContract
    {
        $route = $this->routes[$name]
            ?? null;

        if ($route !== null) {
            return $this->ensureRoute($route);
        }

        throw new InvalidRouteNameException("A route with the name '$name' does not exist");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAll(RequestMethod $method): array
    {
        $paths = $this->getPaths($method);

        return $this->getRoutesFromNames($paths);
    }

    /**
     * Get a route by path.
     */
    protected function internalGetByPath(string $path, RequestMethod $method): RouteContract|null
    {
        if ($method !== RequestMethod::ANY) {
            $type = $method->value;

            $route = $this->paths[$type][$path]
                ?? $this->dynamicPaths[$type][$path]
                ?? null;

            if ($route !== null) {
                return $this->getRouteFromName($route);
            }

            return null;
        }

        return $this->internalGetByPath($path, RequestMethod::GET)
            ?? $this->internalGetByPath($path, RequestMethod::HEAD)
            ?? $this->internalGetByPath($path, RequestMethod::POST)
            ?? $this->internalGetByPath($path, RequestMethod::PATCH)
            ?? $this->internalGetByPath($path, RequestMethod::PUT)
            ?? $this->internalGetByPath($path, RequestMethod::DELETE)
            ?? $this->internalGetByPath($path, RequestMethod::OPTIONS)
            ?? $this->internalGetByPath($path, RequestMethod::TRACE)
            ?? $this->internalGetByPath($path, RequestMethod::CONNECT);
    }

    /**
     * Get a route by path.
     */
    protected function internalGetByRegex(string $regex, RequestMethod $method): DynamicRouteContract|null
    {
        if ($method !== RequestMethod::ANY) {
            $type = $method->value;

            $route = $this->regexes[$type][$regex]
                ?? null;

            if ($route !== null) {
                return $this->getDynamicRouteFromName($route);
            }

            return null;
        }

        return $this->internalGetByRegex($regex, RequestMethod::GET)
            ?? $this->internalGetByRegex($regex, RequestMethod::HEAD)
            ?? $this->internalGetByRegex($regex, RequestMethod::POST)
            ?? $this->internalGetByRegex($regex, RequestMethod::PATCH)
            ?? $this->internalGetByRegex($regex, RequestMethod::PUT)
            ?? $this->internalGetByRegex($regex, RequestMethod::DELETE)
            ?? $this->internalGetByRegex($regex, RequestMethod::OPTIONS)
            ?? $this->internalGetByRegex($regex, RequestMethod::TRACE)
            ?? $this->internalGetByRegex($regex, RequestMethod::CONNECT);
    }

    /**
     * Set a route to its request methods.
     */
    protected function setRouteToRequestMethods(RouteContract $route): void
    {
        $requestMethods = $route->getRequestMethods();

        if (in_array(RequestMethod::ANY, $requestMethods, true)) {
            $requestMethods = RequestMethod::all();
        }

        foreach ($requestMethods as $requestMethod) {
            $this->setRouteToRequestMethod($route, $requestMethod);
        }
    }

    /**
     * Set the route to the request method.
     */
    protected function setRouteToRequestMethod(RouteContract $route, RequestMethod $requestMethod): void
    {
        if ($requestMethod === RequestMethod::ANY) {
            return;
        }

        $name = $route->getName();
        $path = $route->getPath();

        // If this is a dynamic route
        if ($route instanceof DynamicRouteContract) {
            $regex = $route->getRegex();

            // Set the route in the dynamic routes list
            $this->dynamicPaths[$requestMethod->value][$path] = $name;
            $this->regexes[$requestMethod->value][$regex]     = $name;

            return;
        }

        // Otherwise set it in the static routes array
        // Set the route in the static routes list
        $this->paths[$requestMethod->value][$path] = $name;
    }

    /**
     * Get a list of routes for the given names.
     *
     * @param array<string, string> $names The route names
     *
     * @return array<string, RouteContract>
     */
    protected function getRoutesFromNames(array $names): array
    {
        return array_map(
            fn (string $name): RouteContract => $this->getRouteFromName($name),
            $names
        );
    }

    /**
     * Ensure a route is returned.
     *
     * @param RouteContract|Closure():RouteContract $route The route
     */
    protected function ensureRoute(RouteContract|Closure $route): RouteContract
    {
        if (is_callable($route)) {
            return $route();
        }

        return $route;
    }

    /**
     * Get a route from a given name.
     */
    protected function getRouteFromName(string $name): RouteContract
    {
        $route = $this->routes[$name]
            ?? throw new InvalidRouteNameException("Invalid name `$name` provided");

        return $this->ensureRoute($route);
    }

    /**
     * Get a dynamic route from a given name.
     */
    protected function getDynamicRouteFromName(string $name): DynamicRouteContract
    {
        $route = $this->getRouteFromName($name);

        if ($route instanceof DynamicRouteContract) {
            return $route;
        }

        throw new RuntimeException('Invalid dynamic route');
    }
}
