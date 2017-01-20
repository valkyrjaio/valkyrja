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

/**
 * Class Parser
 *
 * @package Valkyrja\Routing\Annotations
 *
 * @author  Melech Mizrachi
 */
class Parser
{
    /**
     * Get route annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @return array
     */
    public function getRouteAnnotations(string $docString): array
    {
        $regex = <<<'REGEX'
    @Route\( 
        \s* 
            ([a-zA-Z0-9\_\-\\\/\:\,\=\'\"\{\}\(\)\+\[\]\s]*)
        \s* 
    \)
REGEX;

        // Get all matches of @Route()
        preg_match_all('/' . $regex . '/x', $docString, $matches);

        // Create a new array to return matches
        $annotations = [];

        // If there are matches
        if ($matches && $matches[0]) {
            $parsedMatch = [];

            // Iterate through the matches individually
            foreach ($matches[1] as $match) {
                $match = trim($match);
                // Explode the string by comma
                $match = explode(',', $match);

                // Iterate through the exploded array
                foreach ($match as $item) {
                    $item = trim($item);
                    // Explode the values by equal sign
                    $items = explode('=', $item);

                    // Iterate through each key value pair
                    foreach ($items as $key => $subItem) {
                        $subItem = trim($subItem);
                        $subItemExplode = explode('::', $subItem);

                        // If this is a class with a constant
                        if (strpos($subItem, '::') && class_exists($subItemExplode[0])) {
                            $subItem = constant("$subItemExplode[0]::$subItemExplode[1]");
                        }

                        // Trim the values and replace any quotes
                        $items[$key] = str_replace(['\'', '\"'], '', trim($subItem));
                    }

                    // Set the first item as the key and second item as the value
                    $parsedMatch[$items[0]] = $items[1];
                }

                // Set this as a new Route in the annotations array
                $annotations[] = new Route($parsedMatch);
            }
        }

        return $annotations;
    }
}