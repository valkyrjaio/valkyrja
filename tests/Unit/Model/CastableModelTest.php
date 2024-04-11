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

namespace Valkyrja\Tests\Unit\Model;

use Valkyrja\Tests\Classes\Enums\Enum;
use Valkyrja\Tests\Classes\Enums\IntEnum;
use Valkyrja\Tests\Classes\Enums\StringEnum;
use Valkyrja\Tests\Classes\Model\CastableModel;
use Valkyrja\Tests\Classes\Model\Model;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Types\ArrayT;
use Valkyrja\Type\Types\BoolT;
use Valkyrja\Type\Types\DoubleT;
use Valkyrja\Type\Types\FalseT;
use Valkyrja\Type\Types\FloatT;
use Valkyrja\Type\Types\IntT;
use Valkyrja\Type\Types\JsonObject;
use Valkyrja\Type\Types\Json;
use Valkyrja\Type\Types\NullT;
use Valkyrja\Type\Types\ObjectT;
use Valkyrja\Type\Types\SerializedObject;
use Valkyrja\Type\Types\StringT;
use Valkyrja\Type\Types\TrueT;

/**
 * Test the castable model.
 *
 * @author Melech Mizrachi
 */
class CastableModelTest extends TestCase
{
    public function testArrayCast(): void
    {
        $value = ['test'];

        // Test a normal array
        $this->propertyTest(CastableModel::ARRAY_PROPERTY, $value, $value);
        // Test an ArrayT object directly
        $this->propertyTest(CastableModel::ARRAY_PROPERTY, new ArrayT($value), $value);
        // Test a stringified array
        $this->propertyTest(CastableModel::ARRAY_PROPERTY, json_encode($value), $value);
        // Test an array of arrays
        $this->propertyTest(CastableModel::ARRAY_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of ArrayT objects
        $this->propertyTest(CastableModel::ARRAY_ARRAY_PROPERTY, [new ArrayT($value)], [$value]);
        // Test an array of stringified arrays
        $this->propertyTest(
            CastableModel::ARRAY_ARRAY_PROPERTY,
            [json_encode($value), json_encode($value)],
            [$value, $value]
        );
    }

    public function testBoolCast(): void
    {
        // Test a normal bool
        $this->propertyTest(CastableModel::BOOL_PROPERTY, true, true);
        // Test a BoolT object directly
        $this->propertyTest(CastableModel::BOOL_PROPERTY, new BoolT(true), true);
        // Test a string
        $this->propertyTest(CastableModel::BOOL_PROPERTY, '1', true);
        // Test an int
        $this->propertyTest(CastableModel::BOOL_PROPERTY, 1, true);
        // Test an array
        $this->propertyTest(CastableModel::BOOL_PROPERTY, [true], true);
        // Test an array of booleans
        $this->propertyTest(CastableModel::BOOL_ARRAY_PROPERTY, [true], [true]);
        // Test an array of BoolT objects
        $this->propertyTest(CastableModel::BOOL_ARRAY_PROPERTY, [new BoolT(true)], [true]);
        // Test an array of strings
        $this->propertyTest(CastableModel::BOOL_ARRAY_PROPERTY, ['1'], [true]);
        // Test an array of ints
        $this->propertyTest(CastableModel::BOOL_ARRAY_PROPERTY, [1], [true]);
        // Test an array of arrays
        $this->propertyTest(CastableModel::BOOL_ARRAY_PROPERTY, [[true]], [true]);
    }

    public function testDoubleCast(): void
    {
        $value = 0.02;

        // Test a normal double
        $this->propertyTest(CastableModel::DOUBLE_PROPERTY, $value, $value);
        // Test a DoubleT object directly
        $this->propertyTest(CastableModel::DOUBLE_PROPERTY, new DoubleT($value), $value);
        // Test a string
        $this->propertyTest(CastableModel::DOUBLE_PROPERTY, (string) $value, $value);
        // Test an int (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::DOUBLE_PROPERTY, (int) $value, 0.0);
        // Test an array of doubles
        $this->propertyTest(CastableModel::DOUBLE_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of DoubleT objects
        $this->propertyTest(CastableModel::DOUBLE_ARRAY_PROPERTY, [new DoubleT($value)], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModel::DOUBLE_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of ints (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::DOUBLE_ARRAY_PROPERTY, [(int) $value], [0.0]);
    }

    public function testFloatCast(): void
    {
        $value = 0.00;

        // Test a normal float
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, $value, $value);
        // Test a FloatT object directly
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, new FloatT($value), $value);
        // Test a string
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, (string) $value, $value);
        // Test an int
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, (int) $value, $value);
        // Test an array of floats
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of FloatT objects
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [new FloatT($value)], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of ints
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [(int) $value], [$value]);

        $value = 0.01;

        // Test a normal float
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, $value, $value);
        // Test a FloatT object directly
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, new FloatT($value), $value);
        // Test a string
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, (string) $value, $value);
        // Test an int (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, (int) $value, 0.0);
        // Test an array (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::FLOAT_PROPERTY, (array) $value, 1.0);
        // Test an array of floats
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of FloatT objects
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [new FloatT($value)], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of ints (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [(int) $value], [0.0]);
        // Test an array of arrays (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::FLOAT_ARRAY_PROPERTY, [(array) $value], [1.0]);
    }

    public function testIntCast(): void
    {
        $value = 0;

        // Test a normal int
        $this->propertyTest(CastableModel::INT_PROPERTY, $value, $value);
        // Test an IntT object directly
        $this->propertyTest(CastableModel::INT_PROPERTY, new IntT($value), $value);
        // Test a string
        $this->propertyTest(CastableModel::INT_PROPERTY, (string) $value, $value);
        // Test a bool
        $this->propertyTest(CastableModel::INT_PROPERTY, (bool) $value, $value);
        // Test a float
        $this->propertyTest(CastableModel::INT_PROPERTY, (float) $value, $value);
        // Test a array (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::INT_PROPERTY, (array) $value, 1);
        // Test an array of ints
        $this->propertyTest(CastableModel::INT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of IntT objects
        $this->propertyTest(CastableModel::INT_ARRAY_PROPERTY, [new IntT($value)], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModel::INT_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of booleans
        $this->propertyTest(CastableModel::INT_ARRAY_PROPERTY, [(bool) $value], [$value]);
        // Test an array of floats
        $this->propertyTest(CastableModel::INT_ARRAY_PROPERTY, [(float) $value], [$value]);
        // Test an array of arrays (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::INT_ARRAY_PROPERTY, [(array) $value], [1]);
    }

    public function testStringCast(): void
    {
        $value = '0';

        // Test a normal int
        $this->propertyTest(CastableModel::STRING_PROPERTY, $value, $value);
        // Test a StringT object directly
        $this->propertyTest(CastableModel::STRING_PROPERTY, new StringT($value), $value);
        // Test a int
        $this->propertyTest(CastableModel::STRING_PROPERTY, (int) $value, $value);
        // Test a float
        $this->propertyTest(CastableModel::STRING_PROPERTY, (float) $value, $value);
        // Test a bool (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::STRING_PROPERTY, (bool) $value, '');
        // Test an array of ints
        $this->propertyTest(CastableModel::STRING_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of StringT objects
        $this->propertyTest(CastableModel::STRING_ARRAY_PROPERTY, [new StringT($value)], [$value]);
        // Test an array of ints
        $this->propertyTest(CastableModel::STRING_ARRAY_PROPERTY, [(int) $value], [$value]);
        // Test an array of floats
        $this->propertyTest(CastableModel::STRING_ARRAY_PROPERTY, [(float) $value], [$value]);
        // Test an array of booleans (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModel::STRING_ARRAY_PROPERTY, [(bool) $value], ['']);
    }

    public function testObjectCast(): void
    {
        $value = new class {
            public int $test = 0;
        };

        // Test a normal object
        $this->propertyTest(CastableModel::OBJECT_PROPERTY, $value, $value);
        // Test an ObjectT object directly
        $this->propertyTest(CastableModel::OBJECT_PROPERTY, new ObjectT($value), $value);

        // Test a stringified object
        $model = $this->propertyTest(CastableModel::OBJECT_PROPERTY, json_encode($value), $value, true);
        self::assertIsObject($model->object);
        self::assertObjectHasProperty('test', $model->object);

        // Test an array of objects
        $this->propertyTest(CastableModel::OBJECT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of ObjectT objects
        $this->propertyTest(CastableModel::OBJECT_ARRAY_PROPERTY, [new ObjectT($value)], [$value]);

        // Test an array of stringified objects
        $model = $this->propertyTest(
            CastableModel::OBJECT_ARRAY_PROPERTY,
            [json_encode($value), json_encode($value)],
            [$value, $value],
            true
        );
        self::assertIsObject($model->objectArray[0] ?? null);
        self::assertObjectHasProperty('test', $model->objectArray[0]);
        self::assertIsObject($model->objectArray[1] ?? null);
        self::assertObjectHasProperty('test', $model->objectArray[1]);
    }

    public function testSerializedObjectCast(): void
    {
        $value = new Model();

        // Test a normal object
        $this->propertyTest(CastableModel::SERIALIZED_OBJECT_PROPERTY, $value, $value);
        // Test a SerializedObjectT object directly
        $this->propertyTest(CastableModel::SERIALIZED_OBJECT_PROPERTY, new SerializedObject($value), $value);

        // Test a stringified object
        $model = $this->propertyTest(CastableModel::SERIALIZED_OBJECT_PROPERTY, serialize($value), $value, true);
        self::assertIsObject($model->serializedObject);

        // Test an array of objects
        $this->propertyTest(CastableModel::SERIALIZED_OBJECT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of SerializedObjectT objects
        $this->propertyTest(CastableModel::SERIALIZED_OBJECT_ARRAY_PROPERTY, [new SerializedObject($value)], [$value]);

        // Test an array of stringified objects
        $model = $this->propertyTest(
            CastableModel::SERIALIZED_OBJECT_ARRAY_PROPERTY,
            [serialize($value), serialize($value)],
            [$value, $value],
            true
        );
        self::assertIsObject($model->serializedObjectArray[0] ?? null);
        self::assertIsObject($model->serializedObjectArray[1] ?? null);
    }

    public function testJsonCast(): void
    {
        $value = ['test'];

        // Test a normal json array
        $this->propertyTest(CastableModel::JSON_PROPERTY, $value, $value);
        // Test a JsonT object directly
        $this->propertyTest(CastableModel::JSON_PROPERTY, new Json($value), $value);
        // Test a stringified json array
        $this->propertyTest(CastableModel::JSON_PROPERTY, json_encode($value), $value);
        // Test an array of json arrays
        $this->propertyTest(CastableModel::JSON_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of JsonT objects
        $this->propertyTest(CastableModel::JSON_ARRAY_PROPERTY, [new Json($value)], [$value]);
        // Test an array of stringified json arrays
        $this->propertyTest(
            CastableModel::JSON_ARRAY_PROPERTY,
            [json_encode($value), json_encode($value)],
            [$value, $value]
        );
    }

    public function testJsonObjectCast(): void
    {
        $value = new class {
            public int $test = 0;
        };

        // Test a normal object
        $this->propertyTest(CastableModel::JSON_OBJECT_PROPERTY, $value, $value);
        // Test a JsonObjectT object directly
        $this->propertyTest(CastableModel::JSON_OBJECT_PROPERTY, new JsonObject($value), $value);

        // Test a stringified object
        $model = $this->propertyTest(CastableModel::JSON_OBJECT_PROPERTY, json_encode($value), $value, true);
        self::assertIsObject($model->jsonObject);
        self::assertObjectHasProperty('test', $model->jsonObject);

        // Test an array of objects
        $this->propertyTest(CastableModel::JSON_OBJECT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of JsonObjectT objects
        $this->propertyTest(CastableModel::JSON_OBJECT_ARRAY_PROPERTY, [new JsonObject($value)], [$value]);

        // Test an array of stringified objects
        $model = $this->propertyTest(
            CastableModel::JSON_OBJECT_ARRAY_PROPERTY,
            [json_encode($value), json_encode($value)],
            [$value, $value],
            true
        );
        self::assertIsObject($model->jsonObjectArray[0] ?? null);
        self::assertObjectHasProperty('test', $model->jsonObjectArray[0]);
        self::assertIsObject($model->jsonObjectArray[1] ?? null);
        self::assertObjectHasProperty('test', $model->jsonObjectArray[1]);
    }

    public function testTrueCast(): void
    {
        // Should not matter what we pass, we should always get true

        // Test a normal true
        $this->propertyTest(CastableModel::TRUE_PROPERTY, true, true);
        // Test a TrueT object directly
        $this->propertyTest(CastableModel::TRUE_PROPERTY, new TrueT(), true);
        // Test a string
        $this->propertyTest(CastableModel::TRUE_PROPERTY, '1', true);
        // Test a int
        $this->propertyTest(CastableModel::TRUE_PROPERTY, 1, true);
        // Test a float
        $this->propertyTest(CastableModel::TRUE_PROPERTY, 1.0, true);
        // Test a false
        $this->propertyTest(CastableModel::TRUE_PROPERTY, false, true);
        // Test an array
        $this->propertyTest(CastableModel::TRUE_PROPERTY, [], true);
        // Test an object
        $this->propertyTest(CastableModel::TRUE_PROPERTY, new class {}, true);
        // Test an array of true
        $this->propertyTest(CastableModel::TRUE_ARRAY_PROPERTY, [true], [true]);
        // Test an array of TrueT objects
        $this->propertyTest(CastableModel::TRUE_ARRAY_PROPERTY, [new TrueT()], [true]);
        // Test an array of strings
        $this->propertyTest(CastableModel::TRUE_ARRAY_PROPERTY, ['1'], [true]);
        // Test an array of ints
        $this->propertyTest(CastableModel::TRUE_ARRAY_PROPERTY, [1], [true]);
        // Test an array of floats
        $this->propertyTest(CastableModel::TRUE_ARRAY_PROPERTY, [1.0], [true]);
        // Test an array of false
        $this->propertyTest(CastableModel::TRUE_ARRAY_PROPERTY, [false], [true]);
        // Test an array of arrays
        $this->propertyTest(CastableModel::TRUE_ARRAY_PROPERTY, [[]], [true]);
        // Test an array of objects
        $this->propertyTest(CastableModel::TRUE_ARRAY_PROPERTY, [new class {}], [true]);
    }

    public function testFalseCast(): void
    {
        // Should not matter what we pass, we should always get false

        // Test a normal false
        $this->propertyTest(CastableModel::FALSE_PROPERTY, false, false);
        // Test a FalseT object directly
        $this->propertyTest(CastableModel::FALSE_PROPERTY, new FalseT(), false);
        // Test a string
        $this->propertyTest(CastableModel::FALSE_PROPERTY, '0', false);
        // Test a int
        $this->propertyTest(CastableModel::FALSE_PROPERTY, 0, false);
        // Test a float
        $this->propertyTest(CastableModel::FALSE_PROPERTY, 0.0, false);
        // Test a true
        $this->propertyTest(CastableModel::FALSE_PROPERTY, true, false);
        // Test an array
        $this->propertyTest(CastableModel::FALSE_PROPERTY, [], false);
        // Test an object
        $this->propertyTest(CastableModel::FALSE_PROPERTY, new class {}, false);
        // Test an array of false
        $this->propertyTest(CastableModel::FALSE_ARRAY_PROPERTY, [false], [false]);
        // Test an array of FalseT objects
        $this->propertyTest(CastableModel::FALSE_ARRAY_PROPERTY, [new FalseT()], [false]);
        // Test an array of strings
        $this->propertyTest(CastableModel::FALSE_ARRAY_PROPERTY, ['0'], [false]);
        // Test an array of ints
        $this->propertyTest(CastableModel::FALSE_ARRAY_PROPERTY, [0], [false]);
        // Test an array of floats
        $this->propertyTest(CastableModel::FALSE_ARRAY_PROPERTY, [0.0], [false]);
        // Test an array of true
        $this->propertyTest(CastableModel::FALSE_ARRAY_PROPERTY, [true], [false]);
        // Test an array of arrays
        $this->propertyTest(CastableModel::FALSE_ARRAY_PROPERTY, [[]], [false]);
        // Test an array of objects
        $this->propertyTest(CastableModel::FALSE_ARRAY_PROPERTY, [new class {}], [false]);
    }

    public function testNullCast(): void
    {
        // Should not matter what we pass, we should always get null
        $value = null;

        // Test a normal null
        $this->propertyTest(CastableModel::NULL_PROPERTY, $value, $value);
        // Test a NullT object directly
        $this->propertyTest(CastableModel::NULL_PROPERTY, new NullT(), $value);
        // Test a string
        $this->propertyTest(CastableModel::NULL_PROPERTY, (string) $value, $value);
        // Test a int
        $this->propertyTest(CastableModel::NULL_PROPERTY, (int) $value, $value);
        // Test a float
        $this->propertyTest(CastableModel::NULL_PROPERTY, (float) $value, $value);
        // Test a true
        $this->propertyTest(CastableModel::NULL_PROPERTY, true, $value);
        // Test an array
        $this->propertyTest(CastableModel::NULL_PROPERTY, [], $value);
        // Test an object
        $this->propertyTest(CastableModel::NULL_PROPERTY, new class {}, $value);
        // Test an array of null
        $this->propertyTest(CastableModel::NULL_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of NullT objects
        $this->propertyTest(CastableModel::NULL_ARRAY_PROPERTY, [new NullT()], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModel::NULL_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of ints
        $this->propertyTest(CastableModel::NULL_ARRAY_PROPERTY, [(int) $value], [$value]);
        // Test an array of floats
        $this->propertyTest(CastableModel::NULL_ARRAY_PROPERTY, [(float) $value], [$value]);
        // Test an array of true
        $this->propertyTest(CastableModel::NULL_ARRAY_PROPERTY, [true], [$value]);
        // Test an array of arrays
        $this->propertyTest(CastableModel::NULL_ARRAY_PROPERTY, [[]], [$value]);
        // Test an array of objects
        $this->propertyTest(CastableModel::NULL_ARRAY_PROPERTY, [new class {}], [$value]);
    }

    public function testModelCast(): void
    {
        $value = new Model();

        // Test a normal model
        $this->propertyTest(CastableModel::MODEL_PROPERTY, $value, $value);

        // Test an array
        $model = $this->propertyTest(CastableModel::MODEL_PROPERTY, $value->asArray(), $value, true);
        self::assertIsObject($model->model);

        // Test an json encoded model
        $model = $this->propertyTest(CastableModel::MODEL_PROPERTY, json_encode($model), $value, true);
        self::assertIsObject($model->model);

        // Test an array of models
        $this->propertyTest(CastableModel::MODEL_ARRAY_PROPERTY, [$value], [$value]);

        // Test an array of arrays
        $model = $this->propertyTest(
            CastableModel::MODEL_ARRAY_PROPERTY,
            [$value->asArray(), $value->asArray()],
            [$value, $value],
            true
        );
        self::assertIsObject($model->modelArray[0] ?? null);
        self::assertIsObject($model->modelArray[1] ?? null);

        // Test an array of json encoded models
        $model = $this->propertyTest(
            CastableModel::MODEL_ARRAY_PROPERTY,
            [json_encode($value), json_encode($value)],
            [$value, $value],
            true
        );
        self::assertIsObject($model->modelArray[0] ?? null);
        self::assertIsObject($model->modelArray[1] ?? null);
    }

    public function testEnumCast(): void
    {
        $value = Enum::club;
        $decoded = json_decode(json_encode($value));

        // Test an Enum
        $this->propertyTest(CastableModel::ENUM_PROPERTY, $value, $value);
        // Test a enum name
        $this->propertyTest(CastableModel::ENUM_PROPERTY, $value->name, $value);
        // Test a stringified enum
        $this->propertyTest(CastableModel::ENUM_PROPERTY, $decoded, $value);
        // Test an array of enums
        $this->propertyTest(CastableModel::ENUM_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of enum names
        $this->propertyTest(
            CastableModel::ENUM_ARRAY_PROPERTY,
            [$value->name, $value->name],
            [$value, $value]
        );
        // Test an array of stringified enums
        $this->propertyTest(
            CastableModel::ENUM_ARRAY_PROPERTY,
            [$decoded, $decoded],
            [$value, $value]
        );
    }

    public function testStringEnumCast(): void
    {
        $value = StringEnum::foo;
        $decoded = json_decode(json_encode($value));

        // Test an Enum
        $this->propertyTest(CastableModel::STRING_ENUM_PROPERTY, $value, $value);
        // Test a enum name
        $this->propertyTest(CastableModel::STRING_ENUM_PROPERTY, $value->value, $value);
        // Test a stringified enum
        $this->propertyTest(CastableModel::STRING_ENUM_PROPERTY, $decoded, $value);
        // Test an array of enums
        $this->propertyTest(CastableModel::STRING_ENUM_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of enum names
        $this->propertyTest(
            CastableModel::STRING_ENUM_ARRAY_PROPERTY,
            [$value->value, $value->value],
            [$value, $value]
        );
        // Test an array of stringified enums
        $this->propertyTest(
            CastableModel::STRING_ENUM_ARRAY_PROPERTY,
            [$decoded, $decoded],
            [$value, $value]
        );
    }

    public function testIntEnumCast(): void
    {
        $value = IntEnum::first;
        $decoded = json_decode(json_encode($value));

        // Test an Enum
        $this->propertyTest(CastableModel::INT_ENUM_PROPERTY, $value, $value);
        // Test a enum name
        $this->propertyTest(CastableModel::INT_ENUM_PROPERTY, $value->value, $value);
        // Test a stringified enum
        $this->propertyTest(CastableModel::INT_ENUM_PROPERTY, $decoded, $value);
        // Test an array of enums
        $this->propertyTest(CastableModel::INT_ENUM_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of enum names
        $this->propertyTest(
            CastableModel::INT_ENUM_ARRAY_PROPERTY,
            [$value->value, $value->value],
            [$value, $value]
        );
        // Test an array of stringified enums
        $this->propertyTest(
            CastableModel::INT_ENUM_ARRAY_PROPERTY,
            [$decoded, $decoded],
            [$value, $value]
        );
    }

    /**
     * Test a property.
     *
     * @param string $property      The property to test
     * @param mixed  $testValue     The test value
     * @param mixed  $expectedValue The expected resulting value
     * @param bool   $skipTest      [optional] Whether to skip the test and return the model
     *
     * @return CastableModel
     */
    protected function propertyTest(string $property, mixed $testValue, mixed $expectedValue, bool $skipTest = false): CastableModel
    {
        $model = CastableModel::fromArray(
            [
                $property => $testValue,
            ]
        );

        if ($skipTest) {
            return $model;
        }

        self::assertSame($expectedValue, $model->$property);

        return $model;
    }
}
