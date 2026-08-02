<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Model;

use Valkyrja\Tests\Fixtures\Type\Model\ExposedIndexableModelFixture;
use Valkyrja\Tests\Fixtures\Type\Model\IndexableModelFixture;
use Valkyrja\Tests\Fixtures\Type\Model\ModelFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Model\Contract\ExposableIndexedModelContract;
use Valkyrja\Type\Model\Contract\IndexedModelContract;

use function method_exists;

/**
 * Test the ExposableIndexableModel model.
 */
final class ExposableIndexableModelTest extends TestCase
{
    public function testContract(): void
    {
        self::assertTrue(method_exists(ExposableIndexedModelContract::class, 'asExposedIndexedArray'));
        self::assertTrue(method_exists(ExposableIndexedModelContract::class, 'asExposedChangedIndexedArray'));
        self::isA(IndexedModelContract::class, ExposableIndexedModelContract::class);
    }

    public function testGetExposable(): void
    {
        self::assertSame([ModelFixture::PRIVATE], ExposedIndexableModelFixture::getExposable());
    }

    public function testAsExposedIndexedArray(): void
    {
        $model = ExposedIndexableModelFixture::fromArray(ModelFixture::VALUES);

        $expectedAsArray = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
        ];
        $expectedExposed = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
            ModelFixture::PRIVATE   => ModelFixture::PRIVATE,
        ];
        $expectedIndexed = [
            IndexableModelFixture::PUBLIC_INDEX    => ModelFixture::PUBLIC,
            IndexableModelFixture::PROTECTED_INDEX => ModelFixture::PROTECTED,
            IndexableModelFixture::PRIVATE_INDEX   => ModelFixture::PRIVATE,
            IndexableModelFixture::NULLABLE_INDEX  => null,
        ];
        self::assertSame($expectedAsArray, $model->asArray());
        self::assertSame($expectedExposed, $model->asExposedArray());
        self::assertSame($expectedIndexed, $model->asExposedIndexedArray());
    }

    public function testAsExposedChangedIndexedArray(): void
    {
        $model = ExposedIndexableModelFixture::fromArray(ModelFixture::VALUES);

        $model->private         = 'test';
        $expectedAsArray        = [];
        $expectedExposed        = [ModelFixture::PRIVATE => 'test'];
        $expectedExposedIndexed = [IndexableModelFixture::PRIVATE_INDEX => 'test'];
        self::assertSame($expectedAsArray, $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
        self::assertSame($expectedExposedIndexed, $model->asExposedChangedIndexedArray());
    }
}
