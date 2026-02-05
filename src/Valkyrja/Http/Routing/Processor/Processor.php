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
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Support\Helpers;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidRoutePathException;

class Processor implements ProcessorContract
{
    /**
     * Process a route.
     *
     * @param RouteContract $route The route
     *
     * @throws InvalidRoutePathException
     */
    #[Override]
    public function route(RouteContract $route): RouteContract
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
     * @param RouteContract $route The route
     *
     * @throws InvalidRoutePathException
     */
    protected function modifyRegex(RouteContract $route): RouteContract
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
     * @param ParameterContract $parameter The parameter
     * @param string            $regex     The regex
     */
    protected function processParameterInRegex(ParameterContract $parameter, string $regex): ParameterContract
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
     * @param RouteContract     $route     The route
     * @param ParameterContract $parameter The parameter
     * @param string            $regex     The regex
     *
     * @throws InvalidRoutePathException
     */
    protected function replaceParameterNameInRegex(RouteContract $route, ParameterContract $parameter, string $regex): string
    {
        // Get the replacement for this parameter's name (something like {name} or {name?}
        // Prepend \/ if it optional so we can replace the path slash and set it in the
        // regex below as a non-capture-optional group
        $nameReplacement = $this->getRegexParameterNameReplacement($parameter);

        // Check if the path doesn't contain the parameter's name replacement
        if (! str_contains($regex, $nameReplacement)) {
            throw new InvalidRoutePathException("{$route->getPath()} is missing $nameReplacement");
        }

        // Get the parameter's regex
        $parameterRegex = $this->getParameterRegex($parameter);

        // Replace the {name} or \/{name?} with the finished regex
        return str_replace($nameReplacement, $parameterRegex, $regex);
    }

    /**
     * Get the regex parameter name replacement.
     *
     * @return non-empty-string
     */
    protected function getRegexParameterNameReplacement(ParameterContract $parameter): string
    {
        // Get whether this parameter is optional
        $isOptional = $parameter->isOptional();

        // Get the replacement for this parameter's name (something like {name} or {name?}
        // Prepend \/ if it optional so we can replace the path slash and set it in the
        // regex below as a non-capture-optional group
        return ($isOptional ? Regex::PATH : '')
            . '{' . $parameter->getName() . ($isOptional ? '?' : '') . '}';
    }

    /**
     * Get a parameter's regex.
     *
     * @return non-empty-string
     */
    protected function getParameterRegex(ParameterContract $parameter): string
    {
        return $this->getParameterRegexOptionalCaptureGroupStart($parameter)
            . $this->getParameterRegexCaptureGroupStart($parameter)
            . $this->getParameterRegexNameCaptureGroup($parameter)
            // Set the parameter's regex to match the value
            . $parameter->getRegex()
            . $this->getParameterRegexCaptureGroupEnd($parameter);
    }

    /**
     * Get a parameter's regex optional capture group start.
     */
    protected function getParameterRegexOptionalCaptureGroupStart(ParameterContract $parameter): string
    {
        // If optional we don't want to capture the / before the value
        return $parameter->isOptional()
            ? Regex::START_OPTIONAL_CAPTURE_GROUP
            : '';
    }

    /**
     * Get a parameter's regex capture group start.
     */
    protected function getParameterRegexCaptureGroupStart(ParameterContract $parameter): string
    {
        // Start the actual value's capture group
        return ! $parameter->shouldCapture()
            ? Regex::START_NON_CAPTURE_GROUP
            : Regex::START_CAPTURE_GROUP;
    }

    /**
     * Get a parameter's regex name capture group.
     */
    protected function getParameterRegexNameCaptureGroup(ParameterContract $parameter): string
    {
        // Add the parameter name only for a capture group (non capture groups won't be captured so we don't need them to be attributed to a param name)
        return $parameter->shouldCapture()
            ? Regex::START_CAPTURE_GROUP_NAME . $parameter->getName() . Regex::END_CAPTURE_GROUP_NAME
            : '';
    }

    /**
     * Get a parameter's regex capture group end.
     */
    protected function getParameterRegexCaptureGroupEnd(ParameterContract $parameter): string
    {
        // End the capture group
        return $parameter->isOptional()
            ? Regex::END_OPTIONAL_CAPTURE_GROUP
            : Regex::END_CAPTURE_GROUP;
    }
}
