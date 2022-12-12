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
use Valkyrja\Tests\Classes\Model\ProtectedExposableModel;

use const JSON_THROW_ON_ERROR;

/**
 * Test the FullyExposed model.
 *
 * @author Melech Mizrachi
 */
class ProtectedExposableModelTest extends TestCase
{
    public function testAsExposed(): void
    {
        $model = ProtectedExposableModel::fromArray(Model::VALUES);

        $expected = [Model::PUBLIC => Model::PUBLIC];
        $this->assertEquals($expected, $model->asArray());
        $this->assertEquals(Model::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [Model::PRIVATE => 'test'];
        $this->assertEquals([], $model->asChangedArray());
        $this->assertEquals($expectedExposed, $model->asExposedChangedArray());
    }

    public function testExpose(): void
    {
        $model = ProtectedExposableModel::fromArray(Model::VALUES);

        $model->expose(Model::PROTECTED, Model::PRIVATE);
        $this->assertEquals(Model::VALUES, $model->asArray());
        $this->assertEquals(Model::VALUES, $model->asExposedArray());

        // asExposed methods call unexpose and so remove the exposable properties if that array is set
        $model->expose(Model::PROTECTED, Model::PRIVATE);
        $model->protected = 'test';
        $model->private   = 'test2';
        $expected         = [Model::PROTECTED => 'test', Model::PRIVATE => 'test2'];
        $this->assertEquals($expected, $model->asChangedArray());
        $this->assertEquals($expected, $model->asExposedChangedArray());

        $model->protected = Model::PROTECTED;
        $model->private   = Model::PRIVATE;

        $expected = [Model::PUBLIC => Model::PUBLIC];
        $this->assertEquals($expected, $model->asArray());
        $this->assertEquals(Model::VALUES, $model->asExposedArray());

        $model->protected = 'test';
        $model->private   = 'test2';
        $expectedExposed  = [Model::PROTECTED => 'test', Model::PRIVATE => 'test2'];
        $this->assertEquals([], $model->asChangedArray());
        $this->assertEquals($expectedExposed, $model->asExposedChangedArray());
    }

    public function testJsonSerialize(): void
    {
        $model = ProtectedExposableModel::fromArray([]);

        $expected = '[]';
        $this->assertEquals($expected, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expected, (string) $model);

        $model = ProtectedExposableModel::fromArray(Model::VALUES);

        $expected = '{"public":"public"}';
        $this->assertEquals($expected, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expected, (string) $model);
        $model->expose(Model::PROTECTED, Model::PRIVATE);
        $expectedExposed = '{"public":"public","protected":"protected","private":"private"}';
        $this->assertEquals($expectedExposed, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expectedExposed, (string) $model);
        $model->unexpose(Model::PROTECTED, Model::PRIVATE);
        $this->assertEquals($expected, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expected, (string) $model);
    }
}
