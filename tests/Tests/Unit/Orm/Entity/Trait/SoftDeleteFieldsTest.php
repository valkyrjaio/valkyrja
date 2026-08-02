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

        $class->deleted_at = '01-26-2026 12:00:00 UTC';

        self::assertSame('01-26-2026 12:00:00 UTC', $class->deleted_at);
    }
}
