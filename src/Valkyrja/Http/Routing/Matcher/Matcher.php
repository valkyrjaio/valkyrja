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

use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\Collection as RouteCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Exception\InvalidRouteParameterException;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher as Contract;
use Valkyrja\Http\Routing\Model\Contract\Route;
use Valkyrja\Http\Routing\Model\Parameter\Parameter;
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
    public function match(string $path, RequestMethod|null $method = null): Route|null
    {
        $path  = Helpers::trimPath($path);
        $route = $this->matchStatic($path, $method);

        return $route ?? $this->matchDynamic($path, $method);
    }

    /**
     * @inheritDoc
     */
    public function matchStatic(string $path, RequestMethod|null $method = null): Route|null
    {
        $route = $this->collection->getStatic($path, $method);

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
    public function matchDynamic(string $path, RequestMethod|null $method = null): Route|null
    {
        return $this->matchDynamicFromArray($this->collection->allDynamic($method), $path);
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
            /** @var array<int, mixed> $matches */
            return $this->applyMatchesToRoute($route, $matches);
        }

        return null;
    }

    /**
     * Get a matched dynamic route.
     *
     * @param Route             $route   The route
     * @param array<int, mixed> $matches The regex matches
     *
     * @throws InvalidRoutePathException
     *
     * @return Route
     */
    protected function applyMatchesToRoute(Route $route, array $matches): Route
    {
        // Clone the route to avoid changing the one set in the master array
        $route = clone $route;

        $this->processMatches($route, $matches);

        return $route;
    }

    /**
     * Process matches for a dynamic route.
     *
     * @param Route             $route   The route
     * @param array<int, mixed> $matches The regex matches
     *
     * @throws InvalidRoutePathException
     *
     * @return void
     */
    protected function processMatches(Route $route, array &$matches): void
    {
        // The first match is the path itself, the rest could be empty.
        if (array_shift($matches) === null || empty($matches)) {
            return;
        }

        // Get the parameters
        $parameters = $route->getParameters();
        // Get the last index in the array
        $lastIndex = array_key_last($matches);

        // Iterate through the matches
        foreach ($matches as $index => $match) {
            $parameter = $this->getParameterForMatchIndex($parameters, $index);

            $this->updateMatchValueWithDefault($parameter, $matches, $index, $match, $lastIndex);
            $this->checkAndCastMatchValue($route, $parameter, $matches, $index, $match);
        }

        // Set the matches
        $route->setMatches($matches);
    }

    /**
     * @param array<int, Parameter> $parameters The parameters
     * @param int                   $index      The index for this match
     *
     * @throws InvalidRoutePathException
     *
     * @return Parameter
     */
    protected function getParameterForMatchIndex(array $parameters, int $index): Parameter
    {
        return $parameters[$index]
            ?? throw new InvalidRoutePathException("No parameter for match key $index");
    }

    /**
     * Update a match's value with the default as defined in the parameter.
     *
     * @param Parameter         $parameter The parameter
     * @param array<int, mixed> $matches   The matches
     * @param int               $index     The index for this match
     * @param mixed             $match     The match
     * @param int               $lastIndex The last index
     *
     * @return void
     */
    protected function updateMatchValueWithDefault(
        Parameter $parameter,
        array &$matches,
        int $index,
        mixed &$match,
        int $lastIndex
    ): void {
        // If there is no match (middle of regex optional group)
        if (! $match) {
            // If the optional parameter was at the end, let the action decide the default assuming a default
            // is not set in the parameter already
            if ($lastIndex === $index && $parameter->getDefault() !== null) {
                array_pop($matches);

                return;
            }

            // Set the value to the parameter default
            $matches[$index] = $match = $parameter->getDefault();
        }
    }

    /**
     * @param Route             $route     The Route
     * @param Parameter         $parameter The parameter
     * @param array<int, mixed> $matches   The matches
     * @param int               $index     The index for this match
     * @param mixed             $match     The match
     *
     * @return void
     */
    protected function checkAndCastMatchValue(
        Route $route,
        Parameter $parameter,
        array &$matches,
        int $index,
        mixed $match
    ): void {
        if ($cast = $parameter->getCast()) {
            $matches[$index] = $this->castMatchValue($route, $parameter, $cast, $index, $match);
        }
    }

    /**
     * Get a match value for the given cast type.
     *
     * @param Route     $route     The route
     * @param Parameter $parameter The parameter
     * @param Cast      $cast      The cast
     * @param int       $index     The match index
     * @param mixed     $match     The match value
     *
     * @return mixed
     */
    protected function castMatchValue(Route $route, Parameter $parameter, Cast $cast, int $index, mixed $match): mixed
    {
        $type = $cast->type::fromValue($match);

        if ($cast->convert) {
            return $type->asValue();
        }

        return $type;
    }
}
