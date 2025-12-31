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
use Valkyrja\Http\Struct\Contract\Struct as Contract;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Enum\Contract\Arrayable;

/**
 * Test the Struct.
 *
 * @author Melech Mizrachi
 */
class StructTest extends TestCase
{
    public function testContract(): void
    {
        self::assertIsA(UnitEnum::class, Contract::class);
        self::assertIsA(Arrayable::class, Contract::class);
    }
}
