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
use Valkyrja\Http\Routing\Model\Contract\Route;

/**
 * Interface Collection.
 *
 * @author Melech Mizrachi
 */
interface Collection
{
    /**
     * Add a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function add(Route $route): void;

    /**
     * Get a route.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no route is
     *                    found for the path combination specified
     */
    public function get(string $path, RequestMethod|null $method = null): Route|null;

    /**
     * Determine if a route exists.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return bool
     */
    public function isset(string $path, RequestMethod|null $method = null): bool;

    /**
     * Get all routes.
     *
     * @return Route[][]
     */
    public function all(): array;

    /**
     * Get a flat array of routes.
     *
     * @return Route[]
     */
    public function allFlattened(): array;

    /**
     * Get a static route.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no static route is
     *                    found for the path and method combination specified
     */
    public function getStatic(string $path, RequestMethod|null $method = null): Route|null;

    /**
     * Determine if a static route exists.
     *
     * @param string             $path   The path
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return bool
     */
    public function hasStatic(string $path, RequestMethod|null $method = null): bool;

    /**
     * Get static routes of a certain request method.
     *
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return array<string, Route>|array<string, array<string, Route>>
     */
    public function allStatic(RequestMethod|null $method = null): array;

    /**
     * Get a dynamic route.
     *
     * @param string             $regex  The regex
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return Route|null
     *                    The route if found or null when no dynamic route is
     *                    found for the path and method combination specified
     */
    public function getDynamic(string $regex, RequestMethod|null $method = null): Route|null;

    /**
     * Determine if a dynamic route exists.
     *
     * @param string             $regex  The regex
     * @param RequestMethod|null $method [optional] The request method
     *
     * @return bool
     */
    public function hasDynamic(string $regex, RequestMethod|null $method = null): bool;

    /**
     * Get the dynamic routes in this collection.
     *
     * @param RequestMethod|null $method
     *
     * @return array<string, Route>|array<string, array<string, Route>>
     */
    public function allDynamic(RequestMethod|null $method = null): array;

    /**
     * Get a named route.
     *
     * @param string $name The name
     *
     * @return Route|null
     *                    The route if found or null when no named route is
     *                    found for the path and method combination specified
     */
    public function getNamed(string $name): Route|null;

    /**
     * Determine if a named route exists.
     *
     * @param string $name The name
     *
     * @return bool
     */
    public function hasNamed(string $name): bool;

    /**
     * Get the named routes in this collection.
     *
     * @return string[]
     */
    public function allNamed(): array;
}
