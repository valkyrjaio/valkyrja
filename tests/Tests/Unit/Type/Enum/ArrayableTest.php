<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Enum;

use Valkyrja\Tests\Fixtures\Enum\ArrayableEnum;
use Valkyrja\Tests\Fixtures\Enum\ArrayableIntEnum;
use Valkyrja\Tests\Fixtures\Enum\ArrayableStringEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ArrayableTest extends TestCase
{
    public function testNames(): void
    {
        self::assertSame(
            ['spade', 'heart', 'diamond', 'club'],
            ArrayableEnum::names()
        );

        self::assertSame(
            ['foo', 'lorem'],
            ArrayableStringEnum::names()
        );

        self::assertSame(
            ['first', 'second'],
            ArrayableIntEnum::names()
        );
    }

    public function testValues(): void
    {
        self::assertSame(
            ['spade', 'heart', 'diamond', 'club'],
            ArrayableEnum::values()
        );

        self::assertSame(
            ['bar', 'ipsum'],
            ArrayableStringEnum::values()
        );

        self::assertSame(
            [1, 2],
            ArrayableIntEnum::values()
        );
    }

    public function testAsArray(): void
    {
        self::assertSame(
            ['spade' => 'spade', 'heart' => 'heart', 'diamond' => 'diamond', 'club' => 'club'],
            ArrayableEnum::asArray()
        );

        self::assertSame(
            ['foo' => 'bar', 'lorem' => 'ipsum'],
            ArrayableStringEnum::asArray()
        );

        self::assertSame(
            ['first' => 1, 'second' => 2],
            ArrayableIntEnum::asArray()
        );
    }

    public function testAsReverseArray(): void
    {
        self::assertSame(
            ['spade' => 'spade', 'heart' => 'heart', 'diamond' => 'diamond', 'club' => 'club'],
            ArrayableEnum::asReverseArray()
        );

        self::assertSame(
            ['bar' => 'foo', 'ipsum' => 'lorem'],
            ArrayableStringEnum::asReverseArray()
        );

        self::assertSame(
            [1 => 'first', 2 => 'second'],
            ArrayableIntEnum::asReverseArray()
        );
    }
}
