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
use Valkyrja\View\Orka\Replacement\Variable\SetVariable;

use function preg_replace;

/**
 * Test the SetVariable class.
 */
final class SetVariableTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $replacement = new SetVariable();

        self::assertInstanceOf(ReplacementContract::class, $replacement);
    }

    public function testReplacementApplied(): void
    {
        $replacement = new SetVariable();

        $input    = '@setvariable(\'name\', $value)';
        $expected = '<?php $template->setVariable(\'name\',  $value); ?>';
        $result   = preg_replace($replacement->regex(), $replacement->replacement(), $input);

        self::assertSame($expected, $result);
    }
}
