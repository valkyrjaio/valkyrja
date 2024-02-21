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

use Error;
use PHPUnit\Framework\TestCase;
use Valkyrja\Tests\Classes\Model\Model;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Test the abstract model.
 *
 * @author Melech Mizrachi
 */
class ModelTest extends TestCase
{
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
    }

    public function testGetNotSet(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Typed property ' . Model::class . '::$public must not be accessed before initialization');

        $model = Model::fromArray([]);

        self::assertSame(Model::PUBLIC, $model->public);
    }

    public function testIsset(): void
    {
        $model = Model::fromArray([]);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
        self::assertFalse(isset($model->private));

        $model = Model::fromArray(Model::VALUES);

        self::assertTrue(isset($model->public));
        self::assertTrue(isset($model->protected));
        self::assertTrue(isset($model->private));
    }

    public function testSet(): void
    {
        $model = Model::fromArray([]);

        $model->public    = Model::PUBLIC;
        $model->protected = Model::PROTECTED;
        $model->private   = Model::PRIVATE;

        self::assertSame(Model::PUBLIC, $model->public);
        self::assertSame(Model::PROTECTED, $model->protected);
        self::assertSame(Model::PRIVATE, $model->private);
    }

    public function testWithProperties(): void
    {
        $model    = Model::fromArray([]);
        $newModel = $model->withProperties(Model::VALUES);

        self::assertFalse(isset($model->public));
        self::assertFalse(isset($model->protected));
        self::assertFalse(isset($model->private));

        self::assertTrue(isset($newModel->public));
        self::assertTrue(isset($newModel->protected));
        self::assertTrue(isset($newModel->private));
    }

    public function testOriginal(): void
    {
        $model = Model::fromArray(Model::VALUES);
        self::assertSame(Model::VALUES, $model->asOriginalArray());
        self::assertSame(Model::PUBLIC, $model->getOriginalPropertyValue(Model::PUBLIC));
        self::assertSame(Model::PROTECTED, $model->getOriginalPropertyValue(Model::PROTECTED));
        self::assertSame(Model::PRIVATE, $model->getOriginalPropertyValue(Model::PRIVATE));

        $model = Model::fromArray([]);
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(Model::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(Model::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(Model::PRIVATE));
        $model->updateProperties(Model::VALUES);
        self::assertSame([], $model->asOriginalArray());
        self::assertNull($model->getOriginalPropertyValue(Model::PUBLIC));
        self::assertNull($model->getOriginalPropertyValue(Model::PROTECTED));
        self::assertNull($model->getOriginalPropertyValue(Model::PRIVATE));

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
}
