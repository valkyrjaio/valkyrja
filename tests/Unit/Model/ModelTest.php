<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Model;

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

        $this->assertTrue($model->hasProperty(Model::PUBLIC));
        $this->assertTrue($model->hasProperty(Model::PROTECTED));
        $this->assertTrue($model->hasProperty(Model::PRIVATE));
    }

    public function testGet(): void
    {
        $model = Model::fromArray(Model::VALUES);

        $this->assertEquals(Model::PUBLIC, $model->public);
        $this->assertEquals(Model::PROTECTED, $model->protected);
        $this->assertEquals(Model::PRIVATE, $model->private);
    }

    public function testGetNotSet(): void
    {
        $this->expectError();

        $model = Model::fromArray([]);

        $this->assertEquals(Model::PUBLIC, $model->public);
    }

    public function testIsset(): void
    {
        $model = Model::fromArray([]);

        $this->assertFalse(isset($model->public));
        $this->assertFalse(isset($model->protected));
        $this->assertFalse(isset($model->private));

        $model = Model::fromArray(Model::VALUES);

        $this->assertTrue(isset($model->public));
        $this->assertTrue(isset($model->protected));
        $this->assertTrue(isset($model->private));
    }

    public function testSet(): void
    {
        $model = Model::fromArray([]);

        $model->public    = Model::PUBLIC;
        $model->protected = Model::PROTECTED;
        $model->private   = Model::PRIVATE;

        $this->assertEquals(Model::PUBLIC, $model->public);
        $this->assertEquals(Model::PROTECTED, $model->protected);
        $this->assertEquals(Model::PRIVATE, $model->private);
    }

    public function testWithProperties(): void
    {
        $model    = Model::fromArray([]);
        $newModel = $model->withProperties(Model::VALUES);

        $this->assertFalse(isset($model->public));
        $this->assertFalse(isset($model->protected));
        $this->assertFalse(isset($model->private));

        $this->assertTrue(isset($newModel->public));
        $this->assertTrue(isset($newModel->protected));
        $this->assertTrue(isset($newModel->private));
    }

    public function testOriginal(): void
    {
        $model = Model::fromArray(Model::VALUES);
        $this->assertEquals(Model::VALUES, $model->asOriginalArray());
        $this->assertEquals(Model::PUBLIC, $model->getOriginalPropertyValue(Model::PUBLIC));
        $this->assertEquals(Model::PROTECTED, $model->getOriginalPropertyValue(Model::PROTECTED));
        $this->assertEquals(Model::PRIVATE, $model->getOriginalPropertyValue(Model::PRIVATE));

        $model = Model::fromArray([]);
        $this->assertEquals([], $model->asOriginalArray());
        $this->assertNull($model->getOriginalPropertyValue(Model::PUBLIC));
        $this->assertNull($model->getOriginalPropertyValue(Model::PROTECTED));
        $this->assertNull($model->getOriginalPropertyValue(Model::PRIVATE));
        $model->updateProperties(Model::VALUES);
        $this->assertEquals([], $model->asOriginalArray());
        $this->assertNull($model->getOriginalPropertyValue(Model::PUBLIC));
        $this->assertNull($model->getOriginalPropertyValue(Model::PROTECTED));
        $this->assertNull($model->getOriginalPropertyValue(Model::PRIVATE));

        $model = Model::fromArray([]);

        $model->public = Model::PUBLIC;
        $this->assertEquals([], $model->asOriginalArray());
        $this->assertNull($model->getOriginalPropertyValue(Model::PUBLIC));
        $this->assertNull($model->getOriginalPropertyValue(Model::PROTECTED));
        $this->assertNull($model->getOriginalPropertyValue(Model::PRIVATE));
    }

    public function testChanged(): void
    {
        // Public properties should show up if changed
        $model = Model::fromArray(Model::VALUES);

        $model->public = 'test';
        $this->assertEquals([Model::PUBLIC => 'test'], $model->asChangedArray());

        // Protected properties should show up if changed
        $model = Model::fromArray(Model::VALUES);

        $model->protected = 'test';
        $this->assertEquals([Model::PROTECTED => 'test'], $model->asChangedArray());

        // Private properties should not show up
        $model = Model::fromArray(Model::VALUES);

        $model->private = 'test';
        $this->assertEquals([], $model->asChangedArray());

        // Private properties should not show up, but public and protected should if changed
        $model = Model::fromArray(Model::VALUES);

        $model->public    = 'test';
        $model->protected = 'test2';
        $model->private   = 'test3';
        $this->assertEquals([Model::PUBLIC => 'test', Model::PROTECTED => 'test2'], $model->asChangedArray());

        // Because public properties aren't tracked unless through methods then they come up as changed
        $model = Model::fromArray([]);

        $model->public = Model::PUBLIC;
        $this->assertEquals([Model::PUBLIC => Model::PUBLIC], $model->asChangedArray());
    }

    public function testAsArray(): void
    {
        $model = Model::fromArray([]);
        $this->assertEquals([], $model->asArray());

        $model = Model::fromArray(Model::VALUES);
        $this->assertEquals([Model::PUBLIC => Model::PUBLIC, Model::PROTECTED => Model::PROTECTED], $model->asArray());
        $this->assertEquals([Model::PUBLIC => Model::PUBLIC], $model->asArray(Model::PUBLIC));
        $this->assertEquals([Model::PROTECTED => Model::PROTECTED], $model->asArray(Model::PROTECTED));
        // Private or hidden properties should not be exposable.
        $this->assertEquals([], $model->asArray(Model::PRIVATE));
    }

    public function testJsonSerialize(): void
    {
        $model = Model::fromArray([]);

        $expected = '[]';
        $this->assertEquals($expected, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expected, (string) $model);

        $model = Model::fromArray(Model::VALUES);

        $expected = '{"public":"public","protected":"protected"}';
        $this->assertEquals($expected, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expected, (string) $model);
    }
}
