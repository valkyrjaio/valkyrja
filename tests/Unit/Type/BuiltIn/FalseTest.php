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

namespace Valkyrja\Tests\Unit\Type\BuiltIn;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Types\FalseT;

class FalseTest extends TestCase
{
    protected const VALUE = false;

    public function testValue(): void
    {
        $type = new FalseT(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = FalseT::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testAsFlatValue(): void
    {
        $type = new FalseT(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new FalseT(self::VALUE);
        // The new value
        $newValue = true;

        $modified = $type->modify(fn (bool $subject): bool => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be unmodified and always false
        self::assertNotSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new FalseT(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
