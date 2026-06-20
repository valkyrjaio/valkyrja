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

namespace Valkyrja\Tests\Unit\Attribute\Trait;

use ReflectionClass;
use Reflector;
use Valkyrja\Attribute\Trait\ReflectionAwareAttribute;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ReflectionAwareAttributeTest extends TestCase
{
    public function testSetAndGetReflection(): void
    {
        $attribute = new class {
            use ReflectionAwareAttribute;
        };

        $reflection = new ReflectionClass($attribute);

        $attribute->setReflection($reflection);

        self::assertInstanceOf(Reflector::class, $attribute->getReflection());
        self::assertSame($reflection, $attribute->getReflection());
    }
}
