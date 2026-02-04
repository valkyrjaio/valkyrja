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

use Valkyrja\Tests\Classes\Type\Model\IndexableModelClass;
use Valkyrja\Tests\Classes\Type\Model\ModelClass;
use Valkyrja\Tests\Classes\Type\Model\SimpleIndexableModelClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Model\Contract\IndexedModelContract;
use Valkyrja\Type\Model\Contract\ModelContract;

use function method_exists;

/**
 * Test the Indexable model.
 */
class IndexableModelTest extends TestCase
{
    public function testContract(): void
    {
        self::assertTrue(method_exists(IndexedModelContract::class, 'getIndexes'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'getReversedIndexes'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'getMappedArrayFromIndexedArray'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'getIndexedArrayFromMappedArray'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'fromIndexedArray'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'updateIndexedProperties'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'withIndexedProperties'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'asIndexedArray'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'asChangedIndexedArray'));
        self::assertTrue(method_exists(IndexedModelContract::class, 'asOriginalIndexedArray'));
        self::isA(ModelContract::class, IndexedModelContract::class);
    }

    public function testGetIndexes(): void
    {
        self::assertSame(
            [
                ModelClass::PUBLIC    => IndexableModelClass::PUBLIC_INDEX,
                ModelClass::PROTECTED => IndexableModelClass::PROTECTED_INDEX,
                ModelClass::PRIVATE   => IndexableModelClass::PRIVATE_INDEX,
                ModelClass::NULLABLE  => IndexableModelClass::NULLABLE_INDEX,
            ],
            IndexableModelClass::getIndexes()
        );
        self::assertSame([], SimpleIndexableModelClass::getIndexes());
    }

    public function testGetReversedIndexes(): void
    {
        self::assertSame(
            [
                IndexableModelClass::PUBLIC_INDEX    => ModelClass::PUBLIC,
                IndexableModelClass::PROTECTED_INDEX => ModelClass::PROTECTED,
                IndexableModelClass::PRIVATE_INDEX   => ModelClass::PRIVATE,
                IndexableModelClass::NULLABLE_INDEX  => ModelClass::NULLABLE,
            ],
            IndexableModelClass::getReversedIndexes()
        );
        self::assertSame([], SimpleIndexableModelClass::getReversedIndexes());
    }

    public function testGetMappedArrayFromIndexedArray(): void
    {
        $array    = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
        ];
        $expected = [
            IndexableModelClass::PUBLIC_INDEX    => ModelClass::PUBLIC,
            IndexableModelClass::PROTECTED_INDEX => ModelClass::PROTECTED,
            IndexableModelClass::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expected, IndexableModelClass::getIndexedArrayFromMappedArray($array));
    }

    public function testGetIndexedArrayFromMappedArray(): void
    {
        $expected = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
            ModelClass::NULLABLE  => null,
        ];

        $array = [
            IndexableModelClass::PUBLIC_INDEX    => ModelClass::PUBLIC,
            IndexableModelClass::PROTECTED_INDEX => ModelClass::PROTECTED,
            IndexableModelClass::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expected, IndexableModelClass::getMappedArrayFromIndexedArray($array));
    }

    public function testAsIndexedArray(): void
    {
        $model = IndexableModelClass::fromArray(ModelClass::VALUES);

        $expectedAsArray        = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
        ];
        $expectedAsIndexedArray = [
            IndexableModelClass::PUBLIC_INDEX    => ModelClass::PUBLIC,
            IndexableModelClass::PROTECTED_INDEX => ModelClass::PROTECTED,
            IndexableModelClass::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expectedAsArray, $model->asArray());
        self::assertSame($expectedAsIndexedArray, $model->asIndexedArray());
    }

    public function testAsChangedIndexedArray(): void
    {
        $model = IndexableModelClass::fromArray(ModelClass::VALUES);

        $value                  = 'test';
        $expectedAsArray        = [
            ModelClass::PROTECTED => $value,
        ];
        $expectedAsIndexedArray = [
            IndexableModelClass::PROTECTED_INDEX => $value,
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
        $model = IndexableModelClass::fromArray(ModelClass::VALUES);

        $expectedAsArray        = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
            ModelClass::PRIVATE   => ModelClass::PRIVATE,
        ];
        $expectedAsIndexedArray = [
            IndexableModelClass::PUBLIC_INDEX    => ModelClass::PUBLIC,
            IndexableModelClass::PROTECTED_INDEX => ModelClass::PROTECTED,
            IndexableModelClass::PRIVATE_INDEX   => ModelClass::PRIVATE,
            IndexableModelClass::NULLABLE_INDEX  => null,
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
            IndexableModelClass::PUBLIC_INDEX    => ModelClass::PUBLIC,
            IndexableModelClass::PROTECTED_INDEX => ModelClass::PROTECTED,
            IndexableModelClass::PRIVATE_INDEX   => ModelClass::PRIVATE,
            IndexableModelClass::NULLABLE_INDEX  => null,
        ];

        $model    = IndexableModelClass::fromIndexedArray($array);
        $expected = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
    }

    public function testUpdateIndexedProperties(): void
    {
        $value = 'test';
        $model = IndexableModelClass::fromArray(ModelClass::VALUES);

        $array = [
            IndexableModelClass::PROTECTED_INDEX => $value,
            IndexableModelClass::PRIVATE_INDEX   => $value,
        ];

        $expectedAsArray        = [
            ModelClass::PROTECTED => $value,
        ];
        $expectedAsIndexedArray = [
            IndexableModelClass::PROTECTED_INDEX => $value,
        ];

        $model->updateIndexedProperties($array);

        self::assertSame($expectedAsArray, $model->asChangedArray());
        self::assertSame($expectedAsIndexedArray, $model->asChangedIndexedArray());
    }

    public function testWithIndexedProperties(): void
    {
        $array                  = [
            IndexableModelClass::PUBLIC_INDEX    => ModelClass::PUBLIC,
            IndexableModelClass::PROTECTED_INDEX => ModelClass::PROTECTED,
            IndexableModelClass::PRIVATE_INDEX   => ModelClass::PRIVATE,
            IndexableModelClass::NULLABLE_INDEX  => null,
        ];
        $expectedMutatedAsArray = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
        ];

        $model        = IndexableModelClass::fromArray([]);
        $mutatedModel = $model->withIndexedProperties($array);

        self::assertSame([], $model->asArray());
        self::assertSame($expectedMutatedAsArray, $mutatedModel->asArray());
    }
}
