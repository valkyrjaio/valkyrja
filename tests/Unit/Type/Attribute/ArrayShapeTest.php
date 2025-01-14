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

namespace Valkyrja\Tests\Unit\Type\Attribute;

use Valkyrja\Tests\Classes\Type\TypeClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Attribute\ArrayShape;
use Valkyrja\Type\Attribute\Intersection;
use Valkyrja\Type\Attribute\Union;

class ArrayShapeTest extends TestCase
{
    public function testDefault(): void
    {
        $attribute = new ArrayShape();

        self::assertSame([], $attribute->shape);
    }

    public function testArrayWithType(): void
    {
        $value     = ['fire' => new TypeClass('test')];
        $attribute = new ArrayShape($value);

        self::assertSame($value, $attribute->shape);
    }

    public function testArrayWithIntersection(): void
    {
        $value     = ['fire' => new Intersection(new TypeClass('test'))];
        $attribute = new ArrayShape($value);

        self::assertSame($value, $attribute->shape);
    }

    public function testArrayWithUnion(): void
    {
        $value     = ['fire' => new Union(new TypeClass('test'))];
        $attribute = new ArrayShape($value);

        self::assertSame($value, $attribute->shape);
    }
}
