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

namespace Valkyrja\Tests\Unit\Type\Enum\Trait;

use Valkyrja\Tests\Classes\Enum\EnumClass;
use Valkyrja\Tests\Classes\Enum\IntEnum;
use Valkyrja\Tests\Classes\Enum\StringEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Enum\Throwable\Exception\EnumCannotModifyException;
use Valkyrja\Type\Enum\Throwable\Exception\EnumInvalidValueException;

final class EnumerableTest extends TestCase
{
    public function testFromValueReturnsSameInstanceWhenAlreadyEnum(): void
    {
        self::assertSame(StringEnum::foo, StringEnum::fromValue(StringEnum::foo));
    }

    public function testFromValueResolvesBackedEnumFromValue(): void
    {
        self::assertSame(StringEnum::foo, StringEnum::fromValue('bar'));
        self::assertSame(IntEnum::second, IntEnum::fromValue(2));
    }

    public function testFromValueResolvesUnitEnumFromName(): void
    {
        self::assertSame(EnumClass::spade, EnumClass::fromValue('spade'));
    }

    public function testFromValueThrowsForUnknownUnitEnumName(): void
    {
        $this->expectException(EnumInvalidValueException::class);

        EnumClass::fromValue('joker');
    }

    public function testFromValueThrowsForNonStringIntValue(): void
    {
        $this->expectException(EnumInvalidValueException::class);

        StringEnum::fromValue(1.5);
    }

    public function testAsValueReturnsSelf(): void
    {
        self::assertSame(StringEnum::foo, StringEnum::foo->asValue());
    }

    public function testAsFlatValueReturnsBackedValue(): void
    {
        self::assertSame('bar', StringEnum::foo->asFlatValue());
        self::assertSame(1, IntEnum::first->asFlatValue());
    }

    public function testAsFlatValueReturnsNameForUnitEnum(): void
    {
        self::assertSame('spade', EnumClass::spade->asFlatValue());
    }

    public function testModifyThrows(): void
    {
        $this->expectException(EnumCannotModifyException::class);

        StringEnum::foo->modify(static fn (mixed $value): mixed => $value);
    }

    public function testJsonSerialize(): void
    {
        self::assertSame('bar', StringEnum::foo->jsonSerialize());
        self::assertSame('spade', EnumClass::spade->jsonSerialize());
    }
}