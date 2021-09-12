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

namespace Valkyrja\Routing;

use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Routing\Exceptions\InvalidRouteName;

/**
 * Interface Router.
 *
 * @author Melech Mizrachi
 */
interface Router extends MiddlewareAware
{
    /**
     * Get the config.
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Whether to run in debug.
     *
     * @return bool
     */
    public function debug(): bool;

    /**
     * Get the route collection.
     *
     * @return Collection
     */
    public function getCollection(): Collection;

    /**
     * Get the route matcher.
     *
     * @return Matcher
     */
    public function getMatcher(): Matcher;

    /**
     * Set a single route.
     *
     * @param Route $route
     *
     * @return void
     */
    public function addRoute(Route $route): void;

    /**
     * Get all routes set by the application.
     *
     * @return Route[]
     */
    public function getRoutes(): array;

    /**
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @throws InvalidRouteName
     *
     * @return Route
     */
    public function getRoute(string $name): Route;

    /**
     * Determine whether a route name exists.
     *
     * @param string $name The name of the route
     *
     * @return bool
     */
    public function hasRoute(string $name): bool;

    /**
     * Get a route from a request.
     *
     * @param Request $request The request
     *
     * @throws HttpException
     *
     * @return Route
     */
    public function getRouteFromRequest(Request $request): Route;

    /**
     * Dispatch the route and find a match.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    public function dispatch(Request $request): Response;
}
