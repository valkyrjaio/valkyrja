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

use Valkyrja\Tests\Classes\Type\Model\ExposedIndexableModelClass;
use Valkyrja\Tests\Classes\Type\Model\IndexableModelClass;
use Valkyrja\Tests\Classes\Type\Model\ModelClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Model\Contract\ExposableIndexedModelContract;
use Valkyrja\Type\Model\Contract\IndexedModelContract;

use function method_exists;

/**
 * Test the ExposableIndexableModel model.
 */
class ExposableIndexableModelTest extends TestCase
{
    public function testContract(): void
    {
        self::assertTrue(method_exists(ExposableIndexedModelContract::class, 'asExposedIndexedArray'));
        self::assertTrue(method_exists(ExposableIndexedModelContract::class, 'asExposedChangedIndexedArray'));
        self::isA(IndexedModelContract::class, ExposableIndexedModelContract::class);
    }

    public function testGetExposable(): void
    {
        self::assertSame([ModelClass::PRIVATE], ExposedIndexableModelClass::getExposable());
    }

    public function testAsExposedIndexedArray(): void
    {
        $model = ExposedIndexableModelClass::fromArray(ModelClass::VALUES);

        $expectedAsArray = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
        ];
        $expectedExposed = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
            ModelClass::PRIVATE   => ModelClass::PRIVATE,
        ];
        $expectedIndexed = [
            IndexableModelClass::PUBLIC_INDEX    => ModelClass::PUBLIC,
            IndexableModelClass::PROTECTED_INDEX => ModelClass::PROTECTED,
            IndexableModelClass::PRIVATE_INDEX   => ModelClass::PRIVATE,
            IndexableModelClass::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expectedAsArray, $model->asArray());
        self::assertSame($expectedExposed, $model->asExposedArray());
        self::assertSame($expectedIndexed, $model->asExposedIndexedArray());
    }

    public function testAsExposedChangedIndexedArray(): void
    {
        $model = ExposedIndexableModelClass::fromArray(ModelClass::VALUES);

        $model->private         = 'test';
        $expectedAsArray        = [];
        $expectedExposed        = [ModelClass::PRIVATE => 'test'];
        $expectedExposedIndexed = [IndexableModelClass::PRIVATE_INDEX => 'test'];
        self::assertSame($expectedAsArray, $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
        self::assertSame($expectedExposedIndexed, $model->asExposedChangedIndexedArray());
    }
}
