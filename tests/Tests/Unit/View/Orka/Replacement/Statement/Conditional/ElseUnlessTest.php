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

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Statement\Conditional;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Statement\Conditional\ElseUnless;

use function preg_replace;

/**
 * Test the ElseUnless class.
 */
final class ElseUnlessTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new ElseUnless();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new ElseUnless();

        $input    = '@elseunless($condition)';
        $expected = '<?php elseif (! ($condition)) : ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
