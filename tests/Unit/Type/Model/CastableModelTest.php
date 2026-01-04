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

namespace Valkyrja\Tests\Unit\Type\Model;

use JsonException;
use RuntimeException;
use Valkyrja\Tests\Classes\Enum\Enum;
use Valkyrja\Tests\Classes\Enum\IntEnum;
use Valkyrja\Tests\Classes\Enum\StringEnum;
use Valkyrja\Tests\Classes\Model\CastableModelClass;
use Valkyrja\Tests\Classes\Model\EmptyCastableModelClass;
use Valkyrja\Tests\Classes\Model\ModelClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\ArrayT;
use Valkyrja\Type\BuiltIn\BoolT;
use Valkyrja\Type\BuiltIn\FalseT;
use Valkyrja\Type\BuiltIn\FloatT;
use Valkyrja\Type\BuiltIn\IntT;
use Valkyrja\Type\BuiltIn\NullT;
use Valkyrja\Type\BuiltIn\ObjectT;
use Valkyrja\Type\BuiltIn\SerializedObject;
use Valkyrja\Type\BuiltIn\StringT;
use Valkyrja\Type\BuiltIn\TrueT;
use Valkyrja\Type\Json\Json;
use Valkyrja\Type\Json\JsonObject;
use Valkyrja\Type\Model\Contract\CastableModelContract as Contract;
use Valkyrja\Type\Model\Contract\ModelContract;

use function json_decode;
use function json_encode;
use function method_exists;

use const JSON_THROW_ON_ERROR;

/**
 * Test the castable model.
 */
class CastableModelTest extends TestCase
{
    public function testContract(): void
    {
        self::assertTrue(method_exists(Contract::class, 'getCastings'));
        self::isA(ModelContract::class, Contract::class);
    }

    /**
     * @throws JsonException
     */
    public function testArrayCast(): void
    {
        $value = ['test'];

        // Test a normal array
        $this->propertyTest(CastableModelClass::ARRAY_PROPERTY, $value, $value);
        // Test an ArrayT object directly
        $this->propertyTest(CastableModelClass::ARRAY_PROPERTY, new ArrayT($value), $value);
        // Test a stringified array
        $this->propertyTest(CastableModelClass::ARRAY_PROPERTY, json_encode($value, JSON_THROW_ON_ERROR), $value);
        // Test an array of arrays
        $this->propertyTest(CastableModelClass::ARRAY_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of ArrayT objects
        $this->propertyTest(CastableModelClass::ARRAY_ARRAY_PROPERTY, [new ArrayT($value)], [$value]);
        // Test an array of stringified arrays
        $this->propertyTest(
            CastableModelClass::ARRAY_ARRAY_PROPERTY,
            [json_encode($value, JSON_THROW_ON_ERROR), json_encode($value, JSON_THROW_ON_ERROR)],
            [$value, $value]
        );
    }

    public function testBoolCast(): void
    {
        // Test a normal bool
        $this->propertyTest(CastableModelClass::BOOL_PROPERTY, true, true);
        // Test a BoolT object directly
        $this->propertyTest(CastableModelClass::BOOL_PROPERTY, new BoolT(true), true);
        // Test a string
        $this->propertyTest(CastableModelClass::BOOL_PROPERTY, '1', true);
        // Test an int
        $this->propertyTest(CastableModelClass::BOOL_PROPERTY, 1, true);
        // Test an array
        $this->propertyTest(CastableModelClass::BOOL_PROPERTY, [true], true);
        // Test an array of booleans
        $this->propertyTest(CastableModelClass::BOOL_ARRAY_PROPERTY, [true], [true]);
        // Test an array of BoolT objects
        $this->propertyTest(CastableModelClass::BOOL_ARRAY_PROPERTY, [new BoolT(true)], [true]);
        // Test an array of strings
        $this->propertyTest(CastableModelClass::BOOL_ARRAY_PROPERTY, ['1'], [true]);
        // Test an array of ints
        $this->propertyTest(CastableModelClass::BOOL_ARRAY_PROPERTY, [1], [true]);
        // Test an array of arrays
        $this->propertyTest(CastableModelClass::BOOL_ARRAY_PROPERTY, [[true]], [true]);
    }

    public function testFloatCast(): void
    {
        $value = 0.00;

        // Test a normal float
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, $value, $value);
        // Test a FloatT object directly
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, new FloatT($value), $value);
        // Test a string
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, (string) $value, $value);
        // Test an int
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, (int) $value, $value);
        // Test an array of floats
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of FloatT objects
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [new FloatT($value)], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of ints
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [(int) $value], [$value]);

        $value = 0.01;

        // Test a normal float
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, $value, $value);
        // Test a FloatT object directly
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, new FloatT($value), $value);
        // Test a string
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, (string) $value, $value);
        // Test an int (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, (int) $value, 0.0);
        // Test an array (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModelClass::FLOAT_PROPERTY, (array) $value, 1.0);
        // Test an array of floats
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of FloatT objects
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [new FloatT($value)], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of ints (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [(int) $value], [0.0]);
        // Test an array of arrays (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModelClass::FLOAT_ARRAY_PROPERTY, [(array) $value], [1.0]);
    }

    public function testIntCast(): void
    {
        $value = 0;

        // Test a normal int
        $this->propertyTest(CastableModelClass::INT_PROPERTY, $value, $value);
        // Test an IntT object directly
        $this->propertyTest(CastableModelClass::INT_PROPERTY, new IntT($value), $value);
        // Test a string
        $this->propertyTest(CastableModelClass::INT_PROPERTY, (string) $value, $value);
        // Test a bool
        $this->propertyTest(CastableModelClass::INT_PROPERTY, (bool) $value, $value);
        // Test a float
        $this->propertyTest(CastableModelClass::INT_PROPERTY, (float) $value, $value);
        // Test a array (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModelClass::INT_PROPERTY, (array) $value, 1);
        // Test an array of ints
        $this->propertyTest(CastableModelClass::INT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of IntT objects
        $this->propertyTest(CastableModelClass::INT_ARRAY_PROPERTY, [new IntT($value)], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModelClass::INT_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of booleans
        $this->propertyTest(CastableModelClass::INT_ARRAY_PROPERTY, [(bool) $value], [$value]);
        // Test an array of floats
        $this->propertyTest(CastableModelClass::INT_ARRAY_PROPERTY, [(float) $value], [$value]);
        // Test an array of arrays (Notice the unexpected value from automatically casting...)
        $this->propertyTest(CastableModelClass::INT_ARRAY_PROPERTY, [(array) $value], [1]);
    }

    public function testStringCast(): void
    {
        $value = '0';

        // Test a normal int
        $this->propertyTest(CastableModelClass::STRING_PROPERTY, $value, $value);
        // Test a StringT object directly
        $this->propertyTest(CastableModelClass::STRING_PROPERTY, new StringT($value), $value);
        // Test a int
        $this->propertyTest(CastableModelClass::STRING_PROPERTY, (int) $value, $value);
        // Test a float
        $this->propertyTest(CastableModelClass::STRING_PROPERTY, (float) $value, $value);
        // Test a bool
        $this->propertyTest(CastableModelClass::STRING_PROPERTY, (bool) $value, 'false');
        // Test an array of ints
        $this->propertyTest(CastableModelClass::STRING_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of StringT objects
        $this->propertyTest(CastableModelClass::STRING_ARRAY_PROPERTY, [new StringT($value)], [$value]);
        // Test an array of ints
        $this->propertyTest(CastableModelClass::STRING_ARRAY_PROPERTY, [(int) $value], [$value]);
        // Test an array of floats
        $this->propertyTest(CastableModelClass::STRING_ARRAY_PROPERTY, [(float) $value], [$value]);
        // Test an array of booleans
        $this->propertyTest(CastableModelClass::STRING_ARRAY_PROPERTY, [(bool) $value], ['false']);
    }

    /**
     * @throws JsonException
     */
    public function testObjectCast(): void
    {
        $value = new class {
            public int $test = 0;
        };

        // Test a normal object
        $this->propertyTest(CastableModelClass::OBJECT_PROPERTY, $value, $value);
        // Test an ObjectT object directly
        $this->propertyTest(CastableModelClass::OBJECT_PROPERTY, new ObjectT($value), $value);

        // Test a stringified object
        $model = $this->propertyTest(
            CastableModelClass::OBJECT_PROPERTY,
            json_encode($value, JSON_THROW_ON_ERROR),
            $value,
            true
        );
        self::assertIsObject($model->object);
        self::assertObjectHasProperty('test', $model->object);

        // Test an array of objects
        $this->propertyTest(CastableModelClass::OBJECT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of ObjectT objects
        $this->propertyTest(CastableModelClass::OBJECT_ARRAY_PROPERTY, [new ObjectT($value)], [$value]);

        // Test an array of stringified objects
        $model = $this->propertyTest(
            CastableModelClass::OBJECT_ARRAY_PROPERTY,
            [json_encode($value, JSON_THROW_ON_ERROR), json_encode($value, JSON_THROW_ON_ERROR)],
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
        $value = new ModelClass();

        // Test a normal object
        $this->propertyTest(CastableModelClass::SERIALIZED_OBJECT_PROPERTY, $value, $value);
        // Test a SerializedObjectT object directly
        $this->propertyTest(CastableModelClass::SERIALIZED_OBJECT_PROPERTY, new SerializedObject($value), $value);

        // Test a stringified object
        $model = $this->propertyTest(CastableModelClass::SERIALIZED_OBJECT_PROPERTY, serialize($value), $value, true);
        self::assertIsObject($model->serializedObject);

        // Test an array of objects
        $this->propertyTest(CastableModelClass::SERIALIZED_OBJECT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of SerializedObjectT objects
        $this->propertyTest(CastableModelClass::SERIALIZED_OBJECT_ARRAY_PROPERTY, [new SerializedObject($value)], [$value]);

        // Test an array of stringified objects
        $model = $this->propertyTest(
            CastableModelClass::SERIALIZED_OBJECT_ARRAY_PROPERTY,
            [serialize($value), serialize($value)],
            [$value, $value],
            true
        );
        self::assertIsObject($model->serializedObjectArray[0] ?? null);
        self::assertIsObject($model->serializedObjectArray[1] ?? null);
    }

    /**
     * @throws JsonException
     */
    public function testJsonCast(): void
    {
        $value = ['test'];

        // Test a normal json array
        $this->propertyTest(CastableModelClass::JSON_PROPERTY, $value, $value);
        // Test a JsonT object directly
        $this->propertyTest(CastableModelClass::JSON_PROPERTY, new Json($value), $value);
        // Test a stringified json array
        $this->propertyTest(CastableModelClass::JSON_PROPERTY, json_encode($value, JSON_THROW_ON_ERROR), $value);
        // Test an array of json arrays
        $this->propertyTest(CastableModelClass::JSON_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of JsonT objects
        $this->propertyTest(CastableModelClass::JSON_ARRAY_PROPERTY, [new Json($value)], [$value]);
        // Test an array of stringified json arrays
        $this->propertyTest(
            CastableModelClass::JSON_ARRAY_PROPERTY,
            [json_encode($value, JSON_THROW_ON_ERROR), json_encode($value, JSON_THROW_ON_ERROR)],
            [$value, $value]
        );
    }

    /**
     * @throws JsonException
     */
    public function testJsonObjectCast(): void
    {
        $value = new class {
            public int $test = 0;
        };

        // Test a normal object
        $this->propertyTest(CastableModelClass::JSON_OBJECT_PROPERTY, $value, $value);
        // Test a JsonObjectT object directly
        $this->propertyTest(CastableModelClass::JSON_OBJECT_PROPERTY, new JsonObject($value), $value);

        // Test a stringified object
        $model = $this->propertyTest(
            CastableModelClass::JSON_OBJECT_PROPERTY,
            json_encode($value, JSON_THROW_ON_ERROR),
            $value,
            true
        );
        self::assertIsObject($model->jsonObject);
        self::assertObjectHasProperty('test', $model->jsonObject);

        // Test an array of objects
        $this->propertyTest(CastableModelClass::JSON_OBJECT_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of JsonObjectT objects
        $this->propertyTest(CastableModelClass::JSON_OBJECT_ARRAY_PROPERTY, [new JsonObject($value)], [$value]);

        // Test an array of stringified objects
        $model = $this->propertyTest(
            CastableModelClass::JSON_OBJECT_ARRAY_PROPERTY,
            [json_encode($value, JSON_THROW_ON_ERROR), json_encode($value, JSON_THROW_ON_ERROR)],
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
        $this->propertyTest(CastableModelClass::TRUE_PROPERTY, true, true);
        // Test a TrueT object directly
        $this->propertyTest(CastableModelClass::TRUE_PROPERTY, new TrueT(), true);
        // Test a string
        $this->propertyTest(CastableModelClass::TRUE_PROPERTY, '1', true);
        // Test a int
        $this->propertyTest(CastableModelClass::TRUE_PROPERTY, 1, true);
        // Test a float
        $this->propertyTest(CastableModelClass::TRUE_PROPERTY, 1.0, true);
        // Test a false
        $this->propertyTest(CastableModelClass::TRUE_PROPERTY, false, true);
        // Test an array
        $this->propertyTest(CastableModelClass::TRUE_PROPERTY, [], true);
        // Test an object
        $this->propertyTest(
            CastableModelClass::TRUE_PROPERTY,
            new class {
            },
            true
        );
        // Test an array of true
        $this->propertyTest(CastableModelClass::TRUE_ARRAY_PROPERTY, [true], [true]);
        // Test an array of TrueT objects
        $this->propertyTest(CastableModelClass::TRUE_ARRAY_PROPERTY, [new TrueT()], [true]);
        // Test an array of strings
        $this->propertyTest(CastableModelClass::TRUE_ARRAY_PROPERTY, ['1'], [true]);
        // Test an array of ints
        $this->propertyTest(CastableModelClass::TRUE_ARRAY_PROPERTY, [1], [true]);
        // Test an array of floats
        $this->propertyTest(CastableModelClass::TRUE_ARRAY_PROPERTY, [1.0], [true]);
        // Test an array of false
        $this->propertyTest(CastableModelClass::TRUE_ARRAY_PROPERTY, [false], [true]);
        // Test an array of arrays
        $this->propertyTest(CastableModelClass::TRUE_ARRAY_PROPERTY, [[]], [true]);
        // Test an array of objects
        $this->propertyTest(CastableModelClass::TRUE_ARRAY_PROPERTY, [
            new class {
            },
        ], [true]);
    }

    public function testFalseCast(): void
    {
        // Should not matter what we pass, we should always get false

        // Test a normal false
        $this->propertyTest(CastableModelClass::FALSE_PROPERTY, false, false);
        // Test a FalseT object directly
        $this->propertyTest(CastableModelClass::FALSE_PROPERTY, new FalseT(), false);
        // Test a string
        $this->propertyTest(CastableModelClass::FALSE_PROPERTY, '0', false);
        // Test a int
        $this->propertyTest(CastableModelClass::FALSE_PROPERTY, 0, false);
        // Test a float
        $this->propertyTest(CastableModelClass::FALSE_PROPERTY, 0.0, false);
        // Test a true
        $this->propertyTest(CastableModelClass::FALSE_PROPERTY, true, false);
        // Test an array
        $this->propertyTest(CastableModelClass::FALSE_PROPERTY, [], false);
        // Test an object
        $this->propertyTest(
            CastableModelClass::FALSE_PROPERTY,
            new class {
            },
            false
        );
        // Test an array of false
        $this->propertyTest(CastableModelClass::FALSE_ARRAY_PROPERTY, [false], [false]);
        // Test an array of FalseT objects
        $this->propertyTest(CastableModelClass::FALSE_ARRAY_PROPERTY, [new FalseT()], [false]);
        // Test an array of strings
        $this->propertyTest(CastableModelClass::FALSE_ARRAY_PROPERTY, ['0'], [false]);
        // Test an array of ints
        $this->propertyTest(CastableModelClass::FALSE_ARRAY_PROPERTY, [0], [false]);
        // Test an array of floats
        $this->propertyTest(CastableModelClass::FALSE_ARRAY_PROPERTY, [0.0], [false]);
        // Test an array of true
        $this->propertyTest(CastableModelClass::FALSE_ARRAY_PROPERTY, [true], [false]);
        // Test an array of arrays
        $this->propertyTest(CastableModelClass::FALSE_ARRAY_PROPERTY, [[]], [false]);
        // Test an array of objects
        $this->propertyTest(CastableModelClass::FALSE_ARRAY_PROPERTY, [
            new class {
            },
        ], [false]);
    }

    public function testNullCast(): void
    {
        // Should not matter what we pass, we should always get null
        $value = null;

        // Test a normal null
        $this->propertyTest(CastableModelClass::NULL_PROPERTY, $value, $value);
        // Test a NullT object directly
        $this->propertyTest(CastableModelClass::NULL_PROPERTY, new NullT(), $value);
        // Test a string
        $this->propertyTest(CastableModelClass::NULL_PROPERTY, (string) $value, $value);
        // Test a int
        $this->propertyTest(CastableModelClass::NULL_PROPERTY, (int) $value, $value);
        // Test a float
        $this->propertyTest(CastableModelClass::NULL_PROPERTY, (float) $value, $value);
        // Test a true
        $this->propertyTest(CastableModelClass::NULL_PROPERTY, true, $value);
        // Test an array
        $this->propertyTest(CastableModelClass::NULL_PROPERTY, [], $value);
        // Test an object
        $this->propertyTest(
            CastableModelClass::NULL_PROPERTY,
            new class {
            },
            $value
        );
        // Test an array of null
        $this->propertyTest(CastableModelClass::NULL_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of NullT objects
        $this->propertyTest(CastableModelClass::NULL_ARRAY_PROPERTY, [new NullT()], [$value]);
        // Test an array of strings
        $this->propertyTest(CastableModelClass::NULL_ARRAY_PROPERTY, [(string) $value], [$value]);
        // Test an array of ints
        $this->propertyTest(CastableModelClass::NULL_ARRAY_PROPERTY, [(int) $value], [$value]);
        // Test an array of floats
        $this->propertyTest(CastableModelClass::NULL_ARRAY_PROPERTY, [(float) $value], [$value]);
        // Test an array of true
        $this->propertyTest(CastableModelClass::NULL_ARRAY_PROPERTY, [true], [$value]);
        // Test an array of arrays
        $this->propertyTest(CastableModelClass::NULL_ARRAY_PROPERTY, [[]], [$value]);
        // Test an array of objects
        $this->propertyTest(CastableModelClass::NULL_ARRAY_PROPERTY, [
            new class {
            },
        ], [$value]);
    }

    /**
     * @throws JsonException
     */
    public function testModelCast(): void
    {
        $value = new ModelClass();

        // Test a normal model
        $this->propertyTest(CastableModelClass::MODEL_PROPERTY, $value, $value);

        // Test an array
        $model = $this->propertyTest(CastableModelClass::MODEL_PROPERTY, $value->asArray(), $value, true);
        self::assertIsObject($model->model);

        // Test an json encoded model
        $model = $this->propertyTest(
            CastableModelClass::MODEL_PROPERTY,
            json_encode($model, JSON_THROW_ON_ERROR),
            $value,
            true
        );
        self::assertIsObject($model->model);

        // Test an array of models
        $this->propertyTest(CastableModelClass::MODEL_ARRAY_PROPERTY, [$value], [$value]);

        // Test an array of arrays
        $model = $this->propertyTest(
            CastableModelClass::MODEL_ARRAY_PROPERTY,
            [$value->asArray(), $value->asArray()],
            [$value, $value],
            true
        );
        self::assertIsObject($model->modelArray[0] ?? null);
        self::assertIsObject($model->modelArray[1] ?? null);

        // Test an array of json encoded models
        $model = $this->propertyTest(
            CastableModelClass::MODEL_ARRAY_PROPERTY,
            [json_encode($value, JSON_THROW_ON_ERROR), json_encode($value, JSON_THROW_ON_ERROR)],
            [$value, $value],
            true
        );
        self::assertIsObject($model->modelArray[0] ?? null);
        self::assertIsObject($model->modelArray[1] ?? null);
    }

    /**
     * @throws JsonException
     */
    public function testEnumCast(): void
    {
        $value   = Enum::club;
        $decoded = json_decode(json_encode($value, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

        // Test an Enum
        $this->propertyTest(CastableModelClass::ENUM_PROPERTY, $value, $value);
        // Test a enum name
        $this->propertyTest(CastableModelClass::ENUM_PROPERTY, $value->name, $value);
        // Test a stringified enum
        $this->propertyTest(CastableModelClass::ENUM_PROPERTY, $decoded, $value);
        // Test an array of enums
        $this->propertyTest(CastableModelClass::ENUM_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of enum names
        $this->propertyTest(
            CastableModelClass::ENUM_ARRAY_PROPERTY,
            [$value->name, $value->name],
            [$value, $value]
        );
        // Test an array of stringified enums
        $this->propertyTest(
            CastableModelClass::ENUM_ARRAY_PROPERTY,
            [$decoded, $decoded],
            [$value, $value]
        );
    }

    /**
     * @throws JsonException
     */
    public function testStringEnumCast(): void
    {
        $value   = StringEnum::foo;
        $decoded = json_decode(json_encode($value, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

        // Test an Enum
        $this->propertyTest(CastableModelClass::STRING_ENUM_PROPERTY, $value, $value);
        // Test a enum name
        $this->propertyTest(CastableModelClass::STRING_ENUM_PROPERTY, $value->value, $value);
        // Test a stringified enum
        $this->propertyTest(CastableModelClass::STRING_ENUM_PROPERTY, $decoded, $value);
        // Test an array of enums
        $this->propertyTest(CastableModelClass::STRING_ENUM_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of enum names
        $this->propertyTest(
            CastableModelClass::STRING_ENUM_ARRAY_PROPERTY,
            [$value->value, $value->value],
            [$value, $value]
        );
        // Test an array of stringified enums
        $this->propertyTest(
            CastableModelClass::STRING_ENUM_ARRAY_PROPERTY,
            [$decoded, $decoded],
            [$value, $value]
        );
    }

    /**
     * @throws JsonException
     */
    public function testIntEnumCast(): void
    {
        $value   = IntEnum::first;
        $decoded = json_decode(json_encode($value, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

        // Test an Enum
        $this->propertyTest(CastableModelClass::INT_ENUM_PROPERTY, $value, $value);
        // Test a enum name
        $this->propertyTest(CastableModelClass::INT_ENUM_PROPERTY, $value->value, $value);
        // Test a stringified enum
        $this->propertyTest(CastableModelClass::INT_ENUM_PROPERTY, $decoded, $value);
        // Test an array of enums
        $this->propertyTest(CastableModelClass::INT_ENUM_ARRAY_PROPERTY, [$value], [$value]);
        // Test an array of enum names
        $this->propertyTest(
            CastableModelClass::INT_ENUM_ARRAY_PROPERTY,
            [$value->value, $value->value],
            [$value, $value]
        );
        // Test an array of stringified enums
        $this->propertyTest(
            CastableModelClass::INT_ENUM_ARRAY_PROPERTY,
            [$decoded, $decoded],
            [$value, $value]
        );
    }

    public function testDefaultEmptyCastings(): void
    {
        $model = new EmptyCastableModelClass();

        self::assertEmpty(EmptyCastableModelClass::getCastings());
        self::assertEmpty($model::getCastings());
    }

    public function testModifyValueClosure(): void
    {
        $this->expectException(RuntimeException::class);

        EmptyCastableModelClass::fromArray(['pie' => 'yes']);
    }

    /**
     * Test a property.
     *
     * @param string $property      The property to test
     * @param mixed  $testValue     The test value
     * @param mixed  $expectedValue The expected resulting value
     * @param bool   $skipTest      [optional] Whether to skip the test and return the model
     */
    protected function propertyTest(
        string $property,
        mixed $testValue,
        mixed $expectedValue,
        bool $skipTest = false
    ): CastableModelClass {
        $model = CastableModelClass::fromArray(
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
