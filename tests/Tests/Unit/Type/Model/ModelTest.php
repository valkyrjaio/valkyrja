<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Model;

use ArrayAccess;
use Closure;
use Error;
use JsonException;
use ReflectionClass;
use TypeError;
use Valkyrja\Tests\Fixtures\Type\Model\ModelFixture;
use Valkyrja\Tests\Fixtures\Type\Model\ModelInvalidIssetMethodFixture;
use Valkyrja\Tests\Fixtures\Type\Model\SimpleModelFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Array\Factory\ArrayFactory;
use Valkyrja\Type\Contract\TypeContract;
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Contract\ModelContract;

use function is_scalar;
use function json_encode;
use function strtoupper;

use const JSON_THROW_ON_ERROR;

/**
 * Test the abstract model.
 */
final class ModelTest extends TestCase
{
    public function testClone(): void
    {
        $test  = new ModelFixture();
        $test2 = clone $test;

        self::assertNotSame($test2, $test);
    }

    public function testContract(): void
    {
        $reflection = new ReflectionClass(ModelContract::class);

        self::assertTrue($reflection->hasMethod('fromArray'));
        self::assertTrue($reflection->hasMethod('__get'));
        self::assertTrue($reflection->hasMethod('__set'));
        self::assertTrue($reflection->hasMethod('__isset'));
        self::assertTrue($reflection->hasMethod('hasProperty'));
        self::assertTrue($reflection->hasMethod('updateProperties'));
        self::assertTrue($reflection->hasMethod('withProperties'));
        self::assertTrue($reflection->hasMethod('asValue'));
        self::assertTrue($reflection->hasMethod('asFlatValue'));
        self::assertTrue($reflection->hasMethod('asArray'));
        self::assertTrue($reflection->hasMethod('asChangedArray'));
        self::assertTrue($reflection->hasMethod('getOriginalPropertyValue'));
        self::assertTrue($reflection->hasMethod('asOriginalArray'));
        self::assertTrue($reflection->hasMethod('jsonSerialize'));
        self::assertTrue($reflection->hasMethod('__toString'));
        self::isA(ArrayAccess::class, ModelContract::class);
        self::isA(TypeContract::class, ModelContract::class);
    }

    public function testDefaults(): void
    {
        $model = new SimpleModelFixture();

        self::assertSame([], $model->asArray());
        self::assertFalse($model->__isset('test'));
        /**
         * @psalm-suppress UndefinedThisPropertyFetch
         *
         * The model's magic accessor takes any name, so there is no property for
         * Psalm to find. Answering for an unknown one is the case under test.
         */
        self::assertNull($model->__get('test'));

        $model->__set('protected', 'value');

        self::assertSame('value', $model->__get('protected'));
    }

    public function testHas(): void
    {
        $model = new ModelFixture();

        self::assertTrue($model->hasProperty(ModelFixture::PUBLIC));
        self::assertTrue($model->hasProperty(ModelFixture::PROTECTED));
        self::assertTrue($model->hasProperty(ModelFixture::PRIVATE));
    }

    public function testGet(): void
    {
        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        self::assertSame(ModelFixture::PUBLIC, $model->public);
        self::assertSame(ModelFixture::PROTECTED, $model->protected);
        self::assertSame(ModelFixture::PRIVATE, $model->private);

        self::assertSame(ModelFixture::PUBLIC, $model[ModelFixture::PUBLIC]);
        self::assertSame(ModelFixture::PROTECTED, $model[ModelFixture::PROTECTED]);
        self::assertSame(ModelFixture::PRIVATE, $model[ModelFixture::PRIVATE]);

        self::assertSame(ModelFixture::PUBLIC, $model->__get(ModelFixture::PUBLIC));
        self::assertSame(ModelFixture::PROTECTED, $model->__get(ModelFixture::PROTECTED));
        self::assertSame(ModelFixture::PRIVATE, $model->__get(ModelFixture::PRIVATE));

        self::assertSame(ModelFixture::PUBLIC, $model->offsetGet(ModelFixture::PUBLIC));
        self::assertSame(ModelFixture::PROTECTED, $model->offsetGet(ModelFixture::PROTECTED));
        self::assertSame(ModelFixture::PRIVATE, $model->offsetGet(ModelFixture::PRIVATE));
    }

    public function testGetNotSet(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage(
            'Typed property ' . ModelFixture::class . '::$public must not be accessed before initialization'
        );

        $model = ModelFixture::fromArray([]);

        self::assertSame(ModelFixture::PUBLIC, $model->public);
    }

    public function testIsset(): void
    {
        $model = ModelFixture::fromArray([]);

        self::assertFalse(isset($model->public));

        self::assertFalse(isset($model[ModelFixture::PUBLIC]));
        self::assertFalse(isset($model[ModelFixture::PROTECTED]));
        self::assertFalse(isset($model[ModelFixture::PRIVATE]));

        self::assertFalse($model->__isset(ModelFixture::PUBLIC));
        self::assertFalse($model->__isset(ModelFixture::PROTECTED));
        self::assertFalse($model->__isset(ModelFixture::PRIVATE));

        self::assertFalse($model->offsetExists(ModelFixture::PUBLIC));
        self::assertFalse($model->offsetExists(ModelFixture::PROTECTED));
        self::assertFalse($model->offsetExists(ModelFixture::PRIVATE));

        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        self::assertTrue(isset($model[ModelFixture::PUBLIC]));
        self::assertTrue(isset($model[ModelFixture::PROTECTED]));
        self::assertTrue(isset($model[ModelFixture::PRIVATE]));

        self::assertTrue($model->__isset(ModelFixture::PUBLIC));
        self::assertTrue($model->__isset(ModelFixture::PROTECTED));
        self::assertTrue($model->__isset(ModelFixture::PRIVATE));

        self::assertTrue($model->offsetExists(ModelFixture::PUBLIC));
        self::assertTrue($model->offsetExists(ModelFixture::PROTECTED));
        self::assertTrue($model->offsetExists(ModelFixture::PRIVATE));
    }

    public function testInvalidIssetReturn(): void
    {
        $this->expectException(TypeError::class);

        $model = new ModelInvalidIssetMethodFixture();
        $model->__isset('test');
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $model = ModelFixture::fromValue([]);

        self::assertFalse(isset($model->public));
        self::assertFalse($model->__isset(ModelFixture::PROTECTED));
        self::assertFalse($model->__isset(ModelFixture::PRIVATE));

        $model = ModelFixture::fromValue(ModelFixture::VALUES);

        self::assertTrue(isset($model->public));
        self::assertTrue($model->__isset(ModelFixture::PROTECTED));
        self::assertTrue($model->__isset(ModelFixture::PRIVATE));

        $model = ModelFixture::fromValue((object) ModelFixture::VALUES);

        self::assertTrue(isset($model->public));
        self::assertTrue($model->__isset(ModelFixture::PROTECTED));
        self::assertTrue($model->__isset(ModelFixture::PRIVATE));

        $model = ModelFixture::fromValue(json_encode(ModelFixture::VALUES));

        self::assertTrue(isset($model->public));
        self::assertTrue($model->__isset(ModelFixture::PROTECTED));
        self::assertTrue($model->__isset(ModelFixture::PRIVATE));

        $model = ModelFixture::fromValue(ModelFixture::fromValue(ModelFixture::VALUES));

        self::assertTrue(isset($model->public));
        self::assertTrue($model->__isset(ModelFixture::PROTECTED));
        self::assertTrue($model->__isset(ModelFixture::PRIVATE));

        $model = ModelFixture::fromValue(json_encode(ModelFixture::fromValue(ModelFixture::VALUES)));

        self::assertTrue(isset($model->public));
        self::assertTrue($model->__isset(ModelFixture::PROTECTED));
        // Since private fields are not exposed
        self::assertFalse($model->__isset(ModelFixture::PRIVATE));
    }

    public function testSet(): void
    {
        $model = ModelFixture::fromArray([]);

        $model->public    = ModelFixture::PUBLIC;
        $model->protected = ModelFixture::PROTECTED;
        $model->private   = ModelFixture::PRIVATE;
        $model->nullable  = ModelFixture::NULLABLE;

        // asArray() reads back through the model's own accessors, so it shows the
        // value __set actually stored. It never exposes a private property, so that
        // one is read the only way it can be.
        self::assertSame(
            [
                ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
                ModelFixture::NULLABLE  => ModelFixture::NULLABLE,
                ModelFixture::PROTECTED => ModelFixture::PROTECTED,
            ],
            $model->asArray()
        );
        /** @psalm-suppress RedundantCondition A private property is not exposed by asArray(), and Psalm folds the __get round trip */
        self::assertSame(ModelFixture::PRIVATE, $model->private);

        $model = ModelFixture::fromArray([]);

        $model->__set(ModelFixture::PUBLIC, ModelFixture::PUBLIC);
        $model->__set(ModelFixture::PROTECTED, ModelFixture::PROTECTED);
        $model->__set(ModelFixture::PRIVATE, ModelFixture::PRIVATE);
        $model->__set(ModelFixture::NULLABLE, ModelFixture::NULLABLE);

        self::assertSame(ModelFixture::PUBLIC, $model->public);
        self::assertSame(ModelFixture::PROTECTED, $model->protected);
        self::assertSame(ModelFixture::PRIVATE, $model->private);
        self::assertSame(ModelFixture::NULLABLE, $model->nullable);

        $model = ModelFixture::fromArray([]);

        $model[ModelFixture::PUBLIC]    = ModelFixture::PUBLIC;
        $model[ModelFixture::PROTECTED] = ModelFixture::PROTECTED;
        $model[ModelFixture::PRIVATE]   = ModelFixture::PRIVATE;
        $model[ModelFixture::NULLABLE]  = ModelFixture::NULLABLE;

        self::assertSame(ModelFixture::PUBLIC, $model->public);
        self::assertSame(ModelFixture::PROTECTED, $model->protected);
        self::assertSame(ModelFixture::PRIVATE, $model->private);
        self::assertSame(ModelFixture::NULLABLE, $model->nullable);

        $model = ModelFixture::fromArray([]);

        $model->offsetSet(ModelFixture::PUBLIC, ModelFixture::PUBLIC);
        $model->offsetSet(ModelFixture::PROTECTED, ModelFixture::PROTECTED);
        $model->offsetSet(ModelFixture::PRIVATE, ModelFixture::PRIVATE);
        $model->offsetSet(ModelFixture::NULLABLE, ModelFixture::NULLABLE);

        self::assertSame(ModelFixture::PUBLIC, $model->public);
        self::assertSame(ModelFixture::PROTECTED, $model->protected);
        self::assertSame(ModelFixture::PRIVATE, $model->private);
        self::assertSame(ModelFixture::NULLABLE, $model->nullable);
    }

    public function testUnset(): void
    {
        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        unset($model[ModelFixture::PUBLIC], $model[ModelFixture::PROTECTED]);

        self::assertFalse(isset($model->public));
        self::assertFalse($model->__isset(ModelFixture::PROTECTED));

        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        $model->offsetUnset(ModelFixture::PUBLIC);
        $model->offsetUnset(ModelFixture::PROTECTED);

        self::assertFalse(isset($model->public));
        self::assertFalse($model->__isset(ModelFixture::PROTECTED));
    }

    public function testUnsetPrivateErrors(): void
    {
        $this->expectException(Error::class);

        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        unset($model[ModelFixture::PRIVATE]);
    }

    public function testUnsetMethodPrivateErrors(): void
    {
        $this->expectException(Error::class);

        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        $model->offsetUnset(ModelFixture::PRIVATE);
    }

    public function testWithProperties(): void
    {
        $model    = ModelFixture::fromArray([]);
        $newModel = $model->withProperties(ModelFixture::VALUES);

        self::assertFalse(isset($model->public));
        self::assertFalse($model->__isset(ModelFixture::PROTECTED));
        self::assertFalse($model->__isset(ModelFixture::PRIVATE));
        self::assertFalse(isset($model->nullable));

        self::assertTrue($newModel->__isset(ModelFixture::PUBLIC));
        self::assertTrue($newModel->__isset(ModelFixture::PROTECTED));
        self::assertTrue($newModel->__isset(ModelFixture::PRIVATE));
    }

    public function testOriginal(): void
    {
        $value = 'test';
        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        $model->public    = $value;
        $model->protected = $value;
        $model->private   = $value;
        $model->nullable  = $value;

        self::assertSame(ModelFixture::VALUES, $model->asOriginalArray());
        self::assertSame(ModelFixture::PUBLIC, $model->getOriginalPropertyValue(ModelFixture::PUBLIC));
        self::assertSame(ModelFixture::PROTECTED, $model->getOriginalPropertyValue(ModelFixture::PROTECTED));
        self::assertSame(ModelFixture::PRIVATE, $model->getOriginalPropertyValue(ModelFixture::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::NULLABLE));

        $model = ModelFixture::fromArray([]);
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::NULLABLE));
        $model->updateProperties(ModelFixture::VALUES);
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::NULLABLE));

        $model = ModelFixture::fromArray([]);

        $model->public = ModelFixture::PUBLIC;
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(ModelFixture::PRIVATE));
    }

    public function testChanged(): void
    {
        // Public properties should show up if changed
        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        $model->public = 'test';
        self::assertSame([ModelFixture::PUBLIC => 'test'], $model->asChangedArray());

        // Protected properties should show up if changed
        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        $model->protected = 'test';
        self::assertSame([ModelFixture::PROTECTED => 'test'], $model->asChangedArray());

        // Private properties should not show up
        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        $model->private = 'test';
        self::assertSame([], $model->asChangedArray());

        // Private properties should not show up, but public and protected should if changed
        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        $model->public    = 'test';
        $model->protected = 'test2';
        $model->private   = 'test3';
        self::assertSame([ModelFixture::PUBLIC => 'test', ModelFixture::PROTECTED => 'test2'], $model->asChangedArray());

        // Because public properties aren't tracked unless through methods then they come up as changed
        $model = ModelFixture::fromArray([]);

        $model->public = ModelFixture::PUBLIC;
        self::assertSame([ModelFixture::PUBLIC => ModelFixture::PUBLIC], $model->asChangedArray());
    }

    public function testAsArray(): void
    {
        $model = ModelFixture::fromArray([]);
        self::assertSame([], $model->asArray());

        $model = ModelFixture::fromArray(ModelFixture::VALUES);
        self::assertSame(
            [
                ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
                ModelFixture::NULLABLE  => null,
                ModelFixture::PROTECTED => ModelFixture::PROTECTED,
            ],
            $model->asArray()
        );
        self::assertSame([ModelFixture::PUBLIC => ModelFixture::PUBLIC], $model->asArray(ModelFixture::PUBLIC));
        self::assertSame([ModelFixture::PROTECTED => ModelFixture::PROTECTED], $model->asArray(ModelFixture::PROTECTED));
        // Private or hidden properties should not be exposable.
        self::assertSame([], $model->asArray(ModelFixture::PRIVATE));
    }

    public function testAsValue(): void
    {
        $test = new ModelFixture();

        self::assertSame($test, $test->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $model = ModelFixture::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = ModelFixture::fromArray(ModelFixture::VALUES);

        $expected = '{"public":"public","nullable":null,"protected":"protected"}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }

    public function testCloning(): void
    {
        $model = ModelFixture::fromArray([]);

        $cloned = clone $model;

        self::assertSame($model->asChangedArray(), $cloned->asChangedArray());

        $updatedValue   = 'fire';
        $cloned->public = $updatedValue;

        self::assertNotSame($model, $cloned);
        self::assertNotSame($model->asChangedArray(), $cloned->asChangedArray());
        self::assertEmpty($model->asChangedArray());
        self::assertSame([ModelFixture::PUBLIC => $updatedValue], $cloned->asChangedArray());
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $value = ['public' => 'cheese'];
        $model = ModelFixture::fromArray($value);

        self::assertSame(ArrayFactory::toString($value), $model->asFlatValue());
    }

    public function testModify(): void
    {
        $value    = 'cheese';
        $newValue = 'fire';
        $model    = ModelFixture::fromArray(['public' => $value]);

        $modified = $model->modify(static function (ModelFixture $model) use ($newValue): ModelFixture {
            $model->public = $newValue;

            return $model;
        });

        self::assertSame($value, $model->public);
        self::assertSame($newValue, $modified->public);
    }

    public function testInternalSetPropertiesAppliesModifyValueClosure(): void
    {
        $model = new class extends Model {
            public string $name = '';

            /**
             * @param array<string, mixed>               $properties
             * @param Closure(string, mixed): mixed|null $modifyValue
             */
            public function applyWithModifier(array $properties, Closure|null $modifyValue): void
            {
                $this->internalSetProperties($properties, $modifyValue);
            }
        };

        $model->applyWithModifier(
            ['name' => 'value'],
            static fn (string $property, mixed $value): string => strtoupper(is_scalar($value) ? (string) $value : ''),
        );

        self::assertSame('VALUE', $model->name);
    }
}
