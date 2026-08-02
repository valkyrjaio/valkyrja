<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Struct\Contract;

use UnitEnum;
use Valkyrja\Http\Struct\Contract\StructContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Enum\Contract\ArrayableContract;

/**
 * Test the Struct.
 */
final class StructTest extends TestCase
{
    public function testContract(): void
    {
        self::assertIsA(UnitEnum::class, StructContract::class);
        self::assertIsA(ArrayableContract::class, StructContract::class);
    }
}
