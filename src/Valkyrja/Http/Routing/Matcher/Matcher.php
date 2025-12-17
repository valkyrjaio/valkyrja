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

namespace Valkyrja\Http\Routing\Matcher;

use Override;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\Collection as RouteCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Data\Contract\Parameter;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Exception\InvalidRouteParameterException;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher as Contract;
use Valkyrja\Http\Routing\Support\Helpers;
use Valkyrja\Type\Data\Cast;

use function is_array;
use function preg_match;

/**
 * Class Matcher.
 *
 * @author Melech Mizrachi
 */
class Matcher implements Contract
{
    /**
     * Matcher constructor.
     *
     * @param Collection $collection The collection
     */
    public function __construct(
        protected Collection $collection = new RouteCollection()
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRoutePathException
     * @throws InvalidRouteParameterException
     */
    #[Override]
    public function match(string $path, RequestMethod|null $requestMethod = null): Route|null
    {
        $path  = Helpers::trimPath($path);
        $route = $this->matchStatic($path, $requestMethod);

        return $route
            ?? $this->matchDynamic($path, $requestMethod);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function matchStatic(string $path, RequestMethod|null $requestMethod = null): Route|null
    {
        $route = $this->collection->getStatic($path, $requestMethod);

        if ($route !== null) {
            return clone $route;
        }

        return null;
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRoutePathException
     * @throws InvalidRouteParameterException
     */
    #[Override]
    public function matchDynamic(string $path, RequestMethod|null $requestMethod = null): Route|null
    {
        return $this->matchDynamicFromArray($this->collection->allDynamic($requestMethod), $path);
    }

    /**
     * Match a dynamic route by path from a given array.
     *
     * @param array<string, Route>|array<string, array<string, Route>> $routes The routes
     * @param string                                                   $path   The path
     *
     * @throws InvalidRoutePathException
     * @throws InvalidRouteParameterException
     *
     * @return Route|null
     */
    protected function matchDynamicFromArray(array $routes, string $path): Route|null
    {
        // Attempt to find a match using dynamic routes that are set
        foreach ($routes as $regex => $route) {
            if (($match = $this->matchDynamicFromRouteOrArray($route, $path, $regex)) !== null) {
                return $match;
            }
        }

        return null;
    }

    /**
     * Match a dynamic route by path from a given route or array.
     *
     * @param Route|array<string, Route> $route The route
     * @param string                     $path  The path
     * @param string                     $regex The regex
     *
     * @throws InvalidRoutePathException
     * @throws InvalidRouteParameterException
     *
     * @return Route|null
     */
    protected function matchDynamicFromRouteOrArray(Route|array $route, string $path, string $regex): Route|null
    {
        if (is_array($route)) {
            return $this->matchDynamicFromArray($route, $path);
        }

        // If the preg match is successful, we've found our route!
        if ($regex !== '' && preg_match($regex, $path, $matches)) {
            /** @var array<int|non-empty-string, string> $matches */
            return $this->processArguments($route, $matches);
        }

        return null;
    }

    /**
     * Process matches for a dynamic route.
     *
     * @param Route                               $route   The route
     * @param array<int|non-empty-string, string> $matches The regex matches
     *
     * @throws InvalidRoutePathException
     *
     * @return Route
     */
    protected function processArguments(Route $route, array $matches): Route
    {
        $dispatch = $route->getDispatch();

        // The first match is the path itself, the rest could be empty.
        array_shift($matches);

        // Get the parameters
        $parameters = $route->getParameters();

        if ($parameters === []) {
            throw new InvalidRoutePathException('Route parameters must not be empty');
        }

        $arguments = [];

        // Iterate through the matches
        foreach ($parameters as $parameter) {
            $name  = $parameter->getName();
            $match = $matches[$name]
                ??= $parameter->getDefault();

            if ($match === null) {
                continue;
            }

            $arguments[$name] = $this->checkAndCastMatchValue(
                parameter: $parameter,
                match: $match
            );
        }

        return $route->withDispatch($dispatch->withArguments($arguments));
    }

    /**
     * @param Parameter                          $parameter The parameter
     * @param array<scalar|object>|scalar|object $match     The match
     *
     * @return array<scalar|object>|scalar|object|null
     */
    protected function checkAndCastMatchValue(
        Parameter $parameter,
        array|string|int|bool|float|object $match
    ): array|string|int|bool|float|object|null {
        $cast = $parameter->getCast();

        if ($cast !== null) {
            return $this->castMatchValue(
                cast: $cast,
                match: $match
            );
        }

        return $match;
    }

    /**
     * Get a match value for the given cast type.
     *
     * @param Cast                               $cast  The cast
     * @param array<scalar|object>|scalar|object $match The match value
     *
     * @return array<scalar|object>|scalar|object|null
     */
    protected function castMatchValue(
        Cast $cast,
        array|string|int|bool|float|object $match
    ): array|string|int|bool|float|object|null {
        $type = $cast->type::fromValue($match);

        if ($cast->convert) {
            /** @var array<scalar|object>|scalar|object|null $type */
            $type = $type->asValue();
        }

        return $type;
    }
}
