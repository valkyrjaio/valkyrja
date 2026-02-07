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

namespace Valkyrja\Tests\Unit\Type\Enum\Support;

use Valkyrja\Tests\Classes\Enum\ArrayableEnum;
use Valkyrja\Tests\Classes\Enum\ArrayableIntEnum;
use Valkyrja\Tests\Classes\Enum\ArrayableStringEnum;
use Valkyrja\Tests\Classes\Enum\EnumClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Enum\Support\Enumerable;

final class EnumTest extends TestCase
{
    protected const EnumClass VALUE = EnumClass::club;

    public function testNames(): void
    {
        self::assertSame(
            ['spade', 'heart', 'diamond', 'club'],
            Enumerable::names(ArrayableEnum::class)
        );

        self::assertSame(
            ['foo', 'lorem'],
            Enumerable::names(ArrayableStringEnum::class)
        );

        self::assertSame(
            ['first', 'second'],
            Enumerable::names(ArrayableIntEnum::class)
        );
    }

    public function testValues(): void
    {
        self::assertSame(
            ['spade', 'heart', 'diamond', 'club'],
            Enumerable::values(ArrayableEnum::class)
        );

        self::assertSame(
            ['bar', 'ipsum'],
            Enumerable::values(ArrayableStringEnum::class)
        );

        self::assertSame(
            [1, 2],
            Enumerable::values(ArrayableIntEnum::class)
        );
    }

    public function testAsArray(): void
    {
        self::assertSame(
            ['spade' => 'spade', 'heart' => 'heart', 'diamond' => 'diamond', 'club' => 'club'],
            Enumerable::asArray(ArrayableEnum::class)
        );

        self::assertSame(
            ['foo' => 'bar', 'lorem' => 'ipsum'],
            Enumerable::asArray(ArrayableStringEnum::class)
        );

        self::assertSame(
            ['first' => 1, 'second' => 2],
            Enumerable::asArray(ArrayableIntEnum::class)
        );
    }

    public function testAsReverseArray(): void
    {
        self::assertSame(
            ['spade' => 'spade', 'heart' => 'heart', 'diamond' => 'diamond', 'club' => 'club'],
            Enumerable::asReverseArray(ArrayableEnum::class)
        );

        self::assertSame(
            ['bar' => 'foo', 'ipsum' => 'lorem'],
            Enumerable::asReverseArray(ArrayableStringEnum::class)
        );

        self::assertSame(
            [1 => 'first', 2 => 'second'],
            Enumerable::asReverseArray(ArrayableIntEnum::class)
        );
    }

    public function testIsValidName(): void
    {
        self::assertTrue(Enumerable::isValidName(ArrayableEnum::class, 'spade'));
        self::assertFalse(Enumerable::isValidName(ArrayableEnum::class, 'card'));

        self::assertTrue(Enumerable::isValidName(ArrayableStringEnum::class, 'foo'));
        self::assertFalse(Enumerable::isValidName(ArrayableStringEnum::class, 'too'));

        self::assertTrue(Enumerable::isValidName(ArrayableIntEnum::class, 'second'));
        self::assertFalse(Enumerable::isValidName(ArrayableIntEnum::class, 'third'));
    }

    public function testIsValidValue(): void
    {
        self::assertTrue(Enumerable::isValidValue(ArrayableStringEnum::class, 'bar'));
        self::assertFalse(Enumerable::isValidValue(ArrayableStringEnum::class, 'too'));

        self::assertTrue(Enumerable::isValidValue(ArrayableIntEnum::class, 2));
        self::assertFalse(Enumerable::isValidValue(ArrayableIntEnum::class, 3));
    }
}
