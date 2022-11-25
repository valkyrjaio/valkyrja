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

use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Enums\CastType;
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\Matcher as Contract;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Helpers;

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
        // Let's check if the route is set in the static routes
        if ($this->collection->hasStatic($path, $method)) {
            return $this->getMatchedStaticRoute($path, $method);
        }

        return null;
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRoutePath
     */
    public function matchDynamic(string $path, string $method = null): ?Route
    {
        // Attempt to find a match using dynamic routes that are set
        foreach ($this->collection->allDynamic($method) as $regex => $dynamicRoute) {
            // If the preg match is successful, we've found our route!
            /* @var array $matches */
            if (preg_match($regex, $path, $matches)) {
                return $this->getMatchedDynamicRoute($regex, $matches, $method);
            }
        }

        return null;
    }

    /**
     * Get a matched static route.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The request method
     *
     * @return Route
     */
    protected function getMatchedStaticRoute(string $path, string $method = null): Route
    {
        return clone $this->collection->getStatic($path, $method);
    }

    /**
     * Get a matched dynamic route.
     *
     * @param string      $path    The path
     * @param array       $matches The regex matches
     * @param string|null $method  [optional] The request method
     *
     * @throws InvalidRoutePath
     *
     * @return Route
     */
    protected function getMatchedDynamicRoute(string $path, array $matches, string $method = null): Route
    {
        // Clone the route to avoid changing the one set in the master array
        $dynamicRoute = clone $this->collection->getDynamic($path, $method);
        // Get the parameters
        $parameters = $dynamicRoute->getParameters();
        // The first match is the path itself
        array_shift($matches);

        // Iterate through the matches
        foreach ($matches as $key => $match) {
            $parameter = $parameters[$key] ?? throw new InvalidRoutePath("No parameter for match key $key");

            // If there is no match (middle of regex optional group)
            if (! $match) {
                // Set the value to the parameter default
                $matches[$key] = $parameter->getDefault();
            } elseif ($type = $parameter->getType()) {
                switch ($type) {
                    case CastType::string :
                        $matches[$key] = (string) $match;

                        break;
                    case CastType::bool :
                        $matches[$key] = (bool) $match;

                        break;
                    case CastType::int :
                        $matches[$key] = (int) $match;

                        break;
                    case CastType::float :
                        $matches[$key] = (float) $match;

                        break;
                }
            }
        }

        // Set the matches
        $dynamicRoute->setMatches($matches);

        return $dynamicRoute;
    }
}
