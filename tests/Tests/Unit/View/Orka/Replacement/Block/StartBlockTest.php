<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Block;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Block\StartBlock;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

use function preg_replace;

/**
 * Test the StartBlock class.
 */
final class StartBlockTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new StartBlock();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new StartBlock();

        $input    = '@startblock(\'content\')';
        $expected = '<?php $template->startBlock(\'content\'); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
