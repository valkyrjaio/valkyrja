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

namespace Valkyrja\Tests\Unit\Type\Support\Enum;

use Valkyrja\Tests\Classes\Enums\Arrayable;
use Valkyrja\Tests\Classes\Enums\ArrayableIntEnum;
use Valkyrja\Tests\Classes\Enums\ArrayableStringEnum;
use Valkyrja\Tests\Classes\Enums\Enum as TestEnum;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Support\Enum;

class EnumTest extends TestCase
{
    protected const VALUE = TestEnum::club;

    public function testNames(): void
    {
        self::assertSame(
            ['spade', 'heart', 'diamond', 'club'],
            Enum::names(Arrayable::class)
        );

        self::assertSame(
            ['foo', 'lorem'],
            Enum::names(ArrayableStringEnum::class)
        );

        self::assertSame(
            ['first', 'second'],
            Enum::names(ArrayableIntEnum::class)
        );
    }

    public function testValues(): void
    {
        self::assertSame(
            ['spade', 'heart', 'diamond', 'club'],
            Enum::values(Arrayable::class)
        );

        self::assertSame(
            ['bar', 'ipsum'],
            Enum::values(ArrayableStringEnum::class)
        );

        self::assertSame(
            [1, 2],
            Enum::values(ArrayableIntEnum::class)
        );
    }

    public function testAsArray(): void
    {
        self::assertSame(
            ['spade' => 'spade', 'heart' => 'heart', 'diamond' => 'diamond', 'club' => 'club'],
            Enum::asArray(Arrayable::class)
        );

        self::assertSame(
            ['foo' => 'bar', 'lorem' => 'ipsum'],
            Enum::asArray(ArrayableStringEnum::class)
        );

        self::assertSame(
            ['first' => 1, 'second' => 2],
            Enum::asArray(ArrayableIntEnum::class)
        );
    }

    public function testAsReverseArray(): void
    {
        self::assertSame(
            ['spade' => 'spade', 'heart' => 'heart', 'diamond' => 'diamond', 'club' => 'club'],
            Enum::asReverseArray(Arrayable::class)
        );

        self::assertSame(
            ['bar' => 'foo', 'ipsum' => 'lorem'],
            Enum::asReverseArray(ArrayableStringEnum::class)
        );

        self::assertSame(
            [1 => 'first', 2 => 'second'],
            Enum::asReverseArray(ArrayableIntEnum::class)
        );
    }

    public function testIsValidName(): void
    {
        self::assertTrue(Enum::isValidName(Arrayable::class, 'spade'));
        self::assertFalse(Enum::isValidName(Arrayable::class, 'card'));

        self::assertTrue(Enum::isValidName(ArrayableStringEnum::class, 'foo'));
        self::assertFalse(Enum::isValidName(ArrayableStringEnum::class, 'too'));

        self::assertTrue(Enum::isValidName(ArrayableIntEnum::class, 'second'));
        self::assertFalse(Enum::isValidName(ArrayableIntEnum::class, 'third'));
    }

    public function testIsValidValue(): void
    {
        self::assertTrue(Enum::isValidValue(ArrayableStringEnum::class, 'bar'));
        self::assertFalse(Enum::isValidValue(ArrayableStringEnum::class, 'too'));

        self::assertTrue(Enum::isValidValue(ArrayableIntEnum::class, 2));
        self::assertFalse(Enum::isValidValue(ArrayableIntEnum::class, 3));
    }
}
