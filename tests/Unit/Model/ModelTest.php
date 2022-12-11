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
    protected const PUBLIC    = 'public';
    protected const PROTECTED = 'protected';
    protected const PRIVATE   = 'private';

    protected const VALUES = [
        self::PUBLIC    => self::PUBLIC,
        self::PROTECTED => self::PROTECTED,
        self::PRIVATE   => self::PRIVATE,
    ];

    /**
     * The model class.
     *
     * @var Model
     */
    protected Model $model;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->model = new Model();
    }

    public function testHas(): void
    {
        $model = new Model();

        $this->assertTrue($model->hasProperty(self::PUBLIC));
        $this->assertTrue($model->hasProperty(self::PROTECTED));
        $this->assertTrue($model->hasProperty(self::PRIVATE));
    }

    public function testGet(): void
    {
        $model = Model::fromArray(self::VALUES);

        $this->assertEquals(self::PUBLIC, $model->public);
        $this->assertEquals(self::PROTECTED, $model->protected);
        $this->assertEquals(self::PRIVATE, $model->private);
    }

    public function testGetNotSet(): void
    {
        $this->expectError();

        $model = Model::fromArray([]);

        $this->assertEquals(self::PUBLIC, $model->public);
    }

    public function testIsset(): void
    {
        $model = Model::fromArray([]);

        $this->assertFalse(isset($model->public));
        $this->assertFalse(isset($model->protected));
        $this->assertFalse(isset($model->private));

        $model = Model::fromArray(self::VALUES);

        $this->assertTrue(isset($model->public));
        $this->assertTrue(isset($model->protected));
        $this->assertTrue(isset($model->private));
    }

    public function testSet(): void
    {
        $model = Model::fromArray([]);

        $model->public    = self::PUBLIC;
        $model->protected = self::PROTECTED;
        $model->private   = self::PRIVATE;

        $this->assertEquals(self::PUBLIC, $model->public);
        $this->assertEquals(self::PROTECTED, $model->protected);
        $this->assertEquals(self::PRIVATE, $model->private);
    }

    public function testWithProperties(): void
    {
        $model    = Model::fromArray([]);
        $newModel = $model->withProperties(self::VALUES);

        $this->assertFalse(isset($model->public));
        $this->assertFalse(isset($model->protected));
        $this->assertFalse(isset($model->private));

        $this->assertTrue(isset($newModel->public));
        $this->assertTrue(isset($newModel->protected));
        $this->assertTrue(isset($newModel->private));
    }

    public function testOriginal(): void
    {
        $model = Model::fromArray(self::VALUES);
        $this->assertEquals(self::VALUES, $model->asOriginalArray());
        $this->assertEquals(self::PUBLIC, $model->getOriginalPropertyValue(self::PUBLIC));
        $this->assertEquals(self::PROTECTED, $model->getOriginalPropertyValue(self::PROTECTED));
        $this->assertEquals(self::PRIVATE, $model->getOriginalPropertyValue(self::PRIVATE));

        $model = Model::fromArray([]);
        $this->assertEquals([], $model->asOriginalArray());
        $this->assertNull($model->getOriginalPropertyValue(self::PUBLIC));
        $this->assertNull($model->getOriginalPropertyValue(self::PROTECTED));
        $this->assertNull($model->getOriginalPropertyValue(self::PRIVATE));
        $model->updateProperties(self::VALUES);
        $this->assertEquals([], $model->asOriginalArray());
        $this->assertNull($model->getOriginalPropertyValue(self::PUBLIC));
        $this->assertNull($model->getOriginalPropertyValue(self::PROTECTED));
        $this->assertNull($model->getOriginalPropertyValue(self::PRIVATE));

        $model = Model::fromArray([]);

        $model->public = self::PUBLIC;
        $this->assertEquals([], $model->asOriginalArray());
        $this->assertNull($model->getOriginalPropertyValue(self::PUBLIC));
        $this->assertNull($model->getOriginalPropertyValue(self::PROTECTED));
        $this->assertNull($model->getOriginalPropertyValue(self::PRIVATE));
    }

    public function testChanged(): void
    {
        // Public properties should show up if changed
        $model = Model::fromArray(self::VALUES);

        $model->public = 'test';
        $this->assertEquals([self::PUBLIC => 'test'], $model->asChangedArray());

        // Protected properties should show up if changed
        $model = Model::fromArray(self::VALUES);

        $model->protected = 'test';
        $this->assertEquals([self::PROTECTED => 'test'], $model->asChangedArray());

        // Private properties should not show up
        $model = Model::fromArray(self::VALUES);

        $model->private = 'test';
        $this->assertEquals([], $model->asChangedArray());

        // Private properties should not show up, but public and protected should if changed
        $model = Model::fromArray(self::VALUES);

        $model->public    = 'test';
        $model->protected = 'test2';
        $model->private   = 'test3';
        $this->assertEquals([self::PUBLIC => 'test', self::PROTECTED => 'test2'], $model->asChangedArray());

        // Because public properties aren't tracked unless through methods then they come up as changed
        $model = Model::fromArray([]);

        $model->public = self::PUBLIC;
        $this->assertEquals([self::PUBLIC => self::PUBLIC], $model->asChangedArray());
    }

    public function testAsArray(): void
    {
        $model = Model::fromArray([]);
        $this->assertEquals([], $model->asArray());

        $model = Model::fromArray(self::VALUES);
        $this->assertEquals([self::PUBLIC => self::PUBLIC, self::PROTECTED => self::PROTECTED], $model->asArray());
        $this->assertEquals([self::PUBLIC => self::PUBLIC], $model->asArray(self::PUBLIC));
        $this->assertEquals([self::PROTECTED => self::PROTECTED], $model->asArray(self::PROTECTED));
        // Private or hidden properties should not be exposable.
        $this->assertEquals([], $model->asArray(self::PRIVATE));
    }

    public function testJsonSerialize(): void
    {
        $model = Model::fromArray([]);
        $this->assertEquals('[]', json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals('[]', (string) $model);

        $model = Model::fromArray(self::VALUES);
        $this->assertEquals('{"public":"public","protected":"protected"}', json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals('{"public":"public","protected":"protected"}', (string) $model);
    }
}
