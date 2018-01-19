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
use Valkyrja\Application;
use Valkyrja\Support\Providers\Provides;

/**
 * Class PathGenerator.
 *
 * @author Melech Mizrachi
 */
class NativePathGenerator implements PathGenerator
{
    use Provides;

    /**
     * Parse segments, data, and params into a path.
     *
     * @param array $segments The segments
     * @param array $data     [optional] The data
     * @param array $params   [optional] The params
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function parse(array $segments, array $data = null, array $params = null): string
    {
        // If data was passed but no params
        if (null === $params && null !== $data) {
            throw new InvalidArgumentException(
                'Route params are required when supplying data'
            );
        }

        $path         = '';
        $replace      = [];
        $replacements = [];

        // If there is data, parse the replacements
        if (null !== $data) {
            $this->parseData(
                $segments,
                $data,
                $params,
                $replace,
                $replacements
            );
        }

        // Iterate through the segments
        foreach ($segments as $index => $segment) {
            // No need to do replacements if there was no data
            if (null !== $data) {
                // Replace any parameters
                $segment = str_replace($replace, $replacements, $segment);
            }

            // If parameters were replaced or none to begin with
            if (strpos($segment, '{') === false) {
                // Append this segment
                $path .= $segment;
            }
        }

        return $path;
    }

    /**
     * Parse data for replacements.
     *
     * @param array $segments     The segments
     * @param array $data         The data
     * @param array $params       The params
     * @param array $replace      The replace array
     * @param array $replacements The replacements array
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function parseData(
        array $segments,
        array $data,
        array $params,
        array &$replace,
        array &$replacements
    ): void {
        // Iterate through all the data properties
        foreach ($data as $key => $datum) {
            // If the data isn't found in the params array it is not a valid
            // param
            if (! isset($params[$key])) {
                throw new InvalidArgumentException(
                    "Invalid route param '{$key}'"
                );
            }

            $regex = $params[$key]['regex'];

            $this->validateDatum($key, $datum, $regex);

            if (is_array($datum)) {
                // Get the segment by the param key and replace the {key}
                // within it to get the repeatable portion of the segment
                $segment     = $this->findParamSegment(
                    $segments,
                    $params[$key]['replace']
                );
                $deliminator = str_replace(
                    $params[$key]['replace'] . '*',
                    '',
                    $segment
                );

                // Set what to replace
                $replace[] = $params[$key]['replace'] . '*';
                // With the data value to replace with
                $replacements[] = implode($deliminator, $datum);

                continue;
            }

            // Set what to replace
            $replace[] = $params[$key]['replace'];
            // With the data value to replace with
            $replacements[] = $datum;
        }
    }

    /**
     * Validate a datum.
     *
     * @param string $key   The key
     * @param mixed  $datum The datum
     * @param string $regex The regex
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function validateDatum(string $key, $datum, string $regex): void
    {
        if (is_array($datum)) {
            foreach ($datum as $datumItem) {
                $this->validateDatum($key, $datumItem, $regex);
            }

            return;
        }

        // If the value of the data doesn't match what was specified when the route was made
        if (preg_match('/^' . $regex . '$/', $datum) === 0) {
            throw new InvalidArgumentException(
                "Route param for {$key}, '{$datum}', does not match {$regex}"
            );
        }
    }

    /**
     * Find a segment with a param key.
     *
     * @param array  $segments
     * @param string $param
     *
     * @return string
     */
    protected function findParamSegment(array $segments, string $param): ? string
    {
        $segment = null;

        foreach ($segments as $segment) {
            if (strpos($segment, $param) !== false) {
                return $segment;
            }
        }

        return $segment;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            PathGenerator::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            PathGenerator::class,
            new static()
        );
    }
}
