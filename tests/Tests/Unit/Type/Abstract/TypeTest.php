<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Abstract;

use JsonSerializable;
use Valkyrja\Tests\Fixtures\Type\TypeFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Contract\TypeContract;

use function json_encode;

final class TypeTest extends TestCase
{
    public function testContract(): void
    {
        self::assertMethodExists(TypeContract::class, 'fromValue');
        self::assertMethodExists(TypeContract::class, 'asValue');
        self::assertMethodExists(TypeContract::class, 'asFlatValue');
        self::assertMethodExists(TypeContract::class, 'modify');
        self::assertIsA(JsonSerializable::class, TypeContract::class);
    }

    public function testString(): void
    {
        $value        = 'test';
        $newValue     = 'test2';
        $type         = TypeFixture::fromValue($value);
        $typeModified = $type->modify(static fn ($value) => $newValue);

        self::assertSame($value, $type->asValue());
        self::assertSame($value, $type->asFlatValue());
        self::assertSame(json_encode($value), json_encode($type));

        self::assertSame($newValue, $typeModified->asValue());
        self::assertSame($newValue, $typeModified->asFlatValue());
        self::assertSame(json_encode($newValue), json_encode($typeModified));
    }

    public function testInt(): void
    {
        $value        = 45;
        $newValue     = 43;
        $type         = TypeFixture::fromValue($value);
        $typeModified = $type->modify(static fn ($value) => $newValue);

        self::assertSame($value, $type->asValue());
        self::assertSame($value, $type->asFlatValue());
        self::assertSame(json_encode($value), json_encode($type));

        self::assertSame($newValue, $typeModified->asValue());
        self::assertSame($newValue, $typeModified->asFlatValue());
        self::assertSame(json_encode($newValue), json_encode($typeModified));
    }

    public function testFloat(): void
    {
        $value        = 4.75;
        $newValue     = 52.32;
        $type         = TypeFixture::fromValue($value);
        $typeModified = $type->modify(static fn ($value) => $newValue);

        self::assertSame($value, $type->asValue());
        self::assertSame($value, $type->asFlatValue());
        self::assertSame(json_encode($value), json_encode($type));

        self::assertSame($newValue, $typeModified->asValue());
        self::assertSame($newValue, $typeModified->asFlatValue());
        self::assertSame(json_encode($newValue), json_encode($typeModified));
    }

    public function testBool(): void
    {
        $value        = true;
        $newValue     = false;
        $type         = TypeFixture::fromValue($value);
        $typeModified = $type->modify(static fn ($value) => $newValue);

        self::assertSame($value, $type->asValue());
        self::assertSame($value, $type->asFlatValue());
        self::assertSame(json_encode($value), json_encode($type));

        self::assertSame($newValue, $typeModified->asValue());
        self::assertSame($newValue, $typeModified->asFlatValue());
        self::assertSame(json_encode($newValue), json_encode($typeModified));
    }

    public function testNull(): void
    {
        $value = null;
        $type  = TypeFixture::fromValue($value);

        self::assertSame($value, $type->asValue());
        self::assertSame($value, $type->asFlatValue());
        self::assertSame(json_encode($value), json_encode($type));
    }
}
