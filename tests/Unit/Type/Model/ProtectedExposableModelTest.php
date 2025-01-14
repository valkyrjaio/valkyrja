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

use Valkyrja\Tests\Classes\Model\ModelClass;
use Valkyrja\Tests\Classes\Model\ProtectedExposableModelClass;
use Valkyrja\Tests\Unit\TestCase;

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
        self::assertSame([ModelClass::PROTECTED, ModelClass::PRIVATE], ProtectedExposableModelClass::getExposable());
    }

    public function testAsExposed(): void
    {
        $model = ProtectedExposableModelClass::fromArray(ModelClass::VALUES);

        $expected = [ModelClass::PUBLIC => ModelClass::PUBLIC, ModelClass::NULLABLE => null];
        self::assertSame($expected, $model->asArray());
        self::assertSame(ModelClass::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [ModelClass::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testExpose(): void
    {
        $model = ProtectedExposableModelClass::fromArray(ModelClass::VALUES);

        $model->expose(ModelClass::PROTECTED, ModelClass::PRIVATE);
        self::assertSame(ModelClass::VALUES, $model->asArray());
        self::assertSame(ModelClass::VALUES, $model->asExposedArray());

        // asExposed methods call unexpose and so remove the exposable properties if that array is set
        $model->expose(ModelClass::PROTECTED, ModelClass::PRIVATE);
        $model->protected = 'test';
        $model->private   = 'test2';
        $expected         = [ModelClass::PROTECTED => 'test', ModelClass::PRIVATE => 'test2'];
        self::assertSame($expected, $model->asChangedArray());
        self::assertSame($expected, $model->asExposedChangedArray());

        $model->protected = ModelClass::PROTECTED;
        $model->private   = ModelClass::PRIVATE;

        $expected = [ModelClass::PUBLIC => ModelClass::PUBLIC, ModelClass::NULLABLE => null];
        self::assertSame($expected, $model->asArray());
        self::assertSame(ModelClass::VALUES, $model->asExposedArray());

        $model->protected = 'test';
        $model->private   = 'test2';
        $expectedExposed  = [ModelClass::PROTECTED => 'test', ModelClass::PRIVATE => 'test2'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testJsonSerialize(): void
    {
        $model = ProtectedExposableModelClass::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = ProtectedExposableModelClass::fromArray(ModelClass::VALUES);

        $expected = '{"public":"public","nullable":null}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
        $model->expose(ModelClass::PROTECTED, ModelClass::PRIVATE);
        $expectedExposed = '{"public":"public","nullable":null,"protected":"protected","private":"private"}';
        self::assertSame($expectedExposed, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expectedExposed, (string) $model);
        $model->unexpose();
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }
}
