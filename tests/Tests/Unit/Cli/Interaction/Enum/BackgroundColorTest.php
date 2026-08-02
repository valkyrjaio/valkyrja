<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Enum;

use Valkyrja\Cli\Interaction\Enum\BackgroundColor;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class BackgroundColorTest extends TestCase
{
    public function testCaseValues(): void
    {
        self::assertSame(40, BackgroundColor::BLACK->value);
        self::assertSame(47, BackgroundColor::WHITE->value);
        self::assertSame(100, BackgroundColor::DARK_GRAY->value);
        self::assertSame(107, BackgroundColor::LIGHT_WHITE->value);
    }

    public function testCasesCount(): void
    {
        self::assertCount(16, BackgroundColor::cases());
    }

    public function testGetDefaultReturnsResetCode(): void
    {
        self::assertSame(49, BackgroundColor::BLACK->getDefault());
        self::assertSame(49, BackgroundColor::LIGHT_WHITE->getDefault());
    }
}
