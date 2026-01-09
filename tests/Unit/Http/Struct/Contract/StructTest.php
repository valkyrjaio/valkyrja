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

namespace Valkyrja\Tests\Unit\Http\Struct\Contract;

use UnitEnum;
use Valkyrja\Http\Struct\Contract\StructContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\BuiltIn\Enum\Contract\ArrayableContract;

/**
 * Test the Struct.
 */
class StructTest extends TestCase
{
    public function testContract(): void
    {
        self::assertIsA(UnitEnum::class, StructContract::class);
        self::assertIsA(ArrayableContract::class, StructContract::class);
    }
}
