<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Data;

use Valkyrja\Orm\Data\EntityCast;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;

final class EntityCastTest extends TestCase
{
    public function testExtendsCast(): void
    {
        $cast = new EntityCast(CastType::string);

        self::assertInstanceOf(Cast::class, $cast);
    }

    public function testTypeProperty(): void
    {
        $cast = new EntityCast(CastType::string);

        self::assertSame(CastType::string->value, $cast->type);
    }

    public function testColumnPropertyDefaultsToNull(): void
    {
        $cast = new EntityCast(CastType::string);

        self::assertNull($cast->column);
    }

    public function testColumnPropertyCanBeSet(): void
    {
        $cast = new EntityCast(CastType::string, 'user_id');

        self::assertSame('user_id', $cast->column);
    }

    public function testConvertPropertyDefaultsToTrue(): void
    {
        $cast = new EntityCast(CastType::string);

        self::assertTrue($cast->convert);
    }

    public function testConvertPropertyCanBeSet(): void
    {
        $cast = new EntityCast(CastType::string, null, false);

        self::assertFalse($cast->convert);
    }

    public function testIsArrayPropertyDefaultsToFalse(): void
    {
        $cast = new EntityCast(CastType::string);

        self::assertFalse($cast->isArray);
    }

    public function testIsArrayPropertyCanBeSet(): void
    {
        $cast = new EntityCast(CastType::string, null, true, true);

        self::assertTrue($cast->isArray);
    }

    public function testAllPropertiesCanBeSetTogether(): void
    {
        $cast = new EntityCast(
            type: CastType::int,
            column: 'entity_id',
            convert: false,
            isArray: true
        );

        self::assertSame(CastType::int->value, $cast->type);
        self::assertSame('entity_id', $cast->column);
        self::assertFalse($cast->convert);
        self::assertTrue($cast->isArray);
    }

    public function testWithStringType(): void
    {
        // This tests using a class-string for the type instead of CastType enum
        $cast = new EntityCast(CastType::string->value);

        self::assertSame(CastType::string->value, $cast->type);
    }
}
