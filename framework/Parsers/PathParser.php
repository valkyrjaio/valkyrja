<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Parsers;

use Valkyrja\Contracts\Parsers\PathParser as PathParserContract;

/**
 * Class PathParser
 *
 * @package Valkyrja\Parsers
 *
 * @author  Melech Mizrachi
 */
class PathParser implements PathParserContract
{
    /**
     * The variable regex.
     *
     * @var string
     */
    protected const VARIABLE_REGEX = <<<'REGEX'
\{
    \s* ([a-zA-Z_][a-zA-Z0-9_-]*) \s*
    (?:
        : \s* ([^{}]*(?:\{(?-1)\}[^{}]*)*)
    )?
\}
REGEX;

    /**
     * Parse a path and get its parts.
     *
     * @param string $path The path
     *
     * @return array
     */
    public function parse(string $path): array
    {
        // Split on [ while skipping placeholders
        $segments = $this->getSegments($path);

        // The current path
        $current = '';

        // Iterate through the segments
        foreach ($segments as $key => $segment) {
            // If this is not the first segment then its an optional group
            if ($key > 0) {
                // Add a non capturing group around this segment
                $segment = '(?:' . $segment;
                // Replace any end brackets with the appropriate group close
                // NOTE: This will take care of missing end brackets in
                // previous groups because the only way that occurs
                // is when a group is nested within another
                $segment = str_replace(['*]', ']'], [')*?', ')?'], $segment);
            }
            // Otherwise it is the first segment
            else {
                // Check for any capture groups within <> or <*>
                // < > groups are normal capture groups
                // < *> groups are repeatable capture groups
                $segment = str_replace(['<', '*>', '>'], ['(?:', ')*', ')'], $segment);
            }

            $current .= $segment;
        }

        return $this->parsePath($current, $this->splitSegments($segments));
    }

    /**
     * Get a path's segments.
     *
     * @param string $path The path
     *
     * @return array
     */
    protected function getSegments(string $path): array
    {
        return preg_split(
            '~' . static::VARIABLE_REGEX . '(*SKIP)(*F) | \[~x',
            $path
        );
    }

    /**
     * Split segments based on ending bracket within the segments.
     *
     * @param array $segments The segments
     *
     * @return array
     */
    protected function splitSegments(array $segments): array
    {
        // The final segments to return
        $returnSegments = [];

        // Iterate through the segments once more
        foreach ($segments as $segment) {
            // If the segment has an ending bracket
            if (strpos($segment, ']') !== false) {
                // Split the segment on that bracket
                $parts = explode(']', $segment);

                // Iterate through the parts
                foreach ($parts as $part) {
                    if (! $part) {
                        continue;
                    }

                    // Setting each part individually
                    $returnSegments[] = $part;
                }

                continue;
            }

            // Otherwise set the segment normally
            $returnSegments[] = $segment;
        }

        return $returnSegments;
    }

    /**
     * Parse a path with no optionals.
     *
     * @param string $path     The path
     * @param array  $segments The segments
     *
     * @return array
     */
    protected function parsePath(string $path, array $segments): array
    {
        /** @var array[] $params */
        // Get all matches for {paramName} and {paramName:(validator)} in the path
        preg_match_all(
            '/' . static::VARIABLE_REGEX . '/x',
            $path,
            $params
        );
        $regex = $path;
        $paramsReturn = [];

        // Run through all matches
        foreach ($params[0] as $key => $param) {
            // Undo replacements made in parse foreach loop (see line 67)
            [$params[0][$key], $params[2][$key]] = str_replace(
                [')*?', ')?'],
                ['*]', ']'],
                [$params[0][$key], $params[2][$key]]
            );
            // Get the regex for this param
            $paramRegex = $this->getParamReplacement($key, $params);
            // Replace the matches with a regex
            $regex = str_replace($param, $paramRegex, $regex);

            // Set the param in the array of params to return
            $paramsReturn[$params[1][$key]] = [
                'replace' => $params[0][$key],
                'regex'   => $paramRegex,
            ];
        }

        $regex = str_replace('/', '\/', $regex);
        $regex = '/^' . $regex . '$/';

        return [
            'regex'    => $regex,
            'params'   => $paramsReturn,
            'segments' => $segments,
        ];
    }

    /**
     * Get a param's replacement.
     *
     * @param string $key    The key
     * @param array  $params The params
     *
     * @return string
     */
    protected function getParamReplacement(string $key, array $params): string
    {
        // TODO: Move to mapper to allow for customization
        // Check if a global regex alias was used
        switch ($params[2][$key]) {
            case 'num' :
                $replacement = '(\d+)';
                break;
            case 'slug' :
                $replacement = '([a-zA-Z0-9-]+)';
                break;
            case 'alpha' :
                $replacement = '([a-zA-Z]+)';
                break;
            case 'alpha-lowercase' :
                $replacement = '([a-z]+)';
                break;
            case 'alpha-uppercase' :
                $replacement = '([A-Z]+)';
                break;
            case 'alpha-num' :
                $replacement = '([a-zA-Z0-9]+)';
                break;
            case 'alpha-num-underscore' :
                $replacement = '(\w+)';
                break;
            default :
                $regex = ($params[2][$key] ?: $params[1][$key]);

                // Check if a regex was set for this match, otherwise use a wildcard all
                $replacement = '(' . $regex . ')';
                break;
        }

        return $replacement;
    }
}
