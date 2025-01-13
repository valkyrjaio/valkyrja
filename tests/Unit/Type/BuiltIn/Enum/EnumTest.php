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

use Valkyrja\Tests\Classes\Enum\Enum;
use Valkyrja\Tests\Classes\Enum\IntEnum;
use Valkyrja\Tests\Classes\Enum\StringEnum;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Exception\InvalidArgumentException;
use Valkyrja\Type\Exception\RuntimeException;

use function json_encode;

class EnumTest extends TestCase
{
    protected const VALUE = Enum::club;

    public function testFromValueStatic(): void
    {
        $type = Enum::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValueBackedEnum(): void
    {
        $type = Enum::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValueUnitEnum(): void
    {
        $type = Enum::fromValue(self::VALUE->name);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValueInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $type = Enum::fromValue('invalid');

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testAsFlatValueStringBackedEnum(): void
    {
        $type = StringEnum::foo;

        self::assertSame($type->value, $type->asFlatValue());
    }

    public function testAsFlatValueIntBackedEnum(): void
    {
        $type = IntEnum::first;

        self::assertSame($type->value, $type->asFlatValue());
    }

    public function testAsFlatValueUnitEnum(): void
    {
        $type = self::VALUE;

        self::assertSame(self::VALUE->name, $type->asFlatValue());
    }

    public function testModify(): void
    {
        $this->expectException(RuntimeException::class);

        $type = self::VALUE;

        $type->modify(static fn (Enum $subject): Enum => Enum::heart);
    }

    public function testJsonSerialize(): void
    {
        $type = self::VALUE;

        self::assertSame(json_encode(self::VALUE), json_encode($type));
    }
}
