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

use Valkyrja\View\Orka\Replacement\Block\Block;
use Valkyrja\View\Orka\Replacement\Block\EndBlock;
use Valkyrja\View\Orka\Replacement\Block\StartBlock;
use Valkyrja\View\Orka\Replacement\Block\TrimBlock;
use Valkyrja\View\Orka\Replacement\Comment\EndMultiline;
use Valkyrja\View\Orka\Replacement\Comment\SingleLine;
use Valkyrja\View\Orka\Replacement\Comment\StartMultiline;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Debug\Dd;
use Valkyrja\View\Orka\Replacement\Layout;
use Valkyrja\View\Orka\Replacement\Partial\Partial;
use Valkyrja\View\Orka\Replacement\Partial\PartialWithVariables;
use Valkyrja\View\Orka\Replacement\Partial\TrimPartial;
use Valkyrja\View\Orka\Replacement\Partial\TrimPartialWithVariables;
use Valkyrja\View\Orka\Replacement\Statement\Break_;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Block\ElseHasBlock;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Block\HasBlock;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Block\UnlessBlock;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Else_;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\ElseIf_;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\ElseUnless;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Empty_;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\EndIf_;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\If_;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Isset_;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\NotEmpty;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Unless;
use Valkyrja\View\Orka\Replacement\Statement\Iterate\EndFor_;
use Valkyrja\View\Orka\Replacement\Statement\Iterate\EndForeach_;
use Valkyrja\View\Orka\Replacement\Statement\Iterate\For_;
use Valkyrja\View\Orka\Replacement\Statement\Iterate\Foreach_;
use Valkyrja\View\Orka\Replacement\Statement\Switch\Case_;
use Valkyrja\View\Orka\Replacement\Statement\Switch\Default_;
use Valkyrja\View\Orka\Replacement\Statement\Switch\EndSwitch_;
use Valkyrja\View\Orka\Replacement\Statement\Switch\Switch_;
use Valkyrja\View\Orka\Replacement\Variable\Escaped;
use Valkyrja\View\Orka\Replacement\Variable\SetVariable;
use Valkyrja\View\Orka\Replacement\Variable\SetVariables;
use Valkyrja\View\Orka\Replacement\Variable\Unescaped;

final class OrkaReplacement
{
    /** @var class-string<ReplacementContract> */
    public const string LAYOUT = Layout::class;

    /************************************************************
     *
     * Block Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string BLOCK = Block::class;
    /** @var class-string<ReplacementContract> */
    public const string END_BLOCK = EndBlock::class;
    /** @var class-string<ReplacementContract> */
    public const string START_BLOCK = StartBlock::class;
    /** @var class-string<ReplacementContract> */
    public const string TRIM_BLOCK = TrimBlock::class;

    /************************************************************
     *
     * Comment Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string END_MULTILINE_COMMENT = EndMultiline::class;
    /** @var class-string<ReplacementContract> */
    public const string SINGLE_LINE_COMMENT = SingleLine::class;
    /** @var class-string<ReplacementContract> */
    public const string START_MULTILINE_COMMENT = StartMultiline::class;

    /************************************************************
     *
     * Debug Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string DEBUG = Dd::class;

    /************************************************************
     *
     * Partial Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string PARTIAL = Partial::class;
    /** @var class-string<ReplacementContract> */
    public const string PARTIAL_WITH_VARIABLES = PartialWithVariables::class;
    /** @var class-string<ReplacementContract> */
    public const string TRIM_PARTIAL = TrimPartial::class;
    /** @var class-string<ReplacementContract> */
    public const string TRIM_PARTIAL_WITH_VARIABLES = TrimPartialWithVariables::class;

    /************************************************************
     *
     * Statement Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string BREAK_ = Break_::class;

    /************************************************************
     *
     * Statement Block Conditional Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string ELSE_HAS_BLOCK = ElseHasBlock::class;
    /** @var class-string<ReplacementContract> */
    public const string HAS_BLOCK = HasBlock::class;
    /** @var class-string<ReplacementContract> */
    public const string UNLESS_BLOCK = UnlessBlock::class;

    /************************************************************
     *
     * Statement Conditional Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string ELSE_ = Else_::class;
    /** @var class-string<ReplacementContract> */
    public const string ELSE_IF = ElseIf_::class;
    /** @var class-string<ReplacementContract> */
    public const string ELSE_UNLESS = ElseUnless::class;
    /** @var class-string<ReplacementContract> */
    public const string EMPTY_ = Empty_::class;
    /** @var class-string<ReplacementContract> */
    public const string END_IF = EndIf_::class;
    /** @var class-string<ReplacementContract> */
    public const string IF_ = If_::class;
    /** @var class-string<ReplacementContract> */
    public const string ISSET_ = Isset_::class;
    /** @var class-string<ReplacementContract> */
    public const string NOT_EMPTY = NotEmpty::class;
    /** @var class-string<ReplacementContract> */
    public const string UNLESS = Unless::class;

    /************************************************************
     *
     * Statement Interation Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string END_FOR = EndFor_::class;
    /** @var class-string<ReplacementContract> */
    public const string END_FOREACH = EndForeach_::class;
    /** @var class-string<ReplacementContract> */
    public const string FOR_ = For_::class;
    /** @var class-string<ReplacementContract> */
    public const string FOREACH_ = Foreach_::class;

    /************************************************************
     *
     * Statement Switch Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string CASE_ = Case_::class;
    /** @var class-string<ReplacementContract> */
    public const string DEFAULT_ = Default_::class;
    /** @var class-string<ReplacementContract> */
    public const string END_SWITCH = EndSwitch_::class;
    /** @var class-string<ReplacementContract> */
    public const string SWITCH_ = Switch_::class;

    /************************************************************
     *
     * Variable Replacements
     *
     ************************************************************/

    /** @var class-string<ReplacementContract> */
    public const string ESCAPED = Escaped::class;
    /** @var class-string<ReplacementContract> */
    public const string SET_VARIABLE = SetVariable::class;
    /** @var class-string<ReplacementContract> */
    public const string SET_VARIABLES = SetVariables::class;
    /** @var class-string<ReplacementContract> */
    public const string UNESCAPED = Unescaped::class;
}
