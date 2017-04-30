<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Parsers\PathParser;

/**
 * Class CommandParser
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class CommandParser extends PathParser
{
    /**
     * The variable regex.
     *
     * @var string
     */
    protected const VARIABLE_REGEX = <<<'REGEX'
\{
    \s* ([a-zA-Z0-9_-]*) \s*
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
}
