<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\View\Provider;

use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\View\Orka\Replacement\Block\Block;
use Valkyrja\View\Orka\Replacement\Block\EndBlock;
use Valkyrja\View\Orka\Replacement\Block\StartBlock;
use Valkyrja\View\Orka\Replacement\Block\TrimBlock;
use Valkyrja\View\Orka\Replacement\Comment\EndMultiline;
use Valkyrja\View\Orka\Replacement\Comment\SingleLine;
use Valkyrja\View\Orka\Replacement\Comment\StartMultiline;
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

class ViewOrkaServiceProvider implements ServiceProviderContract
{
    /**
     * Publish every core replacement.
     *
     * The renderer asks for all of them together, so one publisher binds the set.
     */
    public static function publishReplacements(ContainerContract $container): void
    {
        $container->setSingleton(Layout::class, new Layout());
        $container->setSingleton(Block::class, new Block());
        $container->setSingleton(EndBlock::class, new EndBlock());
        $container->setSingleton(StartBlock::class, new StartBlock());
        $container->setSingleton(TrimBlock::class, new TrimBlock());
        $container->setSingleton(EndMultiline::class, new EndMultiline());
        $container->setSingleton(SingleLine::class, new SingleLine());
        $container->setSingleton(StartMultiline::class, new StartMultiline());
        $container->setSingleton(Partial::class, new Partial());
        $container->setSingleton(PartialWithVariables::class, new PartialWithVariables());
        $container->setSingleton(TrimPartial::class, new TrimPartial());
        $container->setSingleton(TrimPartialWithVariables::class, new TrimPartialWithVariables());
        $container->setSingleton(Break_::class, new Break_());
        $container->setSingleton(ElseHasBlock::class, new ElseHasBlock());
        $container->setSingleton(HasBlock::class, new HasBlock());
        $container->setSingleton(UnlessBlock::class, new UnlessBlock());
        $container->setSingleton(Else_::class, new Else_());
        $container->setSingleton(ElseIf_::class, new ElseIf_());
        $container->setSingleton(ElseUnless::class, new ElseUnless());
        $container->setSingleton(Empty_::class, new Empty_());
        $container->setSingleton(EndIf_::class, new EndIf_());
        $container->setSingleton(If_::class, new If_());
        $container->setSingleton(Isset_::class, new Isset_());
        $container->setSingleton(NotEmpty::class, new NotEmpty());
        $container->setSingleton(Unless::class, new Unless());
        $container->setSingleton(EndFor_::class, new EndFor_());
        $container->setSingleton(EndForeach_::class, new EndForeach_());
        $container->setSingleton(For_::class, new For_());
        $container->setSingleton(Foreach_::class, new Foreach_());
        $container->setSingleton(Case_::class, new Case_());
        $container->setSingleton(Default_::class, new Default_());
        $container->setSingleton(EndSwitch_::class, new EndSwitch_());
        $container->setSingleton(Switch_::class, new Switch_());
        $container->setSingleton(Escaped::class, new Escaped());
        $container->setSingleton(SetVariable::class, new SetVariable());
        $container->setSingleton(SetVariables::class, new SetVariables());
        $container->setSingleton(Unescaped::class, new Unescaped());
        $container->setSingleton(Dd::class, new Dd());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            Layout::class                   => [self::class, 'publishReplacements'],
            Block::class                    => [self::class, 'publishReplacements'],
            EndBlock::class                 => [self::class, 'publishReplacements'],
            StartBlock::class               => [self::class, 'publishReplacements'],
            TrimBlock::class                => [self::class, 'publishReplacements'],
            EndMultiline::class             => [self::class, 'publishReplacements'],
            SingleLine::class               => [self::class, 'publishReplacements'],
            StartMultiline::class           => [self::class, 'publishReplacements'],
            Partial::class                  => [self::class, 'publishReplacements'],
            PartialWithVariables::class     => [self::class, 'publishReplacements'],
            TrimPartial::class              => [self::class, 'publishReplacements'],
            TrimPartialWithVariables::class => [self::class, 'publishReplacements'],
            Break_::class                   => [self::class, 'publishReplacements'],
            ElseHasBlock::class             => [self::class, 'publishReplacements'],
            HasBlock::class                 => [self::class, 'publishReplacements'],
            UnlessBlock::class              => [self::class, 'publishReplacements'],
            Else_::class                    => [self::class, 'publishReplacements'],
            ElseIf_::class                  => [self::class, 'publishReplacements'],
            ElseUnless::class               => [self::class, 'publishReplacements'],
            Empty_::class                   => [self::class, 'publishReplacements'],
            EndIf_::class                   => [self::class, 'publishReplacements'],
            If_::class                      => [self::class, 'publishReplacements'],
            Isset_::class                   => [self::class, 'publishReplacements'],
            NotEmpty::class                 => [self::class, 'publishReplacements'],
            Unless::class                   => [self::class, 'publishReplacements'],
            EndFor_::class                  => [self::class, 'publishReplacements'],
            EndForeach_::class              => [self::class, 'publishReplacements'],
            For_::class                     => [self::class, 'publishReplacements'],
            Foreach_::class                 => [self::class, 'publishReplacements'],
            Case_::class                    => [self::class, 'publishReplacements'],
            Default_::class                 => [self::class, 'publishReplacements'],
            EndSwitch_::class               => [self::class, 'publishReplacements'],
            Switch_::class                  => [self::class, 'publishReplacements'],
            Escaped::class                  => [self::class, 'publishReplacements'],
            SetVariable::class              => [self::class, 'publishReplacements'],
            SetVariables::class             => [self::class, 'publishReplacements'],
            Unescaped::class                => [self::class, 'publishReplacements'],
            Dd::class                       => [self::class, 'publishReplacements'],
        ];
    }
}
