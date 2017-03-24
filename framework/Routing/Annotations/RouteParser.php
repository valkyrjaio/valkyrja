<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotations;

use Valkyrja\Contracts\Routing\Annotations\RouteParser as RouteParserContract;
use Valkyrja\Http\RequestMethod;
use Valkyrja\Routing\Models\Route;

/**
 * Class Parser
 *
 * @package Valkyrja\Routing\Annotations
 *
 * @author  Melech Mizrachi
 */
class RouteParser implements RouteParserContract
{
    /**
     * Get route annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @return \Valkyrja\Routing\Models\Route[]
     */
    public function getRouteAnnotations(string $docString): array
    {
        // Get all matches of @Route()
        preg_match_all('/' . static::ROUTE_REGEX . '/x', $docString, $routes);

        // Create a new array to return matches
        $annotations = [];

        // If routes were found
        if ($routes && $routes[0]) {
            // Iterate through the routes' contents found within the parenthesis
            foreach ($routes[1] as $route) {
                // Match all the arguments (I.E. path = '/')
                preg_match_all('/' . static::ARGUMENTS_REGEX . '/x', $route, $arguments);
                // Properties from the route match
                $properties = [];

                // Iterate through the matched arguments
                foreach ($arguments[2] as $index => $argument) {
                    // Determine if the argument is a constant
                    if (defined($argument)) {
                        $argument = constant($argument);
                    }

                    // Set the argument value to the argument index
                    $properties[$arguments[1][$index]] = $argument;
                }

                // Set this as a new Route in the annotations array
                $route = new Route();

                // Set the method
                $route->setMethod($properties['method'] ?? RequestMethod::GET);

                // Set the path if it exists
                if (isset($properties['path'])) {
                    $route->setPath($properties['path']);
                }

                // Set the name if it exists
                if (isset($properties['name'])) {
                    $route->setName($properties['name']);
                }

                // Set the dynamic if it exists
                if (isset($properties['dynamic'])) {
                    $route->setDynamic($properties['dynamic']);
                }

                // Set the route within the annotations array to return
                $annotations[] = $route;
            }
        }

        return $annotations;
    }
}