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

namespace Valkyrja\Routing\Matchers;

use BackedEnum;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Enums\CastType;
use Valkyrja\Routing\Exceptions\InvalidRouteParameter;
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\Matcher as Contract;
use Valkyrja\Routing\Models\Parameter;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Helpers;
use Valkyrja\Support\Type\Cls;

use function preg_match;

/**
 * Class Matcher.
 *
 * @author Melech Mizrachi
 */
class Matcher implements Contract
{
    /**
     * The route collection.
     *
     * @var Collection
     */
    protected Collection $collection;

    /**
     * Matcher constructor.
     *
     * @param Collection $collection The collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRoutePath
     * @throws InvalidRouteParameter
     */
    public function match(string $path, string $method = null): ?Route
    {
        $path = Helpers::trimPath($path);

        if (null !== $route = $this->matchStatic($path, $method)) {
            return $route;
        }

        return $this->matchDynamic($path, $method);
    }

    /**
     * @inheritDoc
     */
    public function matchStatic(string $path, string $method = null): ?Route
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
     * @throws InvalidRoutePath
     * @throws InvalidRouteParameter
     */
    public function matchDynamic(string $path, string $method = null): ?Route
    {
        // Attempt to find a match using dynamic routes that are set
        foreach ($this->collection->allDynamic($method) as $regex => $route) {
            // If the preg match is successful, we've found our route!
            /* @var array $matches */
            if (preg_match($regex, $path, $matches)) {
                return $this->applyMatchesToRoute($route, $matches);
            }
        }

        return null;
    }

    /**
     * Get a matched dynamic route.
     *
     * @param Route $route   The route
     * @param array $matches The regex matches
     *
     * @throws InvalidRoutePath
     * @throws InvalidRouteParameter
     *
     * @return Route
     */
    protected function applyMatchesToRoute(Route $route, array $matches): Route
    {
        // Clone the route to avoid changing the one set in the master array
        $route = clone $route;

        // Get the parameters
        $parameters = $route->getParameters();
        // The first match is the path itself
        array_shift($matches);
        // Get the last index in the array
        $lastIndex = array_key_last($matches);

        // Iterate through the matches
        foreach ($matches as $index => $match) {
            $this->processMatch($route, $parameters, $matches, $index, $match, $lastIndex);
        }

        // Set the matches
        $route->setMatches($matches);

        return $route;
    }

    /**
     * @param Route $route      The route
     * @param array $parameters The parameters
     * @param array $matches    The matches
     * @param int   $index      The index for this match
     * @param mixed $match      The match
     * @param int   $lastIndex  The last key index
     *
     * @throws InvalidRouteParameter
     * @throws InvalidRoutePath
     *
     * @return void
     */
    protected function processMatch(Route $route, array $parameters, array &$matches, int $index, mixed $match, int $lastIndex): void
    {
        $parameter = $parameters[$index] ?? throw new InvalidRoutePath("No parameter for match key $index");

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

        if ($type = $parameter->getType()) {
            $matches[$index] = $this->getMatchValueForType($route, $parameter, $type, $index, $match);
        }
    }

    /**
     * Get a match value for the given cast type.
     *
     * @param Route     $route     The route
     * @param Parameter $parameter The parameter
     * @param CastType  $castType  The cast type
     * @param int       $index     The match index
     * @param mixed     $match     The match value
     *
     * @throws InvalidRouteParameter
     *
     * @return mixed
     */
    protected function getMatchValueForType(Route $route, Parameter $parameter, CastType $castType, int $index, mixed $match): mixed
    {
        return match ($castType) {
            CastType::string => (string) $match,
            CastType::bool   => (bool) $match,
            CastType::int    => (int) $match,
            CastType::float  => (float) $match,
            CastType::enum   => $this->getEnumMatchValue($parameter, $match),
            default          => $match,
        };
    }

    /**
     * Get an enum from a match value.
     *
     * @param Parameter $parameter The parameter
     * @param mixed     $match     The match value
     *
     * @throws InvalidRouteParameter
     *
     * @return BackedEnum
     */
    protected function getEnumMatchValue(Parameter $parameter, mixed $match): BackedEnum
    {
        /** @var class-string<BackedEnum> $enum */
        $enum = $parameter->getEnum();

        if ($enum && Cls::inherits($enum, BackedEnum::class)) {
            return $enum::from($match);
        }

        throw new InvalidRouteParameter("Missing enum class name for {$parameter->getName()}");
    }
}
