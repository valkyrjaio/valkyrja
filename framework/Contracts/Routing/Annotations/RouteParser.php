<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Routing\Annotations;

use Valkyrja\Contracts\Annotations\Parser\AnnotationsParser;

/**
 * Interface RouteParser
 *
 * @package Valkyrja\Contracts\Routing\Annotations
 *
 * @author  Melech Mizrachi
 */
interface RouteParser extends AnnotationsParser
{
    /**
     * Route regex.
     *
     * @constant string
     *
     * @description
     *
     * @Route\(                                 Match the @Route annotation followed by an opening parenthesis
     *      \s*                                      Followed by any whitespace
     *      (                                        Begin capture
     *          [                                       Begin any of following:
     *              a-z                                     Lowercase character
     *              A-Z                                     Uppercase character
     *              0-9                                     Digit character
     *              \_                                      Underscore
     *              \-                                      Hyphen
     *              \\                                      Backslash (for classes)
     *              \/                                      Forward slash (for paths)
     *              \:                                      Colon (for class constants)
     *              \{                                      Opening curly brace (for params in paths)
     *              \}                                      Closing curly brace (for params in paths)
     *              \(                                      Opening parenthesis (for param regex in paths)
     *              \)                                      Closing parenthesis (for param regex in paths)
     *              \+                                      Addition sign (for param regex in paths)
     *              \[                                      Opening bracket (for param regex in paths)
     *              \]                                      Closing bracket (for param regex in paths)
     *              \.                                      Period (for param regex in paths and dot notation on route name)
     *              \=                                      Equal sign (for argument value/index separation)
     *              \,                                      Comma (for argument separation)
     *              \'                                      Single quote (for argument value enclosure)
     *              \"                                      Double quote (for argument value enclosure)
     *              \*                                      Asterisk (for multiline comments phpDoc)
     *              \s                                      Whitespace
     *          ]*                                      Allow any number of above
     *      )                                        End capture
     *      \s*                                      Followed by any whitespace
     * )                                        Ending parenthesis for @Route annotation
     */
    public const ROUTE_REGEX = <<<'REGEX'
    @Route\( 
        \s* 
            ([a-zA-Z0-9\_\-\\\/\:\{\}\(\)\+\[\]\.\=\,\'\"\*\s]*)
        \s* 
    \)
REGEX;

    /**
     * Route argument regex.
     *
     * @constant string
     *
     * @description
     *
     * ([a-zA-Z_]*)                             Match any lowercase, uppercase, and underscored word
     * \s*                                      Followed by any whitespace
     * \=                                       Followed by an equal sign
     * \s*                                      Followed by any whitespace
     * \'? "?                                   Followed by an optional single or double quote
     * \s*                                      Followed by any whitespace
     * (                                        Begin capture
     *      [                                       Begin any of following:
     *          a-z                                     Lowercase character
     *          A-Z                                     Uppercase character
     *          0-9                                     Digit character
     *          \_                                      Underscore
     *          \-                                      Hyphen
     *          \\                                      Backslash (for classes)
     *          \/                                      Forward slash (for paths)
     *          \:                                      Colon (for class constants)
     *          \{                                      Opening curly brace (for params in paths)
     *          \}                                      Closing curly brace (for params in paths)
     *          \(                                      Opening parenthesis (for param regex in paths)
     *          \)                                      Closing parenthesis (for param regex in paths)
     *          \+                                      Addition sign (for param regex in paths)
     *          \[                                      Opening bracket (for param regex in paths)
     *          \]                                      Closing bracket (for param regex in paths)
     *          \.                                      Period (for param regex in paths and dot notation on route name)
     *      ]*                                      Allow any number of above
     * )                                        End capture
     */
    public const ARGUMENTS_REGEX = <<<'REGEX'
    ([a-zA-Z_]*) 
    \s* \= \s* \'? "? \s*
    ([a-zA-Z0-9\_\-\\\/\:\{\}\(\)\+\[\]\.]*)
REGEX;
}