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
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Matcher\Contract\MatcherContract as Contract;
use Valkyrja\Http\Routing\Support\Helpers;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRouteParameterException;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRoutePathException;
use Valkyrja\Type\Data\Cast;

use function is_array;
use function preg_match;

class Matcher implements Contract
{
    public function __construct(
        protected CollectionContract $collection = new RouteCollection()
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRoutePathException
     * @throws InvalidRouteParameterException
     */
    #[Override]
    public function match(string $path, RequestMethod|null $requestMethod = null): RouteContract|null
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
    public function matchStatic(string $path, RequestMethod|null $requestMethod = null): RouteContract|null
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
    public function matchDynamic(string $path, RequestMethod|null $requestMethod = null): RouteContract|null
    {
        return $this->matchDynamicFromArray($this->collection->allDynamic($requestMethod), $path);
    }

    /**
     * Match a dynamic route by path from a given array.
     *
     * @param array<string, RouteContract>|array<string, array<string, RouteContract>> $routes The routes
     * @param string                                                                   $path   The path
     *
     * @throws InvalidRoutePathException
     * @throws InvalidRouteParameterException
     */
    protected function matchDynamicFromArray(array $routes, string $path): RouteContract|null
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
     * @param RouteContract|array<string, RouteContract> $route The route
     * @param string                                     $path  The path
     * @param string                                     $regex The regex
     *
     * @throws InvalidRoutePathException
     * @throws InvalidRouteParameterException
     */
    protected function matchDynamicFromRouteOrArray(RouteContract|array $route, string $path, string $regex): RouteContract|null
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
     * @param RouteContract                       $route   The route
     * @param array<int|non-empty-string, string> $matches The regex matches
     *
     * @throws InvalidRoutePathException
     */
    protected function processArguments(RouteContract $route, array $matches): RouteContract
    {
        $dispatch = $route->getDispatch();

        // The first match is the path itself, the rest could be empty.
        array_shift($matches);

        // Get the parameters
        $parameters = $route->getParameters();

        if ($parameters === []) {
            throw new InvalidRoutePathException('Route parameters must not be empty');
        }

        $arguments            = [];
        $parametersWithValues = [];

        // Iterate through the matches
        foreach ($parameters as $parameter) {
            $name  = $parameter->getName();
            $match = $matches[$name]
                ??= $parameter->getDefault();

            if ($match === null) {
                $parametersWithValues[] = $parameter;

                continue;
            }

            $arguments[$name] = $this->checkAndCastMatchValue(
                parameter: $parameter,
                match: $match
            );

            $parametersWithValues[] = $parameter->withValue($arguments[$name]);
        }

        return $route
            ->withParameters(...$parametersWithValues)
            ->withDispatch($dispatch->withArguments($arguments));
    }

    /**
     * @param ParameterContract                  $parameter The parameter
     * @param array<scalar|object>|scalar|object $match     The match
     *
     * @return array<scalar|object>|scalar|object|null
     */
    protected function checkAndCastMatchValue(
        ParameterContract $parameter,
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
