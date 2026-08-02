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
use Valkyrja\View\Orka\Replacement\Partial\TrimPartial;

use function preg_replace;

/**
 * Test the TrimPartial class.
 */
final class TrimPartialTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new TrimPartial();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new TrimPartial();

        $input    = '@trimpartial(\'partials/header\')';
        $expected = '<?= trim($template->getPartial(\'partials/header\')); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
