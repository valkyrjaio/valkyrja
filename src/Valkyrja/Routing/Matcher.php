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

namespace Valkyrja\Routing;

/**
 * Interface Matcher.
 *
 * @author Melech Mizrachi
 */
interface Matcher
{
    /**
     * Set the collection.
     *
     * @param Collection $collection The collection
     *
     * @return void
     */
    public function setCollection(Collection $collection): void;

    /**
     * Trim a path.
     *
     * @param string $path The path
     *
     * @return string
     */
    public function trimPath(string $path): string;

    /**
     * Match a route by path.
     *
     * @param string $path   The path
     * @param string $method The request method
     *
     * @return Route|null
     *      The route if found or null when no route is
     *      found for the path and method combination specified
     */
    public function match(string $path, string $method): ?Route;

    /**
     * Match a dynamic route by path.
     *
     * @param string $path   The path
     * @param string $method The request method
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function matchStatic(string $path, string $method): ?Route;

    /**
     * Match a static route by path.
     *
     * @param string $path   The path
     * @param string $method The request method
     *
     * @return Route|null
     *      The route if found or null when no dynamic route is
     *      found for the path and method combination specified
     */
    public function matchDynamic(string $path, string $method): ?Route;
}
