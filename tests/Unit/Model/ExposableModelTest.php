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

use JsonException;
use Valkyrja\Model\ExposableModel as Contract;
use Valkyrja\Model\Model as ModelContract;
use Valkyrja\Tests\Classes\Model\ExposableModel;
use Valkyrja\Tests\Classes\Model\Model;
use Valkyrja\Tests\Classes\Model\SimpleExposableModel;
use Valkyrja\Tests\Unit\TestCase;

use function method_exists;

use const JSON_THROW_ON_ERROR;

/**
 * Test the Exposable model.
 *
 * @author Melech Mizrachi
 */
class ExposableModelTest extends TestCase
{
    public function testContract(): void
    {
        self::assertTrue(method_exists(Contract::class, 'getExposable'));
        self::assertTrue(method_exists(Contract::class, 'asExposedArray'));
        self::assertTrue(method_exists(Contract::class, 'asExposedChangedArray'));
        self::assertTrue(method_exists(Contract::class, 'asExposedOnlyArray'));
        self::assertTrue(method_exists(Contract::class, 'expose'));
        self::isA(ModelContract::class, Contract::class);
    }

    public function testGetExposable(): void
    {
        self::assertSame([Model::PRIVATE], ExposableModel::getExposable());
        self::assertSame([], SimpleExposableModel::getExposable());
    }

    public function testAsExposedArray(): void
    {
        $model = ExposableModel::fromArray(Model::VALUES);

        $expected = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
        self::assertSame(Model::VALUES, $model->asExposedArray());
    }

    public function testAsExposedChangedArray(): void
    {
        $model = ExposableModel::fromArray(Model::VALUES);

        $model->private  = 'test';
        $expectedExposed = [Model::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testAsExposedOnlyArray(): void
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

    /**
     * @throws JsonException
     */
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
