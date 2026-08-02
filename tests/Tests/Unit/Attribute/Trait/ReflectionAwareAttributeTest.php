<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
