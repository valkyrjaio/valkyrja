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

use Valkyrja\Routing\Cacheables\CacheableCollection;
use Valkyrja\Routing\Collection as CollectionContract;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Route;

use function array_merge;
use function json_encode;
use function md5;
use function Valkyrja\app;

use const JSON_THROW_ON_ERROR;

/**
 * Class RouteCollection.
 *
 * @author Melech Mizrachi
 */
class Collection implements CollectionContract
{
    use CacheableCollection;
    use CollectionHelpers;

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
     * @var Matcher
     */
    protected Matcher $matcher;

    /**
     * RouteCollection constructor.
     *
     * @param Matcher $matcher
     */
    public function __construct(Matcher $matcher)
    {
        $matcher->setCollection($this);

        $this->matcher = $matcher;
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

        $this->routes[md5(json_encode($route, JSON_THROW_ON_ERROR))] = $route;
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
}
