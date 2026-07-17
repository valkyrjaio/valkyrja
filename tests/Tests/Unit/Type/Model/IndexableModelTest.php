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

use Valkyrja\Tests\Fixtures\Type\Model\IndexableModelFixture;
use Valkyrja\Tests\Fixtures\Type\Model\ModelFixture;
use Valkyrja\Tests\Fixtures\Type\Model\SimpleIndexableModelFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Model\Contract\IndexedModelContract;
use Valkyrja\Type\Model\Contract\ModelContract;

use function method_exists;

/**
 * Test the Indexable model.
 */
final class IndexableModelTest extends TestCase
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
                ModelFixture::PUBLIC    => IndexableModelFixture::PUBLIC_INDEX,
                ModelFixture::PROTECTED => IndexableModelFixture::PROTECTED_INDEX,
                ModelFixture::PRIVATE   => IndexableModelFixture::PRIVATE_INDEX,
                ModelFixture::NULLABLE  => IndexableModelFixture::NULLABLE_INDEX,
            ],
            IndexableModelFixture::getIndexes()
        );
        self::assertSame([], SimpleIndexableModelFixture::getIndexes());
    }

    public function testGetReversedIndexes(): void
    {
        self::assertSame(
            [
                IndexableModelFixture::PUBLIC_INDEX    => ModelFixture::PUBLIC,
                IndexableModelFixture::PROTECTED_INDEX => ModelFixture::PROTECTED,
                IndexableModelFixture::PRIVATE_INDEX   => ModelFixture::PRIVATE,
                IndexableModelFixture::NULLABLE_INDEX  => ModelFixture::NULLABLE,
            ],
            IndexableModelFixture::getReversedIndexes()
        );
        self::assertSame([], SimpleIndexableModelFixture::getReversedIndexes());
    }

    public function testGetMappedArrayFromIndexedArray(): void
    {
        $array    = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
        ];
        $expected = [
            IndexableModelFixture::PUBLIC_INDEX    => ModelFixture::PUBLIC,
            IndexableModelFixture::PROTECTED_INDEX => ModelFixture::PROTECTED,
            IndexableModelFixture::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expected, IndexableModelFixture::getIndexedArrayFromMappedArray($array));
    }

    public function testGetIndexedArrayFromMappedArray(): void
    {
        $expected = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
            ModelFixture::NULLABLE  => null,
        ];

        $array = [
            IndexableModelFixture::PUBLIC_INDEX    => ModelFixture::PUBLIC,
            IndexableModelFixture::PROTECTED_INDEX => ModelFixture::PROTECTED,
            IndexableModelFixture::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expected, IndexableModelFixture::getMappedArrayFromIndexedArray($array));
    }

    public function testAsIndexedArray(): void
    {
        $model = IndexableModelFixture::fromArray(ModelFixture::VALUES);

        $expectedAsArray        = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
        ];
        $expectedAsIndexedArray = [
            IndexableModelFixture::PUBLIC_INDEX    => ModelFixture::PUBLIC,
            IndexableModelFixture::PROTECTED_INDEX => ModelFixture::PROTECTED,
            IndexableModelFixture::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expectedAsArray, $model->asArray());
        self::assertSame($expectedAsIndexedArray, $model->asIndexedArray());
    }

    public function testAsChangedIndexedArray(): void
    {
        $model = IndexableModelFixture::fromArray(ModelFixture::VALUES);

        $value                  = 'test';
        $expectedAsArray        = [
            ModelFixture::PROTECTED => $value,
        ];
        $expectedAsIndexedArray = [
            IndexableModelFixture::PROTECTED_INDEX => $value,
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
        $model = IndexableModelFixture::fromArray(ModelFixture::VALUES);

        $expectedAsArray        = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
            ModelFixture::PRIVATE   => ModelFixture::PRIVATE,
        ];
        $expectedAsIndexedArray = [
            IndexableModelFixture::PUBLIC_INDEX    => ModelFixture::PUBLIC,
            IndexableModelFixture::PROTECTED_INDEX => ModelFixture::PROTECTED,
            IndexableModelFixture::PRIVATE_INDEX   => ModelFixture::PRIVATE,
            IndexableModelFixture::NULLABLE_INDEX  => null,
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
            IndexableModelFixture::PUBLIC_INDEX    => ModelFixture::PUBLIC,
            IndexableModelFixture::PROTECTED_INDEX => ModelFixture::PROTECTED,
            IndexableModelFixture::PRIVATE_INDEX   => ModelFixture::PRIVATE,
            IndexableModelFixture::NULLABLE_INDEX  => null,
        ];

        $model    = IndexableModelFixture::fromIndexedArray($array);
        $expected = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
    }

    public function testUpdateIndexedProperties(): void
    {
        $value = 'test';
        $model = IndexableModelFixture::fromArray(ModelFixture::VALUES);

        $array = [
            IndexableModelFixture::PROTECTED_INDEX => $value,
            IndexableModelFixture::PRIVATE_INDEX   => $value,
        ];

        $expectedAsArray        = [
            ModelFixture::PROTECTED => $value,
        ];
        $expectedAsIndexedArray = [
            IndexableModelFixture::PROTECTED_INDEX => $value,
        ];

        $model->updateIndexedProperties($array);

        self::assertSame($expectedAsArray, $model->asChangedArray());
        self::assertSame($expectedAsIndexedArray, $model->asChangedIndexedArray());
    }

    public function testWithIndexedProperties(): void
    {
        $array                  = [
            IndexableModelFixture::PUBLIC_INDEX    => ModelFixture::PUBLIC,
            IndexableModelFixture::PROTECTED_INDEX => ModelFixture::PROTECTED,
            IndexableModelFixture::PRIVATE_INDEX   => ModelFixture::PRIVATE,
            IndexableModelFixture::NULLABLE_INDEX  => null,
        ];
        $expectedMutatedAsArray = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
        ];

        $model        = IndexableModelFixture::fromArray([]);
        $mutatedModel = $model->withIndexedProperties($array);

        self::assertSame([], $model->asArray());
        self::assertSame($expectedMutatedAsArray, $mutatedModel->asArray());
    }
}
