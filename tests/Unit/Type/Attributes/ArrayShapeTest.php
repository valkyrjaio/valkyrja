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

namespace Valkyrja\Tests\Unit\Type\Attributes;

use Valkyrja\Tests\Classes\Type\Type;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Attributes\ArrayShape;
use Valkyrja\Type\Attributes\Intersection;
use Valkyrja\Type\Attributes\Union;

class ArrayShapeTest extends TestCase
{
    public function testDefault(): void
    {
        $attribute = new ArrayShape();

        self::assertSame([], $attribute->shape);
    }

    public function testArrayWithType(): void
    {
        $value     = ['fire' => new Type('test')];
        $attribute = new ArrayShape($value);

        self::assertSame($value, $attribute->shape);
    }

    public function testArrayWithIntersection(): void
    {
        $value     = ['fire' => new Intersection(new Type('test'))];
        $attribute = new ArrayShape($value);

        self::assertSame($value, $attribute->shape);
    }

    public function testArrayWithUnion(): void
    {
        $value     = ['fire' => new Union(new Type('test'))];
        $attribute = new ArrayShape($value);

        self::assertSame($value, $attribute->shape);
    }
}
