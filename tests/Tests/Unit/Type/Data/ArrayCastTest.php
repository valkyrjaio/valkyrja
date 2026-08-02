<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Data;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Data\ArrayCast;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;
use Valkyrja\Type\String\StringT;

use function json_encode;

final class ArrayCastTest extends TestCase
{
    public function testInherits(): void
    {
        self::isA(Cast::class, ArrayCast::class);
    }

    public function testString(): void
    {
        $value = StringT::class;
        $data  = new ArrayCast($value);

        self::assertSame($value, $data->type);
        self::assertTrue($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testStringNoConvert(): void
    {
        $value = StringT::class;
        $data  = new ArrayCast($value, false);

        self::assertSame($value, $data->type);
        self::assertFalse($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testType(): void
    {
        $value = CastType::string;
        $data  = new ArrayCast($value);

        self::assertSame($value->value, $data->type);
        self::assertTrue($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testTypeNoConvert(): void
    {
        $value = CastType::string;
        $data  = new ArrayCast($value, false);

        self::assertSame($value->value, $data->type);
        self::assertFalse($data->convert);
        self::assertTrue($data->isArray);
    }

    public function testJsonSerialize(): void
    {
        $value = StringT::class;
        $data  = new ArrayCast($value);

        self::assertSame(
            json_encode([
                'type'    => $value,
                'convert' => true,
                'isArray' => true,
            ]),
            json_encode($data)
        );
    }
}
