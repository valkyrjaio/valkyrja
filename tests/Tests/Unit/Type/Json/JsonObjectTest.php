<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Json;

use JsonException;
use Valkyrja\Tests\Fixtures\Type\Object\ObjectFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Json\JsonObject;

use function json_encode;

final class JsonObjectTest extends TestCase
{
    public function testValue(): void
    {
        $value = new class {
        };
        $type  = new JsonObject($value);

        self::assertSame($value, $type->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $value         = new class {
        };
        $typeFromValue = JsonObject::fromValue($value);

        self::assertSame($value, $typeFromValue->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $value = new class {
        };
        $type  = new JsonObject($value);

        self::assertSame(json_encode($value), $type->asFlatValue());
    }

    /**
     * @throws JsonException
     */
    public function testModify(): void
    {
        $value = new ObjectFixture();
        $type  = new JsonObject($value);
        // The new value
        $newValue = 'bar';

        $modified = $type->modify(static function (object $subject) use ($newValue): object {
            self::assertInstanceOf(ObjectFixture::class, $subject);

            $subject->foo = $newValue;

            return $subject;
        });

        // Original should be unmodified
        $original = $type->asValue();

        self::assertInstanceOf(ObjectFixture::class, $original);
        self::assertSame('test', $original->foo);
        self::assertSame($value, $type->asValue());
        // New should be modified
        $new = $modified->asValue();

        self::assertInstanceOf(ObjectFixture::class, $new);
        self::assertSame($newValue, $new->foo);
    }

    public function testJsonSerialize(): void
    {
        $value = new class {
            public string $pie = 'pie';
        };
        $type  = new JsonObject($value);

        self::assertSame(json_encode($value), json_encode($type));
    }

    /**
     * @throws JsonException
     */
    public function testFromValueDecodesJsonString(): void
    {
        $type = JsonObject::fromValue('{"foo":"bar"}');

        self::assertSame(['foo' => 'bar'], (array) $type->asValue());
    }
}
