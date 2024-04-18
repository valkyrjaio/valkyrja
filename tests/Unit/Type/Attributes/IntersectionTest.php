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
use Valkyrja\Type\Attributes\Intersection;

class IntersectionTest extends TestCase
{
    public function testConstruct(): void
    {
        $value     = new Type('test');
        $attribute = new Intersection($value);

        self::assertSame([$value], $attribute->types);
    }
}
