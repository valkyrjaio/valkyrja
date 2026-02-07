<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Variable;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Variable\Unescaped;

use function preg_replace;

/**
 * Test the Unescaped class.
 */
final class UnescapedTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new Unescaped();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new Unescaped();

        $input    = '{{{ $variable }}}';
        $expected = '<?= $variable; ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }

    public function testReplacementAppliedWithoutSpaces(): void
    {
        $replacement = new Unescaped();

        $input    = '{{{$variable}}}';
        $expected = '<?= $variable; ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
