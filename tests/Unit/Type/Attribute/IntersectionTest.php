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
use Valkyrja\Type\Attribute\Intersection;

class IntersectionTest extends TestCase
{
    public function testConstruct(): void
    {
        $value     = new TypeClass('test');
        $attribute = new Intersection($value);

        self::assertSame([$value], $attribute->types);
    }
}
