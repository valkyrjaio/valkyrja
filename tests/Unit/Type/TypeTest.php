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

namespace Valkyrja\Tests\Unit\Type;

use JsonSerializable;
use Valkyrja\Tests\Classes\Type\Type;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Type as Contract;

use function json_encode;

class TypeTest extends TestCase
{
    public function testContract(): void
    {
        self::assertMethodExists(Contract::class, 'fromValue');
        self::assertMethodExists(Contract::class, 'asValue');
        self::assertMethodExists(Contract::class, 'asFlatValue');
        self::assertMethodExists(Contract::class, 'modify');
        self::assertIsA(JsonSerializable::class, Contract::class);
    }

    public function testString(): void
    {
        $value        = 'test';
        $newValue     = 'test2';
        $type         = Type::fromValue($value);
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
        $type         = Type::fromValue($value);
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
        $type         = Type::fromValue($value);
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
        $type         = Type::fromValue($value);
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
        $type  = Type::fromValue($value);

        self::assertSame($value, $type->asValue());
        self::assertSame($value, $type->asFlatValue());
        self::assertSame(json_encode($value), json_encode($type));
    }
}
