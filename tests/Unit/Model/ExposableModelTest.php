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

use Valkyrja\Tests\Classes\Model\ExposableModel;
use Valkyrja\Tests\Classes\Model\Model;
use Valkyrja\Tests\Unit\TestCase;

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
        self::assertSame([Model::PRIVATE], ExposableModel::getExposable());
    }

    public function testAsExposed(): void
    {
        $model = ExposableModel::fromArray(Model::VALUES);

        $expected = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
        self::assertSame(Model::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [Model::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testAsExposedOnly(): void
    {
        $model = ExposableModel::fromArray(Model::VALUES);

        $model->private  = 'test';
        $expectedExposed = [Model::PRIVATE => 'test'];
        self::assertSame($expectedExposed, $model->asExposedOnlyArray());
    }

    public function testExpose(): void
    {
        $model = ExposableModel::fromArray(Model::VALUES);

        $model->expose(Model::PRIVATE);
        self::assertSame(Model::VALUES, $model->asArray());
        self::assertSame(Model::VALUES, $model->asExposedArray());

        // asExposed methods call unexpose and so remove the exposable properties if that array is set
        $model->expose(Model::PRIVATE);
        $model->private = 'test';
        $expected       = [Model::PRIVATE => 'test'];
        self::assertSame($expected, $model->asChangedArray());
        self::assertSame($expected, $model->asExposedChangedArray());

        $model->private = Model::PRIVATE;

        $expected = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
        self::assertSame(Model::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [Model::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testJsonSerialize(): void
    {
        $model = ExposableModel::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = ExposableModel::fromArray(Model::VALUES);

        $expected = '{"public":"public","nullable":null,"protected":"protected"}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
        $model->expose(Model::PRIVATE);
        $expectedExposed = '{"public":"public","nullable":null,"protected":"protected","private":"private"}';
        self::assertSame($expectedExposed, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expectedExposed, (string) $model);
        $model->unexpose();
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }
}
