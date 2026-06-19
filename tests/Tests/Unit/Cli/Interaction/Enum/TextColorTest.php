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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Enum;

use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class TextColorTest extends TestCase
{
    public function testCaseValues(): void
    {
        self::assertSame(30, TextColor::BLACK->value);
        self::assertSame(37, TextColor::WHITE->value);
        self::assertSame(90, TextColor::DARK_GRAY->value);
        self::assertSame(97, TextColor::LIGHT_WHITE->value);
    }

    public function testCasesCount(): void
    {
        self::assertCount(16, TextColor::cases());
    }

    public function testGetDefaultReturnsResetCode(): void
    {
        self::assertSame(39, TextColor::BLACK->getDefault());
        self::assertSame(39, TextColor::LIGHT_WHITE->getDefault());
    }
}
