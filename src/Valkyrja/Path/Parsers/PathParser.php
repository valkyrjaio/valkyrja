<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Path\Parsers;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Path\PathParser as ParserContract;
use Valkyrja\Support\Providers\Provides;

/**
 * Class PathParser.
 *
 * @author Melech Mizrachi
 */
class PathParser implements ParserContract
{
    use Provides;

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
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ParserContract::class,
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
        $app->container()->setSingleton(ParserContract::class, new static());
    }

    /**
     * Parse a path and get its parts.
     *
     * @param string $path The path
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    public function parse(string $path): array
    {
        // Validate the path for opening and closing tags
        $this->validatePath($path);

        // Split on [ while skipping placeholders
        $segments = $this->getSegments($path);

        // The current path
        $current = '';

        // Iterate through the segments
        foreach ($segments as $key => $segment) {
            // If this is not the first segment then its an optional group
            if ($key > 0) {
                // Add a non capturing group around this segment
                // NOTE: Since the path was originally split into segments on [
                // it is safe to do this as there SHOULD be a closing bracket
                // before another required group and so there shouldn't
                // be any conflicts in mismatching opening  and closing
                // regex non-capture groups. This assumes the
                // developer did their job correctly in
                // the amount of opening required
                // and optional groups
                $segment = '(?:' . $segment;
            }

            // Check for any non-capturing groups within <> or <*>
            // < > groups are normal non-capturing groups
            // < *> groups are repeatable non-capturing groups
            // NOTE: have to use an alias to avoid breaking @Annotations() usage
            // Replace any end brackets with the appropriate group close
            // NOTE: This will take care of missing end brackets in
            // previous groups because the only way that occurs
            // is when a group is nested within another
            $segment = str_replace(
                [
                    // Opening non-capture required group
                    '<',
                    // Close non-capture repeatable required group
                    '*>',
                    // Close non-capture required group
                    '>',
                    // Close non-capture repeatable optional group
                    '*]',
                    // Close non-capture optional group
                    ']',
                ],
                [
                    // Non-capture required group regex open
                    '(?:',
                    // Non-capture required repeatable group regex close
                    ')*',
                    // Non-capture required group regex close
                    ')',
                    // Non-capture repeatable optional group regex close
                    ')*?',
                    // Non-capture optional group regex close
                    ')?',
                ],
                $segment
            );

            $current .= $segment;
        }

        return $this->parsePath($current, $segments);
    }

    /**
     * Validate a path's opening and closing tags.
     *
     * @param string $path The path
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function validatePath(string $path): void
    {
        // The number of opening required non-capture groups <
        $requiredGroupsOpen = substr_count($path, '<');
        // The number of closing required non-capture groups >
        $requiredGroupsClose = substr_count($path, '>');
        // The number of opening optional non-capture groups [
        $optionalGroupsOpen = substr_count($path, '[');
        // The number of closing optional non-capture groups ]
        $optionalGroupsClose = substr_count($path, ']');

        // If the count of required opening and closing tags doesn't match
        if ($requiredGroupsOpen !== $requiredGroupsClose) {
            // Throw an error letting the develop know they made a bad path
            throw new InvalidArgumentException('Mismatch of required groups for path: ' . $path);
        }

        // If the count of optional opening and closing tags doesn't match
        if ($optionalGroupsOpen !== $optionalGroupsClose) {
            // Throw an error letting the develop know they made a bad path
            throw new InvalidArgumentException('Mismatch of optional groups for path: ' . $path);
        }
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
        return preg_split('~' . static::VARIABLE_REGEX . '(*SKIP)(*F) | \[~x', $path);
    }

    /**
     * Parse a path with no optionals.
     *
     * @param string $path     The path
     * @param array  $segments The segments
     *
     * @return array
     */
    protected function parsePath(string $path, array &$segments): array
    {
        /* @var array[] $params */
        // Get all matches for {paramName} and {paramName:regex} in the path
        preg_match_all('/' . static::VARIABLE_REGEX . '/x', $path, $params);

        $regex        = $path;
        $paramsReturn = [];

        // Run through all matches
        foreach ($params[0] as $key => $param) {
            // Undo replacements made in parse foreach loop (see line 85)
            [$params[0][$key], $params[2][$key]] = str_replace(
                [')*?', ')?'],
                ['*]', ']'],
                [$params[0][$key], $params[2][$key]]
            );
            // Get the regex for this param
            $paramRegex = $this->getParamReplacement($key, $params);
            // Replace the matches with a regex
            $regex = str_replace($param, $paramRegex, $regex);
            // What to replace in the segment for this item
            $replace = '{' . $params[1][$key] . '}';

            // Set the param in the array of params to return
            $paramsReturn[$params[1][$key]] = [
                'replace' => $replace,
                'regex'   => $paramRegex,
            ];

            // Iterate through the segments
            foreach ($segments as $segmentKey => $segment) {
                // Replace this match with the replace text thus removing any regex
                // in the segment this fixes any regex with brackets from being
                // messed up in the splitSegments() method
                $segments[$segmentKey] = str_replace($params[0][$key], $replace, $segment);
            }
        }

        $regex = str_replace('/', '\/', $regex);
        $regex = '/^' . $regex . '$/';

        $segmentsReturn = $this->splitSegments($segments, ']');
        $segmentsReturn = $this->splitSegments($segmentsReturn, '<');
        $segmentsReturn = $this->splitSegments($segmentsReturn, '>');

        return [
            'regex'    => $regex,
            'params'   => $paramsReturn,
            'segments' => $segmentsReturn,
        ];
    }

    /**
     * Get a param's replacement.
     *
     * @param int   $key    The key
     * @param array $params The params
     *
     * @return string
     */
    protected function getParamReplacement(int $key, array $params): string
    {
        return config()[ConfigKeyPart::PATH][ConfigKeyPart::PATTERNS][$params[2][$key]]
            ?? ('(' . ($params[2][$key] ?: $params[1][$key]) . ')');
    }

    /**
     * Split segments based on ending bracket within the segments.
     *
     * @param array  $segments    The segments
     * @param string $deliminator The deliminator
     *
     * @return array
     */
    protected function splitSegments(array $segments, string $deliminator): array
    {
        // The final segments to return
        $returnSegments = [];

        // Iterate through the segments once more
        foreach ($segments as $segment) {
            // If the segment has the deliminator
            if (strpos($segment, $deliminator) !== false) {
                $this->splitSegmentsDeliminator($returnSegments, $segment, $deliminator);

                continue;
            }

            // Otherwise set the segment normally
            $returnSegments[] = $segment;
        }

        return $returnSegments;
    }

    /**
     * Split a segment by deliminator (recursive).
     *
     * @param array  $segments    The segments
     * @param string $segment     The segment
     * @param string $deliminator The deliminator
     *
     * @return void
     */
    protected function splitSegmentsDeliminator(array &$segments, string $segment, string $deliminator): void
    {
        // Split the segment on that bracket
        // Iterate through the parts
        foreach (explode($deliminator, $segment) as $part) {
            if (! $part) {
                continue;
            }

            // If the segment has the deliminator
            if (strpos($part, $deliminator) !== false) {
                $this->splitSegmentsDeliminator($segments, $part, $deliminator);

                continue;
            }

            // Setting each part individually
            $segments[] = $part;
        }
    }
}
