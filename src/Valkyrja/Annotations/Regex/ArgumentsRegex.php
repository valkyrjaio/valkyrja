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
interface ArgumentsRegex
{
    /**
     * Route argument regex.
     *
     * @constant string
     *
     * @description
     *
     * ([a-zA-Z0-9_\\\:]*)      Match any lowercase,uppercase, numerical, and
     *                              underscored word
     * \s*                      Followed by any whitespace
     * \=                       Followed by an equal sign
     * \s*                      Followed by any whitespace
     * \'? "?                   Followed by an optional single or double quote
     * \s*                      Followed by any whitespace
     * (                        Begin capture
     *      [                       Begin any of following:
     *          a-z                     Lowercase character
     *          A-Z                     Uppercase character
     *          0-9                     Digit character
     *          \_                      Underscore
     *          \-                      Hyphen
     *          \\                      Backslash (for classes)
     *          \/                      Forward slash (for paths)
     *          \:                      Colon (for class constants)
     *          \{                      Opening curly brace (for params in
     *                                      paths)
     *          \}                      Closing curly brace (for params in
     *                                      paths)
     *          \(                      Opening parenthesis (for param regex
     *                                      in paths)
     *          \)                      Closing parenthesis (for param regex
     *                                      in paths)
     *          \+                      Addition sign (for param regex in paths)
     *          \[                      Opening bracket (for param regex in
     *                                      paths)
     *          \]                      Closing bracket (for param regex in
     *                                      paths)
     *          \.                      Period (for param regex in paths and
     *                                      dot notation on route name)
     *          \<                      Argument Group Open (replaced with
     *                                      `(?:')
     *          \>                      Argument Group Close (replaced with ')')
     *          \|                      Pipe or for regex
     *          \s                      Any whitespace (for sentences)
     *      ]*                       Allow any number of above
     * )                          End capture
     */
    public const ARGUMENTS_REGEX = <<<'REGEX'
    ([a-zA-Z0-9_\\\:]*) 
    \s* \= \s* \'? "? \s*
    ([a-zA-Z0-9\_\-\\\/\:\{\}\(\)\+\[\]\.\<\>\|\s]*)
REGEX;
}
