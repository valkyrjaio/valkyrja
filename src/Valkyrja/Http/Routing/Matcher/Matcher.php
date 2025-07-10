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

use Valkyrja\Dispatcher\Data\Contract\ClassDispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Collection\Collection as RouteCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Data\Contract\Parameter;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Exception\InvalidRouteParameterException;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Exception\RuntimeException;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher as Contract;
use Valkyrja\Http\Routing\Support\Helpers;
use Valkyrja\Type\Data\Cast;

use function is_array;
use function is_string;
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
    public function match(string $path, RequestMethod|null $requestMethod = null): Route|null
    {
        $path  = Helpers::trimPath($path);
        $route = $this->matchStatic($path, $requestMethod);

        return $route ?? $this->matchDynamic($path, $requestMethod);
    }

    /**
     * @inheritDoc
     */
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
        if ($regex !== '' && preg_match($regex, $path, $arguments)) {
            /** @var array<int, string> $arguments */
            return $this->applyArgumentsToRoute($route, $arguments);
        }

        return null;
    }

    /**
     * Get a matched dynamic route.
     *
     * @param Route              $route     The route
     * @param array<int, string> $arguments The regex matches
     *
     * @throws InvalidRoutePathException
     *
     * @return Route
     */
    protected function applyArgumentsToRoute(Route $route, array $arguments): Route
    {
        // Clone the route to avoid changing the one set in the master array
        $route = clone $route;

        return $this->processArguments($route, $arguments);
    }

    /**
     * Process matches for a dynamic route.
     *
     * @param Route              $route     The route
     * @param array<int, string> $arguments The regex matches
     *
     * @throws InvalidRoutePathException
     *
     * @return Route
     */
    protected function processArguments(Route $route, array $arguments): Route
    {
        $dispatch = $route->getDispatch();

        // The first match is the path itself, the rest could be empty.
        if (array_shift($arguments) === null || empty($arguments) || ! $dispatch instanceof ClassDispatch) {
            return $route;
        }

        // Get the parameters
        $parameters = $route->getParameters();

        // Iterate through the matches
        foreach ($arguments as $index => $match) {
            $parameter = $this->getParameterForAgumentIndex($parameters, $index);

            $arguments = $this->updateArgumentValueWithDefault($parameter, $arguments, $index, $match);
            $arguments = $this->checkAndCastMatchValue($route, $parameter, $arguments, $index, $match);
        }

        return $route->withDispatch($dispatch->withArguments($arguments));
    }

    /**
     * @param array<array-key, Parameter> $parameters The parameters
     * @param int                         $index      The index for this argument
     *
     * @throws InvalidRoutePathException
     *
     * @return Parameter
     */
    protected function getParameterForAgumentIndex(array $parameters, int $index): Parameter
    {
        return $parameters[$index]
            ?? throw new InvalidRoutePathException("No parameter for match key $index");
    }

    /**
     * Update a match's value with the default as defined in the parameter.
     *
     * @param Parameter         $parameter The parameter
     * @param array<int, mixed> $matches   The arguments
     * @param int               $index     The index for this argument
     * @param mixed             $argument  The argument
     *
     * @return array<int, mixed>
     */
    protected function updateArgumentValueWithDefault(
        Parameter $parameter,
        array $matches,
        int $index,
        mixed $argument
    ): array {
        // If there is no match (middle of regex optional group)
        if (! $argument) {
            // Set the value to the parameter default
            /** @psalm-suppress MixedAssignment */
            $matches[$index] = $parameter->getDefault();
        }

        return $matches;
    }

    /**
     * @param Route             $route     The Route
     * @param Parameter         $parameter The parameter
     * @param array<int, mixed> $arguments The arguments
     * @param int               $index     The index for this argument
     * @param mixed             $argument  The argument
     *
     * @return array<int, mixed>
     */
    protected function checkAndCastMatchValue(
        Route $route,
        Parameter $parameter,
        array $arguments,
        int $index,
        mixed $argument
    ): array {
        $cast = $parameter->getCast();

        if ($cast !== null) {
            // This shouldn't ever happen as we're iterating over the array with fresh match values as the process runs
            if (! is_string($argument)) {
                throw new RuntimeException('Unexpected match value for ' . $parameter->getName());
            }

            /** @psalm-suppress MixedAssignment */
            $arguments[$index] = $this->castMatchValue($route, $parameter, $cast, $index, $argument);
        }

        return $arguments;
    }

    /**
     * Get a match value for the given cast type.
     *
     * @param Route     $route     The route
     * @param Parameter $parameter The parameter
     * @param Cast      $cast      The cast
     * @param int       $index     The argument index
     * @param string    $argument  The argument value
     *
     * @return mixed
     */
    protected function castMatchValue(Route $route, Parameter $parameter, Cast $cast, int $index, string $argument): mixed
    {
        $type = $cast->type::fromValue($argument);

        if ($cast->convert) {
            return $type->asValue();
        }

        return $type;
    }
}
