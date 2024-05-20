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

use Valkyrja\Tests\Classes\Model\ExposedIndexableModel;
use Valkyrja\Tests\Classes\Model\IndexableModel;
use Valkyrja\Tests\Classes\Model\Model;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Model\Contract\ExposableIndexedModel as Contract;
use Valkyrja\Type\Model\Contract\IndexedModel as ModelContract;

use function method_exists;

/**
 * Test the ExposableIndexableModel model.
 *
 * @author Melech Mizrachi
 */
class ExposableIndexableModelTest extends TestCase
{
    public function testContract(): void
    {
        self::assertTrue(method_exists(Contract::class, 'asExposedIndexedArray'));
        self::assertTrue(method_exists(Contract::class, 'asExposedChangedIndexedArray'));
        self::isA(ModelContract::class, Contract::class);
    }

    public function testGetExposable(): void
    {
        self::assertSame([Model::PRIVATE], ExposedIndexableModel::getExposable());
    }

    public function testAsExposedIndexedArray(): void
    {
        $model = ExposedIndexableModel::fromArray(Model::VALUES);

        $expectedAsArray = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
        ];
        $expectedExposed = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
            Model::PRIVATE   => Model::PRIVATE,
        ];
        $expectedIndexed = [
            IndexableModel::PUBLIC_INDEX    => Model::PUBLIC,
            IndexableModel::PROTECTED_INDEX => Model::PROTECTED,
            IndexableModel::PRIVATE_INDEX   => Model::PRIVATE,
            IndexableModel::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expectedAsArray, $model->asArray());
        self::assertSame($expectedExposed, $model->asExposedArray());
        self::assertSame($expectedIndexed, $model->asExposedIndexedArray());
    }

    public function testAsExposedChangedIndexedArray(): void
    {
        $model = ExposedIndexableModel::fromArray(Model::VALUES);

        $model->private         = 'test';
        $expectedAsArray        = [];
        $expectedExposed        = [Model::PRIVATE => 'test'];
        $expectedExposedIndexed = [IndexableModel::PRIVATE_INDEX => 'test'];
        self::assertSame($expectedAsArray, $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
        self::assertSame($expectedExposedIndexed, $model->asExposedChangedIndexedArray());
    }
}
