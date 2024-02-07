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
    public function testGetExposable(): void
    {
        self::assertSame([Model::PROTECTED, Model::PRIVATE], ProtectedExposableModel::getExposable());
    }

    public function testAsExposed(): void
    {
        $model = ProtectedExposableModel::fromArray(Model::VALUES);

        $expected = [Model::PUBLIC => Model::PUBLIC, Model::NULLABLE => null];
        self::assertSame($expected, $model->asArray());
        self::assertSame(Model::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [Model::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testExpose(): void
    {
        $model = ProtectedExposableModel::fromArray(Model::VALUES);

        $model->expose(Model::PROTECTED, Model::PRIVATE);
        self::assertSame(Model::VALUES, $model->asArray());
        self::assertSame(Model::VALUES, $model->asExposedArray());

        // asExposed methods call unexpose and so remove the exposable properties if that array is set
        $model->expose(Model::PROTECTED, Model::PRIVATE);
        $model->protected = 'test';
        $model->private   = 'test2';
        $expected         = [Model::PROTECTED => 'test', Model::PRIVATE => 'test2'];
        self::assertSame($expected, $model->asChangedArray());
        self::assertSame($expected, $model->asExposedChangedArray());

        $model->protected = Model::PROTECTED;
        $model->private   = Model::PRIVATE;

        $expected = [Model::PUBLIC => Model::PUBLIC, Model::NULLABLE => null];
        self::assertSame($expected, $model->asArray());
        self::assertSame(Model::VALUES, $model->asExposedArray());

        $model->protected = 'test';
        $model->private   = 'test2';
        $expectedExposed  = [Model::PROTECTED => 'test', Model::PRIVATE => 'test2'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testJsonSerialize(): void
    {
        $model = ProtectedExposableModel::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = ProtectedExposableModel::fromArray(Model::VALUES);

        $expected = '{"public":"public","nullable":null}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
        $model->expose(Model::PROTECTED, Model::PRIVATE);
        $expectedExposed = '{"public":"public","nullable":null,"protected":"protected","private":"private"}';
        self::assertSame($expectedExposed, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expectedExposed, (string) $model);
        $model->unexpose();
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }
}
