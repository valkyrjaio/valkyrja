<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Routing\Collection;

use Closure;
use Override;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Throwable\Exception\CliRoutingInvalidRouteNameException;

use function array_map;

class RouteCollection implements RouteCollectionContract
{
    /** @var array<string, Closure():RouteContract> */
    protected array $routes = [];

    /**
     * Get a data representation of the collection.
     */
    #[Override]
    public function getData(): CliRoutingData
    {
        return new CliRoutingData(
            routes: $this->routes,
        );
    }

    /**
     * Set data from a data object.
     */
    #[Override]
    public function setFromData(CliRoutingData $data): void
    {
        $this->routes = $data->routes;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function add(RouteContract ...$commands): static
    {
        foreach ($commands as $command) {
            $this->routes[$command->getName()] = static fn (): RouteContract => $command;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string $name): RouteContract
    {
        $route = $this->routes[$name]
            ?? null;

        if ($route !== null) {
            return $this->ensureRoute($route);
        }

        throw new CliRoutingInvalidRouteNameException("The route `$name` was not found.");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string $name): bool
    {
        return isset($this->routes[$name]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function all(): array
    {
        return array_map(
            fn (Closure $route): RouteContract => $this->ensureRoute($route),
            $this->routes
        );
    }

    /**
     * Ensure a route, or null, is returned.
     *
     * @param Closure():RouteContract $route The route
     */
    protected function ensureRoute(Closure $route): RouteContract
    {
        return $route();
    }
}
