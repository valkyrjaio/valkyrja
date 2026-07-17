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

        $modified = $type->modify(static function (ModelFixture $subject) use ($newValue): ModelFixture {
            $subject->public = $newValue;

            return $subject;
        });

        // Original should be unmodified
        self::assertSame($type->asValue(), $value);
        self::assertSame('test', $type->asValue()->public);
        // New should be modified
        self::assertSame($newValue, $modified->asValue()->public);
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

        self::assertSame('bar', $type->asValue()->foo);
    }
}
