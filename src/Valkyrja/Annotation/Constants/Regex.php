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

namespace Valkyrja\Annotation\Constants;

/**
 * Constant Regex.
 *
 * @author Melech Mizrachi
 */
final class Regex
{
    /**
     * Annotation symbol.
     *
     * @constant string
     */
    public const SYMBOL = '@';

    /**
     * Annotation name regex.
     * - Matches something like @Annotation, or @Annotation\Sub
     *
     * @constant string
     * @description
     *      (                       Begin capture
     *          [                       Begin any of following:
     *              a-z                 Lowercase character
     *              A-Z                 Uppercase character
     *              0-9                 Digit character
     *              \_                  Underscore
     *              \\                  Backslash (for classes)
     *          ]*                  Allow any number of above
     *      )                   End capture
     */
    public const NAME_REGEX = <<<'REGEX'
        ([a-zA-Z0-9\_\\]*)
REGEX;

    /**
     * Arguments regex.
     * - Matches something like: ("name" = "value", "array" = ["value"])
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
     *              \{                  Opening curly brace (for params in paths)
     *              \}                  Closing curly brace (for params in paths)
     *              \(                  Opening parenthesis (for param regex in paths)
     *              \)                  Closing parenthesis (for param regex in paths)
     *              \+                  Addition sign (for param regex in paths)
     *              \[                  Opening bracket (for param regex in paths)
     *              \]                  Closing bracket (for param regex in paths)
     *              \.                  Period (for param regex in paths and dot notation on route name)
     *              \=                  Equal sign (for argument value/index separation)
     *              \,                  Comma (for argument separation)
     *              \'                  Single quote (for argument value enclosure)
     *              \"                  Double quote (for argument value enclosure)
     *              \*                  Asterisk (for multiline comments phpDoc)
     *              \<                  Argument Group Open: replaced with '(?:'
     *              \>                  Argument Group Close: replaced with ')'
     *              \|                  Pipe or for regex
     *              \s                  Whitespace
     *          ]*                  Allow any number of above
     *      )                   End capture
     *      \s*                 Followed by any whitespace
     * )                    Ending parenthesis for the annotation
     */
    public const ARGUMENTS_REGEX = <<<'REGEX'
    \(
        \s*
            ([a-zA-Z0-9\_\-\\\/\:\{\}\(\)\+\[\]\.\=\,\'\"\*\<\>\|\s]*)
        \s*
    \)
REGEX;

    /**
     * Regex for single docstring line.
     *  - Matches something like: "int $param Description"
     *
     * @constant string
     * @description
     * \s*                      Followed by any whitespace
     * \*?                      Optional asterisk (for multiline)
     * \s*                      Followed by an whitespace
     * (                        Begin capture
     *      [a-zA-z0-9\s\*]*        Any alpha numeric whitespace with asterisk (for multiline)
     * )?                       End capture (optional capture group)
     * \s*                      Followed by any whitespace
     * \*?                      Optional asterisk (for multiline)
     * \s*                      Followed by an whitespace
     * (                        Begin capture
     *      ?:[\$]                  Non-capture $ for variables
     *      ([a-zA-Z]*)             Any alpha numeric string
     * )?                       End capture (optional capture group)
     * \s*                      Followed by any whitespace
     * \*?                      Optional asterisk (for multiline)
     * \s*                      Followed by an whitespace
     * (                        Begin capture
     *      [a-zA-z0-9\s\*]*        Any alpha numeric whitespace with asterisk
     *                                  (for multiline)
     * )?                       End capture (optional capture group)
     */
    public const LINE_REGEX = <<<'REGEX'
    \s* \*? \s*
    ([a-zA-z0-9\\]*)?
    \s* \*? \s*
    (?:[\$]([a-zA-Z]*))?
    \s* \*? \s*
    ([a-zA-z0-9\s\*\[\]\=\>\,\$\\]*)?
REGEX;

    /**
     * The full regex.
     */
    public const REGEX = '/'
    . self::SYMBOL
    . self::NAME_REGEX
    . '(?:' . self::ARGUMENTS_REGEX . ')?'
    . self::LINE_REGEX
    . '/x';
}
