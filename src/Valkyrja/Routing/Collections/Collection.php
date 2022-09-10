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
use Valkyrja\Routing\Constants\Regex;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Helpers;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Str;

use function array_merge;
use function is_array;
use function is_string;
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
     */
    public function __construct(Container $container, Dispatcher $dispatcher)
    {
        $this->container  = $container;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function add(Route $route): void
    {
        // Verify the route
        $this->verifyRoute($route);
        // Verify the dispatch
        $this->dispatcher->verifyDispatch($route);

        // Set the id to an md5 hash of the route
        $route->setId(md5(Arr::toString($route->asArray())));
        // Set the path to the validated cleaned path (/some/path)
        $route->setPath(Helpers::trimPath($route->getPath() ?? ''));
        // Set the route to its request methods
        $this->setRouteToRequestMethods($route);
        // Set the route to the named
        $this->setRouteToNamed($route);

        $this->routes[$route->getId()] = $route;
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, string $method = null): ?Route
    {
        return $this->getStatic($path, $method) ?? $this->getDynamic($path, $method);
    }

    /**
     * @inheritDoc
     */
    public function isset(string $path, string $method = null): bool
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
     */
    public function getStatic(string $path, string $method = null): ?Route
    {
        return $this->getOfType($this->static, $path, $method);
    }

    /**
     * @inheritDoc
     */
    public function hasStatic(string $path, string $method = null): bool
    {
        return $this->hasOfType($this->static, $path, $method);
    }

    /**
     * @inheritDoc
     */
    public function allStatic(string $method = null): array
    {
        return $this->allOfType($this->static, $method);
    }

    /**
     * @inheritDoc
     */
    public function getDynamic(string $regex, string $method = null): ?Route
    {
        return $this->getOfType($this->dynamic, $regex, $method);
    }

    /**
     * @inheritDoc
     */
    public function hasDynamic(string $regex, string $method = null): bool
    {
        return $this->hasOfType($this->dynamic, $regex, $method);
    }

    /**
     * @inheritDoc
     */
    public function allDynamic(string $method = null): array
    {
        return $this->allOfType($this->dynamic, $method);
    }

    /**
     * @inheritDoc
     */
    public function getNamed(string $name): ?Route
    {
        return $this->ensureRoute($this->named[$name] ?? null);
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
            $this->createRouteRegex($route);

            // Set the route in the dynamic routes list
            $this->dynamic[$requestMethod][$route->getRegex()] = $route->getId();
        } // Otherwise set it in the static routes array
        else {
            // Set the route in the static routes list
            $this->static[$requestMethod][$route->getPath()] = $route->getId();
        }
    }

    /**
     * Create the regex for a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function createRouteRegex(Route $route): void
    {
        // If the regex has already been set then don't do anything
        if ($route->getRegex() || empty($route->getParameters())) {
            return;
        }

        $regex = $route->getPath();

        // Iterate through the route's parameters
        foreach ($route->getParameters() as $parameter) {
            $nameReplacement = "{{$parameter->getName()}}";

            // Check if the path doesn't contain the parameter's name
            if (! Str::contains($regex, $nameReplacement)) {
                continue;
            }

            $paramRegex = (! $parameter->shouldCapture() ? Regex::START_NON_CAPTURE_GROUP : Regex::START_CAPTURE_GROUP)
                . $parameter->getRegex()
                . ($parameter->isOptional() ? Regex::END_OPTIONAL_CAPTURE_GROUP : Regex::END_CAPTURE_GROUP);

            $regex = Str::replace($regex, $nameReplacement, $paramRegex);
        }

        // Set the regex
        $route->setRegex(Regex::START . Str::replace($regex, '/', '\/') . Regex::END);
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
            $this->named[$route->getName()] = $route->getId();
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
     * @param Route|array|string|null $route The route
     *
     * @return Route|null
     */
    protected function ensureRoute(Route|array|string $route = null): ?Route
    {
        if (is_string($route)) {
            $route = $this->routes[$route];
        }

        if (is_array($route)) {
            return \Valkyrja\Routing\Models\Route::fromArray($route);
        }

        return $route;
    }
}
