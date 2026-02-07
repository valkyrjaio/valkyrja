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

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Debug;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Debug\Dd;

use function preg_replace;

/**
 * Test the Dd class.
 */
final class DdTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new Dd();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new Dd();

        $input    = '@dd($variable)';
        $expected = '<?php \Valkyrja\dd($variable); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }

    public function testReplacementAppliedWithMultipleArguments(): void
    {
        $replacement = new Dd();

        $input    = '@dd($var1, $var2)';
        $expected = '<?php \Valkyrja\dd($var1, $var2); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
