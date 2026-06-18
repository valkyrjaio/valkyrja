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

namespace Valkyrja\View\Orka\Constant;

use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

final class OrkaReplacementCollection
{
    /** @var class-string<ReplacementContract>[] */
    public const array CORE = [
        OrkaReplacement::LAYOUT,
        OrkaReplacement::BLOCK,
        OrkaReplacement::END_BLOCK,
        OrkaReplacement::START_BLOCK,
        OrkaReplacement::TRIM_BLOCK,
        OrkaReplacement::END_MULTILINE_COMMENT,
        OrkaReplacement::SINGLE_LINE_COMMENT,
        OrkaReplacement::START_MULTILINE_COMMENT,
        OrkaReplacement::PARTIAL,
        OrkaReplacement::PARTIAL_WITH_VARIABLES,
        OrkaReplacement::TRIM_PARTIAL,
        OrkaReplacement::TRIM_PARTIAL_WITH_VARIABLES,
        OrkaReplacement::BREAK_,
        OrkaReplacement::ELSE_HAS_BLOCK,
        OrkaReplacement::HAS_BLOCK,
        OrkaReplacement::UNLESS_BLOCK,
        OrkaReplacement::ELSE_,
        OrkaReplacement::ELSE_IF,
        OrkaReplacement::ELSE_UNLESS,
        OrkaReplacement::EMPTY_,
        OrkaReplacement::END_IF,
        OrkaReplacement::IF_,
        OrkaReplacement::ISSET_,
        OrkaReplacement::NOT_EMPTY,
        OrkaReplacement::UNLESS,
        OrkaReplacement::END_FOR,
        OrkaReplacement::END_FOREACH,
        OrkaReplacement::FOR_,
        OrkaReplacement::FOREACH_,
        OrkaReplacement::CASE_,
        OrkaReplacement::DEFAULT_,
        OrkaReplacement::END_SWITCH,
        OrkaReplacement::SWITCH_,
        OrkaReplacement::ESCAPED,
        OrkaReplacement::SET_VARIABLE,
        OrkaReplacement::SET_VARIABLES,
        OrkaReplacement::UNESCAPED,
    ];

    /** @var class-string<ReplacementContract>[] */
    public const array DEBUG = [
        OrkaReplacement::DEBUG,
    ];
}
