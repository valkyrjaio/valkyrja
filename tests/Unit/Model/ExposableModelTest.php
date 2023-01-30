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

use PHPUnit\Framework\TestCase;
use Valkyrja\Tests\Classes\Model\ExposableModel;
use Valkyrja\Tests\Classes\Model\Model;

use const JSON_THROW_ON_ERROR;

/**
 * Test the FullyExposed model.
 *
 * @author Melech Mizrachi
 */
class ExposableModelTest extends TestCase
{
    public function testGetExposable(): void
    {
        $this->assertEquals([Model::PRIVATE], ExposableModel::getExposable());
    }

    public function testAsExposed(): void
    {
        $model = ExposableModel::fromArray(Model::VALUES);

        $expected = [Model::PUBLIC => Model::PUBLIC, Model::PROTECTED => Model::PROTECTED];
        $this->assertEquals($expected, $model->asArray());
        $this->assertEquals(Model::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [Model::PRIVATE => 'test'];
        $this->assertEquals([], $model->asChangedArray());
        $this->assertEquals($expectedExposed, $model->asExposedChangedArray());
    }

    public function testAsExposedOnly(): void
    {
        $model = ExposableModel::fromArray(Model::VALUES);

        $expectedExposed = [Model::PRIVATE => 'test'];
        $this->assertEquals($expectedExposed, $model->asExposedOnlyArray());
    }

    public function testExpose(): void
    {
        $model = ExposableModel::fromArray(Model::VALUES);

        $model->expose(Model::PRIVATE);
        $this->assertEquals(Model::VALUES, $model->asArray());
        $this->assertEquals(Model::VALUES, $model->asExposedArray());

        // asExposed methods call unexpose and so remove the exposable properties if that array is set
        $model->expose(Model::PRIVATE);
        $model->private = 'test';
        $expected       = [Model::PRIVATE => 'test'];
        $this->assertEquals($expected, $model->asChangedArray());
        $this->assertEquals($expected, $model->asExposedChangedArray());

        $model->private = Model::PRIVATE;

        $expected = [Model::PUBLIC => Model::PUBLIC, Model::PROTECTED => Model::PROTECTED];
        $this->assertEquals($expected, $model->asArray());
        $this->assertEquals(Model::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [Model::PRIVATE => 'test'];
        $this->assertEquals([], $model->asChangedArray());
        $this->assertEquals($expectedExposed, $model->asExposedChangedArray());
    }

    public function testJsonSerialize(): void
    {
        $model = ExposableModel::fromArray([]);

        $expected = '[]';
        $this->assertEquals($expected, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expected, (string) $model);

        $model = ExposableModel::fromArray(Model::VALUES);

        $expected = '{"public":"public","protected":"protected"}';
        $this->assertEquals($expected, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expected, (string) $model);
        $model->expose(Model::PRIVATE);
        $expectedExposed = '{"public":"public","protected":"protected","private":"private"}';
        $this->assertEquals($expectedExposed, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expectedExposed, (string) $model);
        $model->unexpose();
        $this->assertEquals($expected, json_encode($model, JSON_THROW_ON_ERROR));
        $this->assertEquals($expected, (string) $model);
    }
}
