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
use Valkyrja\Tests\Classes\Type\Model\ModelClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Object\SerializedObject;

class SerializedObjectTest extends TestCase
{
    public function testValue(): void
    {
        $value = new ModelClass();
        $type  = new SerializedObject($value);

        self::assertSame($type->asValue(), $value);
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $value         = new ModelClass();
        $typeFromValue = SerializedObject::fromValue($value);

        self::assertSame($typeFromValue->asValue(), $value);
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $value = new ModelClass();
        $type  = new SerializedObject($value);

        self::assertSame($type->asFlatValue(), serialize($value));
    }

    /**
     * @throws JsonException
     */
    public function testModify(): void
    {
        $value = ModelClass::fromArray(['public' => 'test']);
        $type  = new SerializedObject($value);
        // The new value
        $newValue = 'bar';

        $modified = $type->modify(static function (ModelClass $subject) use ($newValue): ModelClass {
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
        $value = new ModelClass();
        $type  = new SerializedObject($value);

        self::assertSame(json_encode($type), json_encode($value));
    }
}
