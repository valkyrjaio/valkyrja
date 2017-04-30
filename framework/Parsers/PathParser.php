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
use Valkyrja\Parser\Exceptions\InvalidOptionalPart;
use Valkyrja\Parser\Exceptions\OptionalSegmentsMismatch;
use Valkyrja\Parser\Exceptions\OptionalSegmentsMisplaced;
use Valkyrja\Parser\Exceptions\RegexRequired;

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
        : \s* 
        (
            [
                ^{}]*
                (?:
                \{(?-1)\}
                [^{}
            ]*)
        *)
    )?
\}
REGEX;

    /**
     * Parse a path and get its parts.
     *
     * @param string $path The path
     *
     * @return array
     *
     * @throws \Valkyrja\Parser\Exceptions\InvalidOptionalPart
     * @throws \Valkyrja\Parser\Exceptions\OptionalSegmentsMismatch
     * @throws \Valkyrja\Parser\Exceptions\OptionalSegmentsMisplaced
     * @throws \Valkyrja\Parser\Exceptions\RegexRequired
     */
    public function parse(string $path): array
    {
        // Split on [ while skipping placeholders
        $segments = $this->getSegments($path);

        // Verify the path and its segments
        $this->verifySegments($path, $segments);

        // The current path
        $current = '';
        // The paths built
        $paths = [];

        // Iterate through the segments
        foreach ($segments as $n => $segment) {
            // If the segment is empty and not the first segment
            if ($segment === '' && $n !== 0) {
                // Throw an exception
                throw new InvalidOptionalPart('Empty optional part');
            }

            // Build the path and the current path with the segment
            // This ensures all subsequent segments have the
            // previous parts of the path
            $current .= $segment;
            // Set the parsed results in the list to return
            $paths[] = $this->parsePath($current);
        }

        return $paths;
    }

    /**
     * Get the path without closing optional brackets.
     *
     * @param string $path The path
     *
     * @return string
     */
    protected function noClosingOptionals(string $path): string
    {
        return rtrim($path, ']');
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
            $this->noClosingOptionals($path)
        );
    }

    /**
     * Get the number of optional parts in the path.
     *
     * @param string $path The path
     *
     * @return int
     */
    protected function countOptionals(string $path): int
    {
        return strlen($path) - strlen(rtrim($path, ']'));
    }

    /**
     * Verify the path's segments.
     *
     * @param string $path     The path
     * @param array  $segments The split segments
     *
     * @return void
     *
     * @throws \Valkyrja\Parser\Exceptions\OptionalSegmentsMismatch
     * @throws \Valkyrja\Parser\Exceptions\OptionalSegmentsMisplaced
     */
    protected function verifySegments(string $path, array $segments): void
    {
        // If the total optional count does not match the segments
        if ($this->countOptionals($path) !== count($segments) - 1) {
            // If there are any ] in the middle of the route, throw a more specific error message
            if (preg_match('~' . static::VARIABLE_REGEX . '(*SKIP)(*F) | \]~x', $this->noClosingOptionals($path))) {
                throw new OptionalSegmentsMisplaced('Optional segments can only occur at the end of a path');
            }

            throw new OptionalSegmentsMismatch('Number of opening \'[\' and closing \']\' does not match');
        }
    }

    /**
     * Parse a path with no optionals.
     *
     * @param string $path The path
     *
     * @return array
     *
     * @throws \Valkyrja\Parser\Exceptions\RegexRequired
     */
    protected function parsePath(string $path): array
    {
        // Get all matches for {paramName} and {paramName:(validator)} in the path
        preg_match_all(
            '/' . static::VARIABLE_REGEX . '/x',
            $path,
            $params
        );
        /** @var array[] $params */

        // Run through all matches
        foreach ($params[0] as $key => $param) {
            // Replace the matches with a regex
            $path = str_replace($param, $this->getParamReplacement($key, $params), $path);
        }

        $path = str_replace('/', '\/', $path);
        $path = '/^' . $path . '$/';

        return [
            'regex'  => $path,
            'params' => $params,
        ];
    }

    /**
     * Get a param's replacement.
     *
     * @param string $key    The key
     * @param array  $params The params
     *
     * @return string
     *
     * @throws \Valkyrja\Parser\Exceptions\RegexRequired
     */
    protected function getParamReplacement(string $key, array $params): string
    {
        // If there is no regex for this param
        if (! isset($params[2][$key]) || ! $params[2][$key]) {
            // Throw an error
            throw new RegexRequired('Regex, or regex alias, is required');
        }

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
                // Check if a regex was set for this match, otherwise use a wildcard all
                $replacement = '(' . $params[2][$key] . ')';
                break;
        }

        return $replacement;
    }
}
