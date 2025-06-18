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

namespace Valkyrja\Http\Routing\Contract;

use Valkyrja\Http\Message\Exception\HttpException;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Config;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Exception\InvalidRouteNameException;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher;

/**
 * Interface Router.
 *
 * @author Melech Mizrachi
 */
interface Router
{
    /**
     * Get the config.
     */
    public function getConfig(): Config;

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
     * @throws InvalidRouteNameException
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
     * Match a route, or a response if no route exists, from a given server request.
     *
     * @param ServerRequest $request The request
     *
     * @throws HttpException
     *
     * @return Route|Response
     */
    public function attemptToMatchRoute(ServerRequest $request): Route|Response;

    /**
     * Dispatch a server request.
     *
     * @param ServerRequest $request The request
     *
     * @return Response
     */
    public function dispatch(ServerRequest $request): Response;

    /**
     * Dispatch a server request and a specific route.
     */
    public function dispatchRoute(ServerRequest $request, Route $route): Response;
}
