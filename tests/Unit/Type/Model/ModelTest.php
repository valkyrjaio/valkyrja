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
use Valkyrja\Tests\Classes\Model\Model;
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
        $test  = new Model();
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
        $model = new Model();

        self::assertTrue($model->hasProperty(Model::PUBLIC));
        self::assertTrue($model->hasProperty(Model::PROTECTED));
        self::assertTrue($model->hasProperty(Model::PRIVATE));
    }

    public function testGet(): void
    {
        $model = Model::fromArray(Model::VALUES);

        self::assertSame(Model::PUBLIC, $model->public);
        self::assertSame(Model::PROTECTED, $model->protected);
        self::assertSame(Model::PRIVATE, $model->private);

        self::assertSame(Model::PUBLIC, $model[Model::PUBLIC]);
        self::assertSame(Model::PROTECTED, $model[Model::PROTECTED]);
        self::assertSame(Model::PRIVATE, $model[Model::PRIVATE]);

        self::assertSame(Model::PUBLIC, $model->__get(Model::PUBLIC));
        self::assertSame(Model::PROTECTED, $model->__get(Model::PROTECTED));
        self::assertSame(Model::PRIVATE, $model->__get(Model::PRIVATE));

        self::assertSame(Model::PUBLIC, $model->offsetGet(Model::PUBLIC));
        self::assertSame(Model::PROTECTED, $model->offsetGet(Model::PROTECTED));
        self::assertSame(Model::PRIVATE, $model->offsetGet(Model::PRIVATE));
    }

    public function testGetNotSet(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage(
            'Typed property ' . Model::class . '::$public must not be accessed before initialization'
        );

        $model = Model::fromArray([]);

        self::assertSame(Model::PUBLIC, $model->public);
    }

    public function testIsset(): void
    {
        $model = Model::fromArray([]);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
        self::assertFalse(isset($model->private));

        self::assertFalse(isset($model[Model::PUBLIC]));
        self::assertFalse(isset($model[Model::PROTECTED]));
        self::assertFalse(isset($model[Model::PRIVATE]));

        self::assertFalse($model->__isset(Model::PUBLIC));
        self::assertFalse($model->__isset(Model::PROTECTED));
        self::assertFalse($model->__isset(Model::PRIVATE));

        self::assertFalse($model->offsetExists(Model::PUBLIC));
        self::assertFalse($model->offsetExists(Model::PROTECTED));
        self::assertFalse($model->offsetExists(Model::PRIVATE));

        $model = Model::fromArray(Model::VALUES);

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        self::assertTrue(isset($model->private));

        self::assertTrue(isset($model[Model::PUBLIC]));
        self::assertTrue(isset($model[Model::PROTECTED]));
        self::assertTrue(isset($model[Model::PRIVATE]));

        self::assertTrue($model->__isset(Model::PUBLIC));
        self::assertTrue($model->__isset(Model::PROTECTED));
        self::assertTrue($model->__isset(Model::PRIVATE));

        self::assertTrue($model->offsetExists(Model::PUBLIC));
        self::assertTrue($model->offsetExists(Model::PROTECTED));
        self::assertTrue($model->offsetExists(Model::PRIVATE));
    }

    /**
     * @throws JsonException
     */
    public function testFromValue(): void
    {
        $model = Model::fromValue([]);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
        self::assertFalse(isset($model->private));

        $model = Model::fromValue(Model::VALUES);

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        self::assertTrue(isset($model->private));

        $model = Model::fromValue(json_encode(Model::VALUES));

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        self::assertTrue(isset($model->private));

        $model = Model::fromValue(Model::fromValue(Model::VALUES));

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        // Since private fields are not exposed
        self::assertFalse(isset($model->private));

        $model = Model::fromValue(json_encode(Model::fromValue(Model::VALUES)));

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        // Since private fields are not exposed
        self::assertFalse(isset($model->private));
    }

    public function testSet(): void
    {
        $model = Model::fromArray([]);

        $model->public    = Model::PUBLIC;
        $model->protected = Model::PROTECTED;
        $model->private   = Model::PRIVATE;
        $model->nullable  = Model::NULLABLE;

        self::assertSame(Model::PUBLIC, $model->public);
        self::assertSame(Model::PROTECTED, $model->protected);
        self::assertSame(Model::PRIVATE, $model->private);
        self::assertSame(Model::NULLABLE, $model->nullable);

        $model = Model::fromArray([]);

        $model->__set(Model::PUBLIC, Model::PUBLIC);
        $model->__set(Model::PROTECTED, Model::PROTECTED);
        $model->__set(Model::PRIVATE, Model::PRIVATE);
        $model->__set(Model::NULLABLE, Model::NULLABLE);

        self::assertSame(Model::PUBLIC, $model->public);
        self::assertSame(Model::PROTECTED, $model->protected);
        self::assertSame(Model::PRIVATE, $model->private);
        self::assertSame(Model::NULLABLE, $model->nullable);

        $model = Model::fromArray([]);

        $model[Model::PUBLIC]    = Model::PUBLIC;
        $model[Model::PROTECTED] = Model::PROTECTED;
        $model[Model::PRIVATE]   = Model::PRIVATE;
        $model[Model::NULLABLE]  = Model::NULLABLE;

        self::assertSame(Model::PUBLIC, $model->public);
        self::assertSame(Model::PROTECTED, $model->protected);
        self::assertSame(Model::PRIVATE, $model->private);
        self::assertSame(Model::NULLABLE, $model->nullable);

        $model = Model::fromArray([]);

        $model->offsetSet(Model::PUBLIC, Model::PUBLIC);
        $model->offsetSet(Model::PROTECTED, Model::PROTECTED);
        $model->offsetSet(Model::PRIVATE, Model::PRIVATE);
        $model->offsetSet(Model::NULLABLE, Model::NULLABLE);

        self::assertSame(Model::PUBLIC, $model->public);
        self::assertSame(Model::PROTECTED, $model->protected);
        self::assertSame(Model::PRIVATE, $model->private);
        self::assertSame(Model::NULLABLE, $model->nullable);
    }

    public function testUnset(): void
    {
        $model = Model::fromArray(Model::VALUES);

        unset($model[Model::PUBLIC], $model[Model::PROTECTED]);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));

        $model = Model::fromArray(Model::VALUES);

        $model->offsetUnset(Model::PUBLIC);
        $model->offsetUnset(Model::PROTECTED);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
    }

    public function testUnsetPrivateErrors(): void
    {
        $this->expectException(Error::class);

        $model = Model::fromArray(Model::VALUES);

        unset($model[Model::PRIVATE]);
    }

    public function testUnsetMethodPrivateErrors(): void
    {
        $this->expectException(Error::class);

        $model = Model::fromArray(Model::VALUES);

        $model->offsetUnset(Model::PRIVATE);
    }

    public function testWithProperties(): void
    {
        $model    = Model::fromArray([]);
        $newModel = $model->withProperties(Model::VALUES);

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
        $model = Model::fromArray(Model::VALUES);

        $model->public    = $value;
        $model->protected = $value;
        $model->private   = $value;
        $model->nullable  = $value;

        self::assertSame(Model::VALUES, $model->asOriginalArray());
        self::assertSame(Model::PUBLIC, $model->getOriginalPropertyValue(Model::PUBLIC));
        self::assertSame(Model::PROTECTED, $model->getOriginalPropertyValue(Model::PROTECTED));
        self::assertSame(Model::PRIVATE, $model->getOriginalPropertyValue(Model::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(Model::NULLABLE));

        $model = Model::fromArray([]);
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(Model::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(Model::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(Model::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(Model::NULLABLE));
        $model->updateProperties(Model::VALUES);
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(Model::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(Model::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(Model::PRIVATE));
        self::assertNull($model->getOriginalPropertyValue(Model::NULLABLE));

        $model = Model::fromArray([]);

        $model->public = Model::PUBLIC;
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(Model::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(Model::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(Model::PRIVATE));
    }

    public function testChanged(): void
    {
        // Public properties should show up if changed
        $model = Model::fromArray(Model::VALUES);

        $model->public = 'test';
        self::assertSame([Model::PUBLIC => 'test'], $model->asChangedArray());

        // Protected properties should show up if changed
        $model = Model::fromArray(Model::VALUES);

        $model->protected = 'test';
        self::assertSame([Model::PROTECTED => 'test'], $model->asChangedArray());

        // Private properties should not show up
        $model = Model::fromArray(Model::VALUES);

        $model->private = 'test';
        self::assertSame([], $model->asChangedArray());

        // Private properties should not show up, but public and protected should if changed
        $model = Model::fromArray(Model::VALUES);

        $model->public    = 'test';
        $model->protected = 'test2';
        $model->private   = 'test3';
        self::assertSame([Model::PUBLIC => 'test', Model::PROTECTED => 'test2'], $model->asChangedArray());

        // Because public properties aren't tracked unless through methods then they come up as changed
        $model = Model::fromArray([]);

        $model->public = Model::PUBLIC;
        self::assertSame([Model::PUBLIC => Model::PUBLIC], $model->asChangedArray());
    }

    public function testAsArray(): void
    {
        $model = Model::fromArray([]);
        self::assertSame([], $model->asArray());

        $model = Model::fromArray(Model::VALUES);
        self::assertSame(
            [
                Model::PUBLIC    => Model::PUBLIC,
                Model::NULLABLE  => null,
                Model::PROTECTED => Model::PROTECTED,
            ],
            $model->asArray()
        );
        self::assertSame([Model::PUBLIC => Model::PUBLIC], $model->asArray(Model::PUBLIC));
        self::assertSame([Model::PROTECTED => Model::PROTECTED], $model->asArray(Model::PROTECTED));
        // Private or hidden properties should not be exposable.
        self::assertSame([], $model->asArray(Model::PRIVATE));
    }

    public function testAsValue(): void
    {
        $test = new Model();

        self::assertSame($test, $test->asValue());
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $model = Model::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = Model::fromArray(Model::VALUES);

        $expected = '{"public":"public","nullable":null,"protected":"protected"}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }

    public function testCloning(): void
    {
        $model = Model::fromArray([]);

        $cloned = clone $model;

        self::assertSame($model->asChangedArray(), $cloned->asChangedArray());

        $updatedValue   = 'fire';
        $cloned->public = $updatedValue;

        self::assertNotSame($model, $cloned);
        self::assertNotSame($model->asChangedArray(), $cloned->asChangedArray());
        self::assertEmpty($model->asChangedArray());
        self::assertSame([Model::PUBLIC => $updatedValue], $cloned->asChangedArray());
    }

    /**
     * @throws JsonException
     */
    public function testAsFlatValue(): void
    {
        $value = ['public' => 'cheese'];
        $model = Model::fromArray($value);

        self::assertSame(Arr::toString($value), $model->asFlatValue());
    }

    public function testModify(): void
    {
        $value    = 'cheese';
        $newValue = 'fire';
        $model    = Model::fromArray(['public' => $value]);

        $modified = $model->modify(static function (Model $model) use ($newValue): Model {
            $model->public = $newValue;

            return $model;
        });

        self::assertSame($value, $model->public);
        self::assertSame($newValue, $modified->public);
    }
}
