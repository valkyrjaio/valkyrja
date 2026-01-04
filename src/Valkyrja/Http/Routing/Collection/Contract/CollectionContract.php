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

namespace Valkyrja\Http\Routing\Collection\Contract;

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Data;

/**
 * @psalm-type RequestMethodList array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
 *
 * @phpstan-type RequestMethodList array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
 *
 * @psalm-type RequestMethodRouteList array{CONNECT?: array<string, RouteContract>, DELETE?: array<string, RouteContract>, GET?: array<string, RouteContract>, HEAD?: array<string, RouteContract>, OPTIONS?: array<string, RouteContract>, PATCH?: array<string, RouteContract>, POST?: array<string, RouteContract>, PUT?: array<string, RouteContract>, TRACE?: array<string, RouteContract>}
 *
 * @phpstan-type RequestMethodRouteList array{CONNECT?: array<string, RouteContract>, DELETE?: array<string, RouteContract>, GET?: array<string, RouteContract>, HEAD?: array<string, RouteContract>, OPTIONS?: array<string, RouteContract>, PATCH?: array<string, RouteContract>, POST?: array<string, RouteContract>, PUT?: array<string, RouteContract>, TRACE?: array<string, RouteContract>}
 */
interface CollectionContract
{
    /**
     * Get a data representation of the collection.
     */
    public function getData(): Data;

    /**
     * Set data from a data object.
     */
    public function setFromData(Data $data): void;

    /**
     * Add a route.
     *
     * @param RouteContract $route The route
     */
    public function add(RouteContract $route): void;

    /**
     * Get a route.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return RouteContract|null
     *                            The route if found or null when no route is
     *                            found for the path combination specified
     */
    public function get(string $path, RequestMethod|null $method = null): RouteContract|null;

    /**
     * Determine if a route exists.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     */
    public function has(string $path, RequestMethod|null $method = null): bool;

    /**
     * Get all routes.
     *
     * @return RouteContract[][]
     */
    public function all(): array;

    /**
     * Get a flat array of routes.
     *
     * @return array<string, RouteContract>
     */
    public function allFlattened(): array;

    /**
     * Get a static route.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return RouteContract|null
     *                            The route if found or null when no static route is
     *                            found for the path and method combination specified
     */
    public function getStatic(string $path, RequestMethod|null $method = null): RouteContract|null;

    /**
     * Determine if a static route exists.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     */
    public function hasStatic(string $path, RequestMethod|null $method = null): bool;

    /**
     * Get static routes of a certain request method.
     *
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return array<string, RouteContract>|array<string, array<string, RouteContract>>
     */
    public function allStatic(RequestMethod|null $method = null): array;

    /**
     * Get a dynamic route.
     *
     * @param string             $regex  The regex
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return RouteContract|null
     *                            The route if found or null when no dynamic route is
     *                            found for the path and method combination specified
     */
    public function getDynamic(string $regex, RequestMethod|null $method = null): RouteContract|null;

    /**
     * Determine if a dynamic route exists.
     *
     * @param string             $regex  The regex
     * @param RequestMethod|null $method [optional] The request method
     */
    public function hasDynamic(string $regex, RequestMethod|null $method = null): bool;

    /**
     * Get the dynamic routes in this collection.
     *
     *
     * @return array<string, RouteContract>|array<string, array<string, RouteContract>>
     */
    public function allDynamic(RequestMethod|null $method = null): array;

    /**
     * Get a route by name.
     *
     * @param string $name The name
     *
     * @return RouteContract|null
     *                            The route if found or null when no named route is
     *                            found for the path and method combination specified
     */
    public function getByName(string $name): RouteContract|null;

    /**
     * Determine if a named route exists.
     *
     * @param string $name The name
     */
    public function hasNamed(string $name): bool;
}
