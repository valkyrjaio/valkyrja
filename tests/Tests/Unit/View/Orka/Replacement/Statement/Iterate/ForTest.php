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

namespace Valkyrja\Tests\Unit\View\Orka\Replacement\Statement\Iterate;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Statement\Iterate\For_;

use function preg_replace;

/**
 * Test the For_ class.
 */
class ForTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new For_();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new For_();

        $input    = '@for($i = 0; $i < 10; $i++)';
        $expected = '<?php for ($i = 0; $i < 10; $i++) : ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
