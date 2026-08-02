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
use Valkyrja\View\Orka\Replacement\Block\Block;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

use function preg_replace;

/**
 * Test the Block class.
 */
final class BlockTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new Block();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new Block();

        $input    = '@block(\'content\')';
        $expected = '<?= $template->getBlock(\'content\'); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }

    public function testReplacementAppliedWithSpaces(): void
    {
        $replacement = new Block();

        $input    = '@block( \'content\' )';
        $expected = '<?= $template->getBlock(\'content\' ); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
