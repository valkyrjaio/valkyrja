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
use Valkyrja\View\Orka\Replacement\Statement\Conditional\Block\UnlessBlock;

use function preg_replace;

/**
 * Test the UnlessBlock class.
 */
final class UnlessBlockTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new UnlessBlock();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new UnlessBlock();

        $input    = '@unlessblock(\'content\')';
        $expected = '<?php if (! $template->hasBlock(\'content\')) : ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
