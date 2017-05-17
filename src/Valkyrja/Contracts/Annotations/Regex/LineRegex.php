<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Annotations\Regex;

/**
 * Interface LineRegex.
 *
 * @author Melech Mizrachi
 */
interface LineRegex
{
    /**
     * @description
     *
     * \s*                                      Followed by any whitespace
     * \*?                                      Optional asterisk (for multiline)
     * \s*                                      Followed by an whitespace
     * (                                        Begin capture
     *      [a-zA-z0-9\s\*]*                        Any alpha numeric whitespace with asterisk (for multiline)
     * )?                                       End capture (optional cature group)
     * \s*                                      Followed by any whitespace
     * \*?                                      Optional asterisk (for multiline)
     * \s*                                      Followed by an whitespace
     * (                                        Begin capture
     *      ?:[\$]                                  Non-capture $ for variables
     *      ([a-zA-Z]*)                             Any alpha numeric string
     * )?                                       End capture (optional cature group)
     * \s*                                      Followed by any whitespace
     * \*?                                      Optional asterisk (for multiline)
     * \s*                                      Followed by an whitespace
     * (                                        Begin capture
     *      [a-zA-z0-9\s\*]*                        Any alpha numeric whitespace with asterisk (for multiline)
     * )?                                       End capture (optional cature group)
     */
    public const LINE_REGEX = <<<'REGEX'
    \s* \*? \s*
    ([a-zA-z0-9\\]*)?
    \s* \*? \s*
    (?:[\$]([a-zA-Z]*))?
    \s* \*? \s*
    ([a-zA-z0-9\s\*\[\]\=\>\,\$\\]*)?
REGEX;
}
