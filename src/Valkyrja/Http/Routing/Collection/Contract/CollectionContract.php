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
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Data;

/**
 * @psalm-type RequestMethodList array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
 *
 * @phpstan-type RequestMethodList array{CONNECT?: array<string, string>, DELETE?: array<string, string>, GET?: array<string, string>, HEAD?: array<string, string>, OPTIONS?: array<string, string>, PATCH?: array<string, string>, POST?: array<string, string>, PUT?: array<string, string>, TRACE?: array<string, string>}
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
     */
    public function add(RouteContract $route): void;

    /**
     * Determine if a route path exists.
     */
    public function hasPath(string $path, RequestMethod $method): bool;

    /**
     * Get a route by path.
     */
    public function getByPath(string $path, RequestMethod $method): RouteContract;

    /**
     * Determine if a route regex exists.
     */
    public function hasRegex(string $regex, RequestMethod $method): bool;

    /**
     * Get a route by regex.
     */
    public function getByRegex(string $regex, RequestMethod $method): DynamicRouteContract;

    /**
     * Get all the route paths.
     * The returned array is keyed by the route path with the value being the route name.
     *
     * @return array<string, string>
     */
    public function getPaths(RequestMethod $method): array;

    /**
     * Get all the route regexes.
     * The returned array is keyed by the route regex with the value being the route name.
     *
     * @return array<string, string>
     */
    public function getRegexes(RequestMethod $method): array;

    /**
     * Determine if a route name exists.
     */
    public function hasName(string $name): bool;

    /**
     * Get a route by name.
     */
    public function getByName(string $name): RouteContract;

    /**
     * Get all routes by request method.
     * The returned array is keyed by the route name.
     *
     * @return array<string, RouteContract>
     */
    public function getAll(RequestMethod $method): array;
}
