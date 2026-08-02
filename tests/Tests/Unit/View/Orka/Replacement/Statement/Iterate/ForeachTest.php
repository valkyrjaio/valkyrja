<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Statement\Iterate;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Statement\Iterate\Foreach_;

use function preg_replace;

/**
 * Test the Foreach_ class.
 */
final class ForeachTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new Foreach_();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new Foreach_();

        $input    = '@foreach($items as $item)';
        $expected = '<?php foreach ($items as $item) : ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }

    public function testReplacementAppliedWithKeyValue(): void
    {
        $replacement = new Foreach_();

        $input    = '@foreach($items as $key => $item)';
        $expected = '<?php foreach ($items as $key => $item) : ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
