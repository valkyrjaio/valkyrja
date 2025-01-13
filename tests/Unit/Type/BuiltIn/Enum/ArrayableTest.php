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

namespace Valkyrja\Tests\Unit\Type\BuiltIn\Enum;

use Valkyrja\Tests\Classes\Enum\ArrayableEnum;
use Valkyrja\Tests\Classes\Enum\ArrayableIntEnum;
use Valkyrja\Tests\Classes\Enum\ArrayableStringEnum;
use Valkyrja\Tests\Unit\TestCase;

class ArrayableTest extends TestCase
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
