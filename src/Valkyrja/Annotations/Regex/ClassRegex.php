<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotations\Regex;

/**
 * Interface ArgumentsRegex.
 *
 * @author Melech Mizrachi
 */
interface ClassRegex
{
    /**
     * Class regex.
     *
     * @constant string
     * @description
     * \(                    Match an opening parenthesis
     *      \s*                 Followed by any whitespace
     *      (                       Begin capture
     *          [                       Begin any of following:
     *              a-z                 Lowercase character
     *              A-Z                 Uppercase character
     *              0-9                 Digit character
     *              \_                  Underscore
     *              \-                  Hyphen
     *              \\                  Backslash (for classes)
     *              \/                  Forward slash (for paths)
     *              \:                  Colon (for class constants)
     *              \{                  Opening curly brace (for params in
     *                                      paths)
     *              \}                  Closing curly brace (for params in
     *                                      paths)
     *              \(                  Opening parenthesis (for param regex
     *                                      in paths)
     *              \)                  Closing parenthesis (for param regex
     *                                      in paths)
     *              \+                  Addition sign (for param regex in paths)
     *              \[                  Opening bracket (for param regex in
     *                                      paths)
     *              \]                  Closing bracket (for param regex in
     *                                      paths)
     *              \.                  Period (for param regex in paths and
     *                                      dot notation on route name)
     *              \=                  Equal sign (for argument value/index
     *                                      separation)
     *              \,                  Comma (for argument separation)
     *              \'                  Single quote (for argument value
     *                                      enclosure)
     *              \"                  Double quote (for argument value
     *                                      enclosure)
     *              \*                  Asterisk (for multiline comments phpDoc)
     *              \<                  Argument Group Open (replaced with
     *                                      '(?:')
     *              \>                  Argument Group Close (replaced with ')')
     *              \|                  Pipe or for regex
     *              \s                  Whitespace
     *          ]*                  Allow any number of above
     *      )                   End capture
     *      \s*                 Followed by any whitespace
     * )                    Ending parenthesis for the
     * annotation
     */
    public const CLASS_REGEX = <<<'REGEX'
    \( 
        \s* 
            ([a-zA-Z0-9\_\-\\\/\:\{\}\(\)\+\[\]\.\=\,\'\"\*\<\>\|\s]*)
        \s* 
    \)
REGEX;
}
