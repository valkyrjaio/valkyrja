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

namespace Valkyrja\Http\Routing\Processor;

use Override;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Contract\Parameter;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Http\Routing\Processor\Contract\Processor as Contract;
use Valkyrja\Http\Routing\Support\Helpers;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRoutePathException;

/**
 * Class Processor.
 *
 * @author Melech Mizrachi
 */
class Processor implements Contract
{
    /**
     * Process a route.
     *
     * @param Route $route The route
     *
     * @throws InvalidRoutePathException
     *
     * @return Route
     */
    #[Override]
    public function route(Route $route): Route
    {
        // Set the path to the validated cleaned path (/some/path)
        $route = $route->withPath(Helpers::trimPath($route->getPath()));

        // If this is a dynamic route
        if (str_contains($route->getPath(), '{')) {
            $route = $this->modifyRegex($route);
        }

        return $route;
    }

    /**
     * Create the regex for a route.
     *
     * @param Route $route The route
     *
     * @throws InvalidRoutePathException
     *
     * @return Route
     */
    protected function modifyRegex(Route $route): Route
    {
        // If the regex has already been set then don't do anything
        if ($route->getRegex() !== null) {
            return $route;
        }

        // Replace all slashes with \/
        $regex = str_replace('/', Regex::PATH, $route->getPath());

        // Iterate through the route's parameters
        foreach ($route->getParameters() as $parameter) {
            // Validate the parameter
            $parameter = $this->processParameterInRegex($parameter, $regex);

            $regex = $this->replaceParameterNameInRegex($route, $parameter, $regex);
        }

        $regex = Regex::START . $regex . Regex::END;

        return $route->withRegex($regex);
    }

    /**
     * Validate the parameter name exists in the regex.
     *
     * @param Parameter $parameter The parameter
     * @param string    $regex     The regex
     *
     * @return Parameter
     */
    protected function processParameterInRegex(Parameter $parameter, string $regex): Parameter
    {
        // If the parameter is optional or the name has a ? affixed to it
        if ($parameter->isOptional() || str_contains($regex, $parameter->getName() . '?')) {
            // Ensure the parameter is set to optional
            return $parameter->withIsOptional(true);
        }

        return $parameter;
    }

    /**
     * Replace the parameter name in the route's regex.
     *
     * @param Route     $route     The route
     * @param Parameter $parameter The parameter
     * @param string    $regex     The regex
     *
     * @throws InvalidRoutePathException
     *
     * @return string
     */
    protected function replaceParameterNameInRegex(Route $route, Parameter $parameter, string $regex): string
    {
        // Get whether this parameter is optional
        $isOptional = $parameter->isOptional();

        // Get the replacement for this parameter's name (something like {name} or {name?}
        // Prepend \/ if it optional so we can replace the path slash and set it in the
        // regex below as a non-capture-optional group
        $nameReplacement = ($isOptional ? Regex::PATH : '')
            . '{' . $parameter->getName() . ($isOptional ? '?' : '') . '}';

        // Check if the path doesn't contain the parameter's name replacement
        if (! str_contains($regex, $nameReplacement)) {
            throw new InvalidRoutePathException("{$route->getPath()} is missing $nameReplacement");
        }

        // If optional we don't want to capture the / before the value
        $parameterRegex = ($isOptional ? Regex::START_OPTIONAL_CAPTURE_GROUP : '')
            // Start the actual value's capture group
            . (! $parameter->shouldCapture() ? Regex::START_NON_CAPTURE_GROUP : Regex::START_CAPTURE_GROUP)
            // Add the parameter name only for a capture group (non capture groups won't be captured so we don't need them to be attributed to a param name)
            . ($parameter->shouldCapture() ? Regex::START_CAPTURE_GROUP_NAME . $parameter->getName() . Regex::END_CAPTURE_GROUP_NAME : '')
            // Set the parameter's regex to match the value
            . $parameter->getRegex()
            // End the capture group
            . ($isOptional ? Regex::END_OPTIONAL_CAPTURE_GROUP : Regex::END_CAPTURE_GROUP);

        // Replace the {name} or \/{name?} with the finished regex
        return str_replace($nameReplacement, $parameterRegex, $regex);
    }
}
