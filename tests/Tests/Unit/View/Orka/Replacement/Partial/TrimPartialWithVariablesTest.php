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
use Valkyrja\View\Orka\Replacement\Partial\TrimPartialWithVariables;

use function preg_replace;

/**
 * Test the TrimPartialWithVariables class.
 */
final class TrimPartialWithVariablesTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new TrimPartialWithVariables();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new TrimPartialWithVariables();

        $input    = '@trimpartial(\'partials/header\', [\'title\' => $title])';
        $expected = '<?= trim($template->getPartial(\'partials/header\',  [\'title\' => $title])); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
