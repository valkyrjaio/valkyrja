<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Statement\Conditional;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\EndIf_;

use function preg_replace;

/**
 * Test the EndIf_ class.
 */
final class EndIfTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new EndIf_();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new EndIf_();

        $input    = '@endif';
        $expected = '<?php endif ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
