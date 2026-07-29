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
use Valkyrja\Orm\Entity\Trait\DatedFields;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class DatedFieldsTest extends TestCase
{
    public function testHasDateCreatedProperty(): void
    {
        $class = new class {
            use DatedFields;
        };

        // A property declaration has no behavior to exercise; assert the shape the
        // test name claims instead of reading back what was just assigned.
        $property = new ReflectionProperty($class::class, 'created_at');

        self::assertTrue($property->isPublic());
        self::assertSame('string', (string) $property->getType());
    }

    public function testHasDateModifiedProperty(): void
    {
        $class = new class {
            use DatedFields;
        };

        // A property declaration has no behavior to exercise; assert the shape the
        // test name claims instead of reading back what was just assigned.
        $property = new ReflectionProperty($class::class, 'updated_at');

        self::assertTrue($property->isPublic());
        self::assertSame('string', (string) $property->getType());
    }
}
