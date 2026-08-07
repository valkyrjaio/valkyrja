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

use JsonException;
use ReflectionClass;
use Valkyrja\Tests\Fixtures\Type\Model\ExposableModelFixture;
use Valkyrja\Tests\Fixtures\Type\Model\ModelFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Contract\ModelContract;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Test the Exposable model.
 */
final class ExposableModelTest extends TestCase
{
    public function testContract(): void
    {
        $reflection = new ReflectionClass(ExposableModelContract::class);

        self::assertTrue($reflection->hasMethod('getExposable'));
        self::assertTrue($reflection->hasMethod('asExposedArray'));
        self::assertTrue($reflection->hasMethod('asExposedChangedArray'));
        self::assertTrue($reflection->hasMethod('asExposedOnlyArray'));
        self::assertTrue($reflection->hasMethod('expose'));
        self::isA(ModelContract::class, ExposableModelContract::class);
    }

    public function testGetExposable(): void
    {
        self::assertSame([ModelFixture::PRIVATE], ExposableModelFixture::getExposable());
    }

    public function testAsExposedArray(): void
    {
        $model = ExposableModelFixture::fromArray(ModelFixture::VALUES);

        $expected = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
        self::assertSame(ModelFixture::VALUES, $model->asExposedArray());
    }

    public function testAsExposedChangedArray(): void
    {
        $model = ExposableModelFixture::fromArray(ModelFixture::VALUES);

        $model->private  = 'test';
        $expectedExposed = [ModelFixture::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testAsExposedOnlyArray(): void
    {
        $model = ExposableModelFixture::fromArray(ModelFixture::VALUES);

        $model->private  = 'test';
        $expectedExposed = [ModelFixture::PRIVATE => 'test'];
        self::assertSame($expectedExposed, $model->asExposedOnlyArray());
    }

    public function testExpose(): void
    {
        $model = ExposableModelFixture::fromArray(ModelFixture::VALUES);

        $model->expose(ModelFixture::PRIVATE);
        self::assertSame(ModelFixture::VALUES, $model->asArray());
        self::assertSame(ModelFixture::VALUES, $model->asExposedArray());

        // asExposed methods call unexpose and so remove the exposable properties if that array is set
        $model->expose(ModelFixture::PRIVATE);
        $model->private = 'test';
        $expected       = [ModelFixture::PRIVATE => 'test'];
        self::assertSame($expected, $model->asChangedArray());
        self::assertSame($expected, $model->asExposedChangedArray());

        $model->private = ModelFixture::PRIVATE;

        $expected = [
            ModelFixture::PUBLIC    => ModelFixture::PUBLIC,
            ModelFixture::NULLABLE  => null,
            ModelFixture::PROTECTED => ModelFixture::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
        self::assertSame(ModelFixture::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [ModelFixture::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $model = ExposableModelFixture::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = ExposableModelFixture::fromArray(ModelFixture::VALUES);

        $expected = '{"public":"public","nullable":null,"protected":"protected"}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
        $model->expose(ModelFixture::PRIVATE);
        $expectedExposed = '{"public":"public","nullable":null,"protected":"protected","private":"private"}';
        self::assertSame($expectedExposed, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expectedExposed, (string) $model);
        $model->unexpose();
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }
}
