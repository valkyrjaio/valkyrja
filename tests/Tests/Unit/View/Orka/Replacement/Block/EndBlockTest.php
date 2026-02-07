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

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Block;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Block\EndBlock;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;

use function preg_replace;

/**
 * Test the EndBlock class.
 */
final class EndBlockTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new EndBlock();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new EndBlock();

        $input    = '@endblock';
        $expected = '<?php $template->endBlock(); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
