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
use stdClass;
use Valkyrja\Tests\Fixtures\Type\Model\ModelFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Object\SerializedObject;

use function json_encode;
use function serialize;

final class SerializedObjectTest extends TestCase
{
    public function testValue(): void
    {
        $value = new ModelFixture();
        $type  = new SerializedObject($value);

        self::assertSame($type->asValue(), $value);
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $value         = new ModelFixture();
        $typeFromValue = SerializedObject::fromValue($value);

        self::assertSame($typeFromValue->asValue(), $value);
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $value = new ModelFixture();
        $type  = new SerializedObject($value);

        self::assertSame($type->asFlatValue(), serialize($value));
    }

    /**
     * @throws JsonException
     */
    public function testModify(): void
    {
        $value = ModelFixture::fromArray(['public' => 'test']);
        $type  = new SerializedObject($value);
        // The new value
        $newValue = 'bar';

        $modified = $type->modify(static function (object $subject) use ($newValue): object {
            self::assertInstanceOf(ModelFixture::class, $subject);

            $subject->public = $newValue;

            return $subject;
        });

        // Original should be unmodified
        self::assertSame($type->asValue(), $value);
        $original = $type->asValue();

        self::assertInstanceOf(ModelFixture::class, $original);
        self::assertSame('test', $original->public);
        // New should be modified
        $new = $modified->asValue();

        self::assertInstanceOf(ModelFixture::class, $new);
        self::assertSame($newValue, $new->public);
    }

    public function testJsonSerialize(): void
    {
        $value = new ModelFixture();
        $type  = new SerializedObject($value);

        self::assertSame(json_encode($type), json_encode($value));
    }

    public function testFromValueUnserializesString(): void
    {
        $serialized = serialize((object) ['foo' => 'bar']);

        $type = SerializedObject::fromValue($serialized, [stdClass::class]);

        self::assertSame(['foo' => 'bar'], (array) $type->asValue());
    }
}
