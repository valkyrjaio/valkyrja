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

use ArrayAccess;
use Error;
use JsonException;
use Valkyrja\Tests\Classes\Model\ModelClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Arr;
use Valkyrja\Type\Contract\Type;
use Valkyrja\Type\Model\Contract\Model as Contract;

use function json_encode;
use function method_exists;

use const JSON_THROW_ON_ERROR;

/**
 * Test the abstract model.
 *
 * @author Melech Mizrachi
 */
class ModelTest extends TestCase
{
    public function testClone(): void
    {
        $test  = new ModelClass();
        $test2 = clone $test;

        self::assertNotSame($test2, $test);
    }

    public function testContract(): void
    {
        self::assertTrue(method_exists(Contract::class, 'fromArray'));
        self::assertTrue(method_exists(Contract::class, '__get'));
        self::assertTrue(method_exists(Contract::class, '__set'));
        self::assertTrue(method_exists(Contract::class, '__isset'));
        self::assertTrue(method_exists(Contract::class, 'hasProperty'));
        self::assertTrue(method_exists(Contract::class, 'updateProperties'));
        self::assertTrue(method_exists(Contract::class, 'withProperties'));
        self::assertTrue(method_exists(Contract::class, 'asValue'));
        self::assertTrue(method_exists(Contract::class, 'asFlatValue'));
        self::assertTrue(method_exists(Contract::class, 'asArray'));
        self::assertTrue(method_exists(Contract::class, 'asChangedArray'));
        self::assertTrue(method_exists(Contract::class, 'getOriginalPropertyValue'));
        self::assertTrue(method_exists(Contract::class, 'asOriginalArray'));
        self::assertTrue(method_exists(Contract::class, 'jsonSerialize'));
        self::assertTrue(method_exists(Contract::class, '__toString'));
        self::isA(ArrayAccess::class, Contract::class);
        self::isA(Type::class, Contract::class);
    }

    public function testHas(): void
    {
        $model = new ModelClass();

        self::assertTrue($model->hasProperty(ModelClass::PUBLIC));
        self::assertTrue($model->hasProperty(ModelClass::PROTECTED));
        self::assertTrue($model->hasProperty(ModelClass::PRIVATE));
    }

    public function testGet(): void
    {
        $model = ModelClass::fromArray(ModelClass::VALUES);

        self::assertSame(ModelClass::PUBLIC, $model->public);
        self::assertSame(ModelClass::PROTECTED, $model->protected);
        self::assertSame(ModelClass::PRIVATE, $model->private);

        self::assertSame(ModelClass::PUBLIC, $model[ModelClass::PUBLIC]);
        self::assertSame(ModelClass::PROTECTED, $model[ModelClass::PROTECTED]);
        self::assertSame(ModelClass::PRIVATE, $model[ModelClass::PRIVATE]);

        self::assertSame(ModelClass::PUBLIC, $model->__get(ModelClass::PUBLIC));
        self::assertSame(ModelClass::PROTECTED, $model->__get(ModelClass::PROTECTED));
        self::assertSame(ModelClass::PRIVATE, $model->__get(ModelClass::PRIVATE));

        self::assertSame(ModelClass::PUBLIC, $model->offsetGet(ModelClass::PUBLIC));
        self::assertSame(ModelClass::PROTECTED, $model->offsetGet(ModelClass::PROTECTED));
        self::assertSame(ModelClass::PRIVATE, $model->offsetGet(ModelClass::PRIVATE));
    }

    public function testGetNotSet(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage(
            'Typed property ' . ModelClass::class . '::$public must not be accessed before initialization'
        );

        $model = ModelClass::fromArray([]);

        self::assertSame(ModelClass::PUBLIC, $model->public);
    }

    public function testIsset(): void
    {
        $model = ModelClass::fromArray([]);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
        self::assertFalse(isset($model->private));

        self::assertFalse(isset($model[ModelClass::PUBLIC]));
        self::assertFalse(isset($model[ModelClass::PROTECTED]));
        self::assertFalse(isset($model[ModelClass::PRIVATE]));

        self::assertFalse($model->__isset(ModelClass::PUBLIC));
        self::assertFalse($model->__isset(ModelClass::PROTECTED));
        self::assertFalse($model->__isset(ModelClass::PRIVATE));

        self::assertFalse($model->offsetExists(ModelClass::PUBLIC));
        self::assertFalse($model->offsetExists(ModelClass::PROTECTED));
        self::assertFalse($model->offsetExists(ModelClass::PRIVATE));

        $model = ModelClass::fromArray(ModelClass::VALUES);

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        self::assertTrue(isset($model->private));

        self::assertTrue(isset($model[ModelClass::PUBLIC]));
        self::assertTrue(isset($model[ModelClass::PROTECTED]));
        self::assertTrue(isset($model[ModelClass::PRIVATE]));

        self::assertTrue($model->__isset(ModelClass::PUBLIC));
        self::assertTrue($model->__isset(ModelClass::PROTECTED));
        self::assertTrue($model->__isset(ModelClass::PRIVATE));

        self::assertTrue($model->offsetExists(ModelClass::PUBLIC));
        self::assertTrue($model->offsetExists(ModelClass::PROTECTED));
        self::assertTrue($model->offsetExists(ModelClass::PRIVATE));
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $model = ModelClass::fromValue([]);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
        self::assertFalse(isset($model->private));

        $model = ModelClass::fromValue(ModelClass::VALUES);

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        self::assertTrue(isset($model->private));

        $model = ModelClass::fromValue(json_encode(ModelClass::VALUES));

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        self::assertTrue(isset($model->private));

        $model = ModelClass::fromValue(ModelClass::fromValue(ModelClass::VALUES));

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        self::assertTrue(isset($model->private));

        $model = ModelClass::fromValue(json_encode(ModelClass::fromValue(ModelClass::VALUES)));

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        // Since private fields are not exposed
        self::assertFalse(isset($model->private));
    }

    public function testSet(): void
    {
        $model = ModelClass::fromArray([]);

        $model->public    = ModelClass::PUBLIC;
        $model->protected = ModelClass::PROTECTED;
        $model->private   = ModelClass::PRIVATE;
        $model->nullable  = ModelClass::NULLABLE;

        self::assertSame(ModelClass::PUBLIC, $model->public);
        self::assertSame(ModelClass::PROTECTED, $model->protected);
        self::assertSame(ModelClass::PRIVATE, $model->private);
        self::assertSame(ModelClass::NULLABLE, $model->nullable);

        $model = ModelClass::fromArray([]);

        $model->__set(ModelClass::PUBLIC, ModelClass::PUBLIC);
        $model->__set(ModelClass::PROTECTED, ModelClass::PROTECTED);
        $model->__set(ModelClass::PRIVATE, ModelClass::PRIVATE);
        $model->__set(ModelClass::NULLABLE, ModelClass::NULLABLE);

        self::assertSame(ModelClass::PUBLIC, $model->public);
        self::assertSame(ModelClass::PROTECTED, $model->protected);
        self::assertSame(ModelClass::PRIVATE, $model->private);
        self::assertSame(ModelClass::NULLABLE, $model->nullable);

        $model = ModelClass::fromArray([]);

        $model[ModelClass::PUBLIC]    = ModelClass::PUBLIC;
        $model[ModelClass::PROTECTED] = ModelClass::PROTECTED;
        $model[ModelClass::PRIVATE]   = ModelClass::PRIVATE;
        $model[ModelClass::NULLABLE]  = ModelClass::NULLABLE;

        self::assertSame(ModelClass::PUBLIC, $model->public);
        self::assertSame(ModelClass::PROTECTED, $model->protected);
        self::assertSame(ModelClass::PRIVATE, $model->private);
        self::assertSame(ModelClass::NULLABLE, $model->nullable);

        $model = ModelClass::fromArray([]);

        $model->offsetSet(ModelClass::PUBLIC, ModelClass::PUBLIC);
        $model->offsetSet(ModelClass::PROTECTED, ModelClass::PROTECTED);
        $model->offsetSet(ModelClass::PRIVATE, ModelClass::PRIVATE);
        $model->offsetSet(ModelClass::NULLABLE, ModelClass::NULLABLE);

        self::assertSame(ModelClass::PUBLIC, $model->public);
        self::assertSame(ModelClass::PROTECTED, $model->protected);
        self::assertSame(ModelClass::PRIVATE, $model->private);
        self::assertSame(ModelClass::NULLABLE, $model->nullable);
    }

    public function testUnset(): void
    {
        $model = ModelClass::fromArray(ModelClass::VALUES);

        unset($model[ModelClass::PUBLIC], $model[ModelClass::PROTECTED]);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));

        $model = ModelClass::fromArray(ModelClass::VALUES);

        $model->offsetUnset(ModelClass::PUBLIC);
        $model->offsetUnset(ModelClass::PROTECTED);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
    }

    public function testUnsetPrivateErrors(): void
    {
        $this->expectException(Error::class);

        $model = ModelClass::fromArray(ModelClass::VALUES);

        unset($model[ModelClass::PRIVATE]);
    }

    public function testUnsetMethodPrivateErrors(): void
    {
        $this->expectException(Error::class);

        $model = ModelClass::fromArray(ModelClass::VALUES);

        $model->offsetUnset(ModelClass::PRIVATE);
    }

    public function testWithProperties(): void
    {
        $model    = ModelClass::fromArray([]);
        $newModel = $model->withProperties(ModelClass::VALUES);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
        self::assertFalse(isset($model->private));
        self::assertFalse(isset($model->nullable));

        self::assertTrue(isset($newModel->public));
        self::assertTrue(isset($newModel->protected));
        self::assertTrue(isset($newModel->private));
    }

    public function testOriginal(): void
    {
        $value = 'test';
        $model = ModelClass::fromArray(ModelClass::VALUES);

        $model->public    = $value;
        $model->protected = $value;
        $model->private   = $value;
        $model->nullable  = $value;

        self::assertSame(ModelClass::VALUES, $model->asOriginalArray());
        self::assertSame(ModelClass::PUBLIC, $model->getOriginalPropertyValue(ModelClass::PUBLIC));
        self::assertSame(ModelClass::PROTECTED, $model->getOriginalPropertyValue(ModelClass::PROTECTED));
        self::assertSame(ModelClass::PRIVATE, $model->getOriginalPropertyValue(ModelClass::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::NULLABLE));

        $model = ModelClass::fromArray([]);
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::NULLABLE));
        $model->updateProperties(ModelClass::VALUES);
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::NULLABLE));

        $model = ModelClass::fromArray([]);

        $model->public = ModelClass::PUBLIC;
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(ModelClass::PRIVATE));
    }

    public function testChanged(): void
    {
        // Public properties should show up if changed
        $model = ModelClass::fromArray(ModelClass::VALUES);

        $model->public = 'test';
        self::assertSame([ModelClass::PUBLIC => 'test'], $model->asChangedArray());

        // Protected properties should show up if changed
        $model = ModelClass::fromArray(ModelClass::VALUES);

        $model->protected = 'test';
        self::assertSame([ModelClass::PROTECTED => 'test'], $model->asChangedArray());

        // Private properties should not show up
        $model = ModelClass::fromArray(ModelClass::VALUES);

        $model->private = 'test';
        self::assertSame([], $model->asChangedArray());

        // Private properties should not show up, but public and protected should if changed
        $model = ModelClass::fromArray(ModelClass::VALUES);

        $model->public    = 'test';
        $model->protected = 'test2';
        $model->private   = 'test3';
        self::assertSame([ModelClass::PUBLIC => 'test', ModelClass::PROTECTED => 'test2'], $model->asChangedArray());

        // Because public properties aren't tracked unless through methods then they come up as changed
        $model = ModelClass::fromArray([]);

        $model->public = ModelClass::PUBLIC;
        self::assertSame([ModelClass::PUBLIC => ModelClass::PUBLIC], $model->asChangedArray());
    }

    public function testAsArray(): void
    {
        $model = ModelClass::fromArray([]);
        self::assertSame([], $model->asArray());

        $model = ModelClass::fromArray(ModelClass::VALUES);
        self::assertSame(
            [
                ModelClass::PUBLIC    => ModelClass::PUBLIC,
                ModelClass::NULLABLE  => null,
                ModelClass::PROTECTED => ModelClass::PROTECTED,
            ],
            $model->asArray()
        );
        self::assertSame([ModelClass::PUBLIC => ModelClass::PUBLIC], $model->asArray(ModelClass::PUBLIC));
        self::assertSame([ModelClass::PROTECTED => ModelClass::PROTECTED], $model->asArray(ModelClass::PROTECTED));
        // Private or hidden properties should not be exposable.
        self::assertSame([], $model->asArray(ModelClass::PRIVATE));
    }

    public function testAsValue(): void
    {
        $test = new ModelClass();

        self::assertSame($test, $test->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $model = ModelClass::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = ModelClass::fromArray(ModelClass::VALUES);

        $expected = '{"public":"public","nullable":null,"protected":"protected"}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }

    public function testCloning(): void
    {
        $model = ModelClass::fromArray([]);

        $cloned = clone $model;

        self::assertSame($model->asChangedArray(), $cloned->asChangedArray());

        $updatedValue   = 'fire';
        $cloned->public = $updatedValue;

        self::assertNotSame($model, $cloned);
        self::assertNotSame($model->asChangedArray(), $cloned->asChangedArray());
        self::assertEmpty($model->asChangedArray());
        self::assertSame([ModelClass::PUBLIC => $updatedValue], $cloned->asChangedArray());
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $value = ['public' => 'cheese'];
        $model = ModelClass::fromArray($value);

        self::assertSame(Arr::toString($value), $model->asFlatValue());
    }

    public function testModify(): void
    {
        $value    = 'cheese';
        $newValue = 'fire';
        $model    = ModelClass::fromArray(['public' => $value]);

        $modified = $model->modify(static function (ModelClass $model) use ($newValue): ModelClass {
            $model->public = $newValue;

            return $model;
        });

        self::assertSame($value, $model->public);
        self::assertSame($newValue, $modified->public);
    }
}
