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

use Valkyrja\Orm\Entity\Trait\DatedFields;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class DatedFieldsTest extends TestCase
{
    public function testHasDateCreatedProperty(): void
    {
        $class = new class {
            use DatedFields;
        };

        $class->created_at = '01-26-2026 12:00:00 UTC';

        self::assertSame('01-26-2026 12:00:00 UTC', $class->created_at);
    }

    public function testHasDateModifiedProperty(): void
    {
        $class = new class {
            use DatedFields;
        };

        $class->updated_at = '01-26-2026 12:00:00 UTC';

        self::assertSame('01-26-2026 12:00:00 UTC', $class->updated_at);
    }
}
