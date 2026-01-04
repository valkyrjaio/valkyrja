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

namespace Valkyrja\Tests\Unit\Type\Abstract;

use JsonSerializable;
use Valkyrja\Tests\Classes\Type\TypeClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Contract\TypeContract;

use function json_encode;

class TypeTest extends TestCase
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
        $type         = TypeClass::fromValue($value);
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
        $type         = TypeClass::fromValue($value);
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
        $type         = TypeClass::fromValue($value);
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
        $type         = TypeClass::fromValue($value);
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
        $type  = TypeClass::fromValue($value);

        self::assertSame($value, $type->asValue());
        self::assertSame($value, $type->asFlatValue());
        self::assertSame(json_encode($value), json_encode($type));
    }
}
