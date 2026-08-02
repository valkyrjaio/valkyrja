<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Variable;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Variable\SetVariables;

use function preg_replace;

/**
 * Test the SetVariables class.
 */
final class SetVariablesTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new SetVariables();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new SetVariables();

        $input    = '@setvariables([\'name\' => $value])';
        $expected = '<?php $template->setVariables([\'name\' => $value]); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
