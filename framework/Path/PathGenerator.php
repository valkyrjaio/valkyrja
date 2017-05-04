<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Path;

use InvalidArgumentException;

use Valkyrja\Contracts\Path\PathGenerator as PathGeneratorContract;

/**
 * Class PathGenerator
 *
 * @package Valkyrja\Path
 *
 * @author  Melech Mizrachi
 */
class PathGenerator implements PathGeneratorContract
{
    /**
     * Parse segments, data, and params into a path.
     *
     * @param array $segments The segments
     * @param array $data     [optional] The data
     * @param array $params   [optional] The params
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function parse(array $segments, array $data = null, array $params = null): string
    {
        // If data was not passed in
        if (null === $data) {
            return $segments[0];
        }

        // If data was passed but no params
        if (null === $params && null !== $data) {
            throw new InvalidArgumentException('Route params are required when supplying data');
        }

        $path = '';
        $replace = [];
        $replacement = [];

        // Iterate through all the data properties for the route
        foreach ($data as $key => $datum) {
            // If the data isn't found in the params array it is not a valid param
            if (! isset($params[$key])) {
                throw new InvalidArgumentException("Invalid route param '{$key}' with value '{$datum}'");
            }

            $regex = $params[$key]['regex'];

            // If the value of the data doesn't match what was specified when the route was made
            if (preg_match('/^' . $regex . '$/', $datum) === 0) {
                throw new InvalidArgumentException("Route param for {$key}, '{$datum}', does not match {$regex}");
            }

            // Set what to replace
            $replace[] = $params[$key]['replace'];
            // With the data value to replace with
            $replacement[] = $datum;
        }

        // Iterate through the segments
        foreach ($segments as $index => $segment) {
            // Replace any parameters
            $segment = str_replace($replace, $replacement, $segment);

            // If parameters were replaced or none to begin with
            if (strpos($segment, '{') === false) {
                // Append this segment
                $path .= $segment;
            }
        }

        return $path;
    }
}
