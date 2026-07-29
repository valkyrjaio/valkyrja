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

use JsonException;
use Valkyrja\Tests\Fixtures\Enum\EnumFixture;
use Valkyrja\Tests\Fixtures\Enum\IntEnum;
use Valkyrja\Tests\Fixtures\Enum\StringEnum;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Enum\Throwable\Exception\EnumCannotModifyException;
use Valkyrja\Type\Enum\Throwable\Exception\EnumInvalidValueException;

use function json_encode;

use const JSON_THROW_ON_ERROR;

final class EnumTest extends TestCase
{
    protected const EnumFixture VALUE = EnumFixture::club;

    public function testFromValueStatic(): void
    {
        $type = EnumFixture::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValueBackedEnum(): void
    {
        $type = EnumFixture::fromValue(self::VALUE);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromValueUnitEnum(): void
    {
        $type = EnumFixture::fromValue(self::VALUE->name);

        self::assertSame(self::VALUE, $type->asValue());
    }

    public function testFromNonStringOrIntValue(): void
    {
        $this->expectException(EnumInvalidValueException::class);

        EnumFixture::fromValue(true);
    }

    public function testFromValueInvalidValue(): void
    {
        $this->expectException(EnumInvalidValueException::class);

        $type = EnumFixture::fromValue('invalid');

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
        $this->expectException(EnumCannotModifyException::class);

        $type = self::VALUE;

        $type->modify(static fn (EnumFixture $subject) => $subject);
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $type = self::VALUE;

        self::assertSame(json_encode(self::VALUE, JSON_THROW_ON_ERROR), json_encode($type, JSON_THROW_ON_ERROR));
    }
}
