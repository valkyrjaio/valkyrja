<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Entity\Trait;

use ReflectionProperty;
use Valkyrja\Orm\Entity\Trait\SoftDeleteFields;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SoftDeleteFieldsTest extends TestCase
{
    public function testHasDateDeletedPropertyWithDefaultNull(): void
    {
        $class = new class {
            use SoftDeleteFields;
        };

        self::assertNull($class->deleted_at);
    }

    public function testDateDeletedCanBeSet(): void
    {
        $class = new class {
            use SoftDeleteFields;
        };

        // A property declaration has no behavior to exercise; assert the shape the
        // test name claims instead of reading back what was just assigned.
        $property = new ReflectionProperty($class::class, 'deleted_at');

        self::assertTrue($property->isPublic());
        self::assertSame('?string', (string) $property->getType());
    }
}
