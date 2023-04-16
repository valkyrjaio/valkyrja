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

namespace Valkyrja\View\Constants;

/**
 * Constant Regex.
 *
 * @author Melech Mizrachi
 */
final class Regex
{
    /**
     * Annotation name regex.
     * - Matches something like @Annotation, or @Annotation\Sub.
     *
     * @constant string
     *
     * @description
     *
     *      @startblock
     *      \(                  Escaped beginning parenthesis
     *      \s*                 Any amount of whitespace
     *      \'                  Escaped single quote
     *      (                   Begin capture
     *          [                   Begin any of following:
     *              a-z                 Lowercase character
     *              A-Z                 Uppercase character
     *              0-9                 Digit character
     *          ]*                  Allow any number of above
     *      )                   End capture
     *      \'                  Escaped single quote
     *      \s*                 Any amount of whitespace
     *      \)                  Escaped ending parenthesis
     *      \s*                 Any amount of whitespace
     *      (                   Begin capture
     *          [                   Begin any of following:
     *              \s                 Any whitespace character
     *              \S                 Any non-whitespace character
     *              .                  Any character
     *          ]*                  Allow any number of above
     *          ?                   Capture as few as possible
     *      )                   End capture
     *      \s*                 Any amount of whitespace
     *
     *      @endblock
     */
    public const NAME_REGEX = <<<'REGEX'
                @startblock\(\s*\'([a-zA-Z0-9]*)\'\s*\)\s*([\s\S.]*?)\s*@endblock
        REGEX;
}
