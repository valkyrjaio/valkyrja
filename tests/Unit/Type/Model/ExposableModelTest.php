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

use JsonException;
use Valkyrja\Tests\Classes\Model\ExposableModelClass;
use Valkyrja\Tests\Classes\Model\ModelClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Contract\ModelContract;

use function method_exists;

use const JSON_THROW_ON_ERROR;

/**
 * Test the Exposable model.
 */
class ExposableModelTest extends TestCase
{
    public function testContract(): void
    {
        self::assertTrue(method_exists(ExposableModelContract::class, 'getExposable'));
        self::assertTrue(method_exists(ExposableModelContract::class, 'asExposedArray'));
        self::assertTrue(method_exists(ExposableModelContract::class, 'asExposedChangedArray'));
        self::assertTrue(method_exists(ExposableModelContract::class, 'asExposedOnlyArray'));
        self::assertTrue(method_exists(ExposableModelContract::class, 'expose'));
        self::isA(ModelContract::class, ExposableModelContract::class);
    }

    public function testGetExposable(): void
    {
        self::assertSame([ModelClass::PRIVATE], ExposableModelClass::getExposable());
    }

    public function testAsExposedArray(): void
    {
        $model = ExposableModelClass::fromArray(ModelClass::VALUES);

        $expected = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
        self::assertSame(ModelClass::VALUES, $model->asExposedArray());
    }

    public function testAsExposedChangedArray(): void
    {
        $model = ExposableModelClass::fromArray(ModelClass::VALUES);

        $model->private  = 'test';
        $expectedExposed = [ModelClass::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    public function testAsExposedOnlyArray(): void
    {
        $model = ExposableModelClass::fromArray(ModelClass::VALUES);

        $model->private  = 'test';
        $expectedExposed = [ModelClass::PRIVATE => 'test'];
        self::assertSame($expectedExposed, $model->asExposedOnlyArray());
    }

    public function testExpose(): void
    {
        $model = ExposableModelClass::fromArray(ModelClass::VALUES);

        $model->expose(ModelClass::PRIVATE);
        self::assertSame(ModelClass::VALUES, $model->asArray());
        self::assertSame(ModelClass::VALUES, $model->asExposedArray());

        // asExposed methods call unexpose and so remove the exposable properties if that array is set
        $model->expose(ModelClass::PRIVATE);
        $model->private = 'test';
        $expected       = [ModelClass::PRIVATE => 'test'];
        self::assertSame($expected, $model->asChangedArray());
        self::assertSame($expected, $model->asExposedChangedArray());

        $model->private = ModelClass::PRIVATE;

        $expected = [
            ModelClass::PUBLIC    => ModelClass::PUBLIC,
            ModelClass::NULLABLE  => null,
            ModelClass::PROTECTED => ModelClass::PROTECTED,
        ];
        self::assertSame($expected, $model->asArray());
        self::assertSame(ModelClass::VALUES, $model->asExposedArray());

        $model->private  = 'test';
        $expectedExposed = [ModelClass::PRIVATE => 'test'];
        self::assertSame([], $model->asChangedArray());
        self::assertSame($expectedExposed, $model->asExposedChangedArray());
    }

    /**
     * @throws JsonException
     */
    public function testJsonSerialize(): void
    {
        $model = ExposableModelClass::fromArray([]);

        $expected = '[]';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);

        $model = ExposableModelClass::fromArray(ModelClass::VALUES);

        $expected = '{"public":"public","nullable":null,"protected":"protected"}';
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
        $model->expose(ModelClass::PRIVATE);
        $expectedExposed = '{"public":"public","nullable":null,"protected":"protected","private":"private"}';
        self::assertSame($expectedExposed, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expectedExposed, (string) $model);
        $model->unexpose();
        self::assertSame($expected, json_encode($model, JSON_THROW_ON_ERROR));
        self::assertSame($expected, (string) $model);
    }
}
