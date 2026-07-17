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

use Valkyrja\Tests\Fixtures\Type\Model\ModelFixture;
use Valkyrja\Tests\Fixtures\Type\Model\ProtectedExposableModelFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Test the FullyExposed model.
 */
final class ProtectedExposableModelTest extends TestCase
{
    public function testGetExposable(): void
    {
        self::assertSame([ModelFixture::PROTECTED, ModelFixture::PRIVATE], ProtectedExposableModelFixture::getExposable());
    }

    public function testAsExposed(): void
    {
        $model = ProtectedExposableModelFixture::fromArray(ModelFixture::VALUES);

        $expected = [ModelFixture::PUBLIC => ModelFixture::PUBLIC, ModelFixture::NULLABLE => null];
        self::assertSame($expected, $model->asArray());
        self::assertSame(ModelFixture::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [ModelFixture::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testExpose(): void
    {
        $model = ProtectedExposableModelFixture::fromArray(ModelFixture::VALUES);

        $model->expose(ModelFixture::PROTECTED, ModelFixture::PRIVATE);
        self::assertSame(ModelFixture::VALUES, $model->asArray());
        self::assertSame(ModelFixture::VALUES, $model->asExposedArray());

        // asExposed methods call unexpose and so remove the exposable properties if that array is set
        $model->expose(ModelFixture::PROTECTED, ModelFixture::PRIVATE);
        $model->protected = 'test';
        $model->private   = 'test2';
        $expected         = [ModelFixture::PROTECTED => 'test', ModelFixture::PRIVATE => 'test2'];
        self::assertSame($expected, $model->asChangedArray());
        self::assertSame($expected, $model->asExposedChangedArray());

        $model->protected = ModelFixture::PROTECTED;
        $model->private   = ModelFixture::PRIVATE;

        $expected = [ModelFixture::PUBLIC => ModelFixture::PUBLIC, ModelFixture::NULLABLE => null];
        self::assertSame($expected, $model->asArray());
        self::assertSame(ModelFixture::VALUES, $model->asExposedArray());

        $model->protected = 'test';
        $model->private   = 'test2';
        $expectedExposed  = [ModelFixture::PROTECTED => 'test', ModelFixture::PRIVATE => 'test2'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testJsonSerialize(): void
    {
        $model = ProtectedExposableModelFixture::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = ProtectedExposableModelFixture::fromArray(ModelFixture::VALUES);

        $expected = '{"public":"public","nullable":null}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
        $model->expose(ModelFixture::PROTECTED, ModelFixture::PRIVATE);
        $expectedExposed = '{"public":"public","nullable":null,"protected":"protected","private":"private"}';
        self::assertSame($expectedExposed, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expectedExposed, (string) $model);
        $model->unexpose();
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }
}
