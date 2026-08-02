<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Object;

use JsonException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Object\ObjectT;

use function json_encode;

final class ObjectTest extends TestCase
{
    public function testValue(): void
    {
        $value = new class {
        };
        $type  = new ObjectT($value);

        self::assertSame($value, $type->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $value         = new class {
        };
        $typeFromValue = ObjectT::fromValue($value);

        self::assertSame($value, $typeFromValue->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $value = new class {
        };
        $type  = new ObjectT($value);

        self::assertSame(json_encode($value), $type->asFlatValue());
    }

    public function testModify(): void
    {
        $value = new class {
            public string $foo = 'test';
        };
        $type  = new ObjectT($value);
        // The new value
        $newValue = 'bar';

        $modified = $type->modify(static function (object $subject) use ($newValue): object {
            $subject->foo = $newValue;

            return $subject;
        });

        // Original should be unmodified
        self::assertSame('test', $type->asValue()->foo);
        self::assertSame($value, $type->asValue());
        // New should be modified
        self::assertSame($newValue, $modified->asValue()->foo);
    }

    public function testJsonSerialize(): void
    {
        $value = new class {
            public string $pie = 'pie';
        };
        $type  = new ObjectT($value);

        self::assertSame(json_encode($value), json_encode($type));
    }
}
