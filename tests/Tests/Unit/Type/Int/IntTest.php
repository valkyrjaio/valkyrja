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

namespace Valkyrja\Tests\Unit\Type\Int;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Int\IntT;
use Valkyrja\Type\Int\Throwable\Exception\IntInvalidFromValueException;

final class IntTest extends TestCase
{
    protected const int VALUE = 1;

    public function testValue(): void
    {
        $type = new IntT(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValue(): void
    {
        $typeFromValue = IntT::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $typeFromValue->asValue());
    }

    public function testFromEmptyArrayValue(): void
    {
        self::assertSame(0, IntT::fromValue([])->asValue());
    }

    public function testFromNonEmptyArrayValue(): void
    {
        self::assertSame(1, IntT::fromValue(['x'])->asValue());
    }

    public function testFromUnsupportedValueThrows(): void
    {
        $this->expectException(IntInvalidFromValueException::class);

        IntT::fromValue(null);
    }

    public function testAsFlatValue(): void
    {
        $type = new IntT(self::VALUE);

        self::assertSame(self::VALUE, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $type = new IntT(self::VALUE);
        // The new value
        $newValue = 2;

        $modified = $type->modify(static fn (int $subject): int => $newValue);

        // Original should be unmodified
        self::assertSame(self::VALUE, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new IntT(self::VALUE);

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
