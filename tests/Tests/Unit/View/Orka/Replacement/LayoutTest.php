<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Orka\Replacement;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Layout;

use function preg_replace;

/**
 * Test the Layout class.
 */
final class LayoutTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new Layout();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new Layout();

        $input    = '@layout(\'layouts/main\')';
        $expected = '<?php $template->setLayout(\'layouts/main\'); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }

    public function testReplacementAppliedWithSpaces(): void
    {
        $replacement = new Layout();

        $input    = '@layout( \'layouts/main\' )';
        $expected = '<?php $template->setLayout(\'layouts/main\' ); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
