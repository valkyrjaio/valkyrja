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

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Comment;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Comment\SingleLine;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

use function preg_replace;

/**
 * Test the SingleLine class.
 */
class SingleLineTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new SingleLine();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new SingleLine();

        $input    = '@// This is a comment';
        $expected = '<?php // This is a comment';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
