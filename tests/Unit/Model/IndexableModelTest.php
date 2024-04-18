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

use Valkyrja\Model\IndexedModel as Contract;
use Valkyrja\Model\Model as ModelContract;
use Valkyrja\Tests\Classes\Model\IndexableModel;
use Valkyrja\Tests\Classes\Model\Model;
use Valkyrja\Tests\Classes\Model\SimpleIndexableModel;
use Valkyrja\Tests\Unit\TestCase;

use function method_exists;

/**
 * Test the Indexable model.
 *
 * @author Melech Mizrachi
 */
class IndexableModelTest extends TestCase
{
    public function testContract(): void
    {
        self::assertTrue(method_exists(Contract::class, 'getIndexes'));
        self::assertTrue(method_exists(Contract::class, 'getReversedIndexes'));
        self::assertTrue(method_exists(Contract::class, 'getMappedArrayFromIndexedArray'));
        self::assertTrue(method_exists(Contract::class, 'getIndexedArrayFromMappedArray'));
        self::assertTrue(method_exists(Contract::class, 'fromIndexedArray'));
        self::assertTrue(method_exists(Contract::class, 'updateIndexedProperties'));
        self::assertTrue(method_exists(Contract::class, 'withIndexedProperties'));
        self::assertTrue(method_exists(Contract::class, 'asIndexedArray'));
        self::assertTrue(method_exists(Contract::class, 'asChangedIndexedArray'));
        self::assertTrue(method_exists(Contract::class, 'asOriginalIndexedArray'));
        self::isA(ModelContract::class, Contract::class);
    }

    public function testGetIndexes(): void
    {
        self::assertSame(
            [
                Model::PUBLIC    => IndexableModel::PUBLIC_INDEX,
                Model::PROTECTED => IndexableModel::PROTECTED_INDEX,
                Model::PRIVATE   => IndexableModel::PRIVATE_INDEX,
                Model::NULLABLE  => IndexableModel::NULLABLE_INDEX,
            ],
            IndexableModel::getIndexes()
        );
        self::assertSame([], SimpleIndexableModel::getIndexes());
    }

    public function testGetReversedIndexes(): void
    {
        self::assertSame(
            [
                IndexableModel::PUBLIC_INDEX    => Model::PUBLIC,
                IndexableModel::PROTECTED_INDEX => Model::PROTECTED,
                IndexableModel::PRIVATE_INDEX   => Model::PRIVATE,
                IndexableModel::NULLABLE_INDEX  => Model::NULLABLE,
            ],
            IndexableModel::getReversedIndexes()
        );
        self::assertSame([], SimpleIndexableModel::getReversedIndexes());
    }

    public function testGetMappedArrayFromIndexedArray(): void
    {
        $array    = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
        ];
        $expected = [
            IndexableModel::PUBLIC_INDEX    => Model::PUBLIC,
            IndexableModel::PROTECTED_INDEX => Model::PROTECTED,
            IndexableModel::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expected, IndexableModel::getIndexedArrayFromMappedArray($array));
    }

    public function testGetIndexedArrayFromMappedArray(): void
    {
        $expected = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::PROTECTED => Model::PROTECTED,
            Model::NULLABLE  => null,
        ];

        $array = [
            IndexableModel::PUBLIC_INDEX    => Model::PUBLIC,
            IndexableModel::PROTECTED_INDEX => Model::PROTECTED,
            IndexableModel::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expected, IndexableModel::getMappedArrayFromIndexedArray($array));
    }

    public function testAsIndexedArray(): void
    {
        $model = IndexableModel::fromArray(Model::VALUES);

        $expectedAsArray        = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
        ];
        $expectedAsIndexedArray = [
            IndexableModel::PUBLIC_INDEX    => Model::PUBLIC,
            IndexableModel::PROTECTED_INDEX => Model::PROTECTED,
            IndexableModel::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expectedAsArray, $model->asArray());
        self::assertSame($expectedAsIndexedArray, $model->asIndexedArray());
    }

    public function testAsChangedIndexedArray(): void
    {
        $model = IndexableModel::fromArray(Model::VALUES);

        $value                  = 'test';
        $expectedAsArray        = [
            Model::PROTECTED => $value,
        ];
        $expectedAsIndexedArray = [
            IndexableModel::PROTECTED_INDEX => $value,
        ];

        $model->protected = $value;
        // Ensure that even if a private value was changed it will not appear in changed arrays
        $model->private = $value;

        self::assertSame($expectedAsArray, $model->asChangedArray());
        self::assertSame($expectedAsIndexedArray, $model->asChangedIndexedArray());
    }

    public function testAsOriginalIndexedArray(): void
    {
        $value = 'test';
        $model = IndexableModel::fromArray(Model::VALUES);

        $expectedAsArray        = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
            Model::PRIVATE   => Model::PRIVATE,
        ];
        $expectedAsIndexedArray = [
            IndexableModel::PUBLIC_INDEX    => Model::PUBLIC,
            IndexableModel::PROTECTED_INDEX => Model::PROTECTED,
            IndexableModel::PRIVATE_INDEX   => Model::PRIVATE,
            IndexableModel::NULLABLE_INDEX  => null,
        ];

        $model->public    = $value;
        $model->protected = $value;
        $model->private   = $value;
        $model->nullable  = $value;

        self::assertSame($expectedAsArray, $model->asOriginalArray());
        self::assertSame($expectedAsIndexedArray, $model->asOriginalIndexedArray());
    }

    public function testFromIndexedArray(): void
    {
        $array = [
            IndexableModel::PUBLIC_INDEX    => Model::PUBLIC,
            IndexableModel::PROTECTED_INDEX => Model::PROTECTED,
            IndexableModel::PRIVATE_INDEX   => Model::PRIVATE,
            IndexableModel::NULLABLE_INDEX  => null,
        ];

        $model    = IndexableModel::fromIndexedArray($array);
        $expected = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
    }

    public function testUpdateIndexedProperties(): void
    {
        $value = 'test';
        $model = IndexableModel::fromArray(Model::VALUES);

        $array = [
            IndexableModel::PROTECTED_INDEX => $value,
            IndexableModel::PRIVATE_INDEX   => $value,
        ];

        $expectedAsArray        = [
            Model::PROTECTED => $value,
        ];
        $expectedAsIndexedArray = [
            IndexableModel::PROTECTED_INDEX => $value,
        ];

        $model->updateIndexedProperties($array);

        self::assertSame($expectedAsArray, $model->asChangedArray());
        self::assertSame($expectedAsIndexedArray, $model->asChangedIndexedArray());
    }

    public function testWithIndexedProperties(): void
    {
        $array                  = [
            IndexableModel::PUBLIC_INDEX    => Model::PUBLIC,
            IndexableModel::PROTECTED_INDEX => Model::PROTECTED,
            IndexableModel::PRIVATE_INDEX   => Model::PRIVATE,
            IndexableModel::NULLABLE_INDEX  => null,
        ];
        $expectedMutatedAsArray = [
            Model::PUBLIC    => Model::PUBLIC,
            Model::NULLABLE  => null,
            Model::PROTECTED => Model::PROTECTED,
        ];

        $model        = IndexableModel::fromArray([]);
        $mutatedModel = $model->withIndexedProperties($array);

        self::assertSame([], $model->asArray());
        self::assertSame($expectedMutatedAsArray, $mutatedModel->asArray());
    }
}
