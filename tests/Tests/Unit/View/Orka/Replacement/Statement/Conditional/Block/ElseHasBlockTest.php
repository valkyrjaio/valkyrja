<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Statement\Conditional\Block;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Block\ElseHasBlock;

use function preg_replace;

/**
 * Test the ElseHasBlock class.
 */
final class ElseHasBlockTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new ElseHasBlock();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new ElseHasBlock();

        $input    = '@elsehasblock(\'sidebar\')';
        $expected = '<?php elseif ($template->hasBlock(\'sidebar\')) : ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
