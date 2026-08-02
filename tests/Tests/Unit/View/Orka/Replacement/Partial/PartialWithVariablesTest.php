<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Partial;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Partial\PartialWithVariables;

use function preg_replace;

/**
 * Test the PartialWithVariables class.
 */
final class PartialWithVariablesTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new PartialWithVariables();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new PartialWithVariables();

        $input    = '@partial(\'partials/header\', [\'title\' => $title])';
        $expected = '<?= $template->getPartial(\'partials/header\',  [\'title\' => $title]); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
