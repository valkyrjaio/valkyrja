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

namespace Valkyrja\Cli\Routing\Collection;

use Closure;
use Override;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidRouteNameException;

use function is_callable;

class Collection implements CollectionContract
{
    /** @var array<string, RouteContract|Closure():RouteContract> */
    protected array $routes = [];

    /**
     * Get a data representation of the collection.
     */
    #[Override]
    public function getData(): Data
    {
        return new Data(
            routes: $this->routes,
        );
    }

    /**
     * Set data from a data object.
     */
    #[Override]
    public function setFromData(Data $data): void
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
            $this->routes[$command->getName()] = $command;
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

        throw new InvalidRouteNameException("The route `$name` was not found.");
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
            fn (RouteContract|Closure $route): RouteContract => $this->ensureRoute($route),
            $this->routes
        );
    }

    /**
     * Ensure a route, or null, is returned.
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
}
